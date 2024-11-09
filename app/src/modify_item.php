<?php
	session_start();

	// Generar un token CSRF si no existe en la sesión
	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	// Obtener el token CSRF para incluirlo en el formulario
	$csrf_token = $_SESSION['csrf_token'];

	echo '<head>';
	    echo'<meta charset="UTF-8">  									
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>localhost:81/modify_item</title>';
	  echo '  <link rel="stylesheet" href="../css/styles.css">';
	    echo '</head>';
	    
	// Crear un menú de navegación
	  echo '<nav>';
	  echo '<ul style="list-style-type: none; padding: 0;">';
	      if (isset($_SESSION['user_email'])) {
		// La sesión está iniciada, el usuario está autenticado
		echo '<li style="display: inline; margin-right: 15px;"><a href="logout.php">Cerrar Sesión</a></li>';
		echo '<li style="display: inline; margin-right: 15px;"><a href="modify_user.php">Modificar perfil</a></li>';
	    } else {
		// No hay sesión iniciada
		echo '<li style="display: inline; margin-right: 15px;"><a href="register.php">Página de registro</a></li>';
		echo '<li style="display: inline; margin-right: 15px;"><a href="login.php">Log in</a></li>';
	    } 
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/items.php">Mostrar playlist</a></li>';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/add_item.php">Añadir cancion</a></li>';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/delete_item.php">Eliminar cancion</a></li>';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/modify_item.php">Modificar cancion</a></li>';
	  echo '</ul>';
	  echo '</nav>';
	  
	// Conexión a la base de datos
	$hostname = "db";
	$username = "admin";
	$password = "test";
	$db = "database";

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
	    die("Database connection failed: " . $conn->connect_error);
	}

	// Obtener la lista de canciones
	$sql = "SELECT * FROM canciones";
	$query = $conn->query($sql);

	if ($query === false) {
	    error_log("Error en la consulta SQL: " . $conn->error);
	    echo "<p style='color: red;'>Ha ocurrido un error. Inténtalo de nuevo más tarde.</p>";
	}

	// Mostrar mensajes de estado
	if (isset($_GET['msg'])) {
	    if ($_GET['msg'] == 'success') {
		echo "<p style='color: green;'>Canción modificada con éxito.</p>";
	    } elseif ($_GET['msg'] == 'error') {
		echo "<p style='color: red;'>Error al modificar la canción: " . htmlspecialchars($_GET['error']) . "</p>";
	    } elseif ($_GET['msg'] == 'no_id') {
		echo "<p style='color: orange;'>No se recibió el ID de la canción.</p>";
	    }
	}

	echo '<div class="container">';
	echo '<div class="table-container">';
	echo '<h2>Lista de Canciones</h2>';
	echo '<table border="1">
	    <tr>
		<th>ID</th>
		<th>Nombre de la Canción</th>
		<th>Artista</th>
		<th>Acciones</th>
	    </tr>';

	// Iterar sobre los resultados
	while ($row = $query->fetch_assoc()) {
	    echo "<tr>
		<td>{$row['id']}</td>
		<td>{$row['nombre_cancion']}</td>
		<td>{$row['cantante']}</td>
		<td>
		    <form method='GET' action='modify_item.php'>
		        <input type='hidden' name='id' value='{$row['id']}'>
		        <input type='submit' value='Modificar'>
		    </form>
		</td>
	      </tr>";
	}
	echo '</table>';
	echo '</div>'; // Cerrar contenedor de la tabla

	// Mostrar formulario de modificación si se selecciona una canción
	if (isset($_GET['id'])) {
	    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
	    if ($id === false) {
		die("ID inválido.");
	    }

	    $sql = "SELECT * FROM canciones WHERE id = ?";
	    $stmt = $conn->prepare($sql);
	    if ($stmt === false) {
		die("Error en la preparación de la consulta: " . $conn->error);
	    }

	    $stmt->bind_param("i", $id);
	    $stmt->execute();
	    $result = $stmt->get_result();

	    if ($result->num_rows > 0) {
		$cancion_selec = $result->fetch_assoc();
		?>
		<form id="item_modify_form" method="POST" action="process_modify_item.php">
		    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
		    <input type="hidden" name="id" value="<?php echo $cancion_selec['id']; ?>">
		    <label for="nombre_cancion">Nombre de la Canción:</label>
		    <input type="text" id="nombre_cancion" name="nombre_cancion" value="<?php echo $cancion_selec['nombre_cancion']; ?>" required>
		    <br>
		    <label for="cantante">Artista:</label>
		    <input type="text" id="cantante" name="cantante" value="<?php echo $cancion_selec['cantante']; ?>" required>
		    <br>
		    <label for="fecha_lanzamiento">Fecha de Lanzamiento:</label>
		    <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" value="<?php echo $cancion_selec['fecha_lanzamiento']; ?>" required>
		    <br>
		    <label for="genero">Genero:</label>
		    <input type="text" id="genero_musical" name="genero" value="<?php echo $cancion_selec['genero_musical']; ?>" required>
		    <br>
		    <label for="album">Álbum:</label>
		    <input type="text" id="album" name="album" value="<?php echo $cancion_selec['album']; ?>" required>
		    <br>
		    <button type="submit" id="item_modify_submit">Confirmar</button>
		</form>
		<?php
	    } else {
		echo "<p style='color: red;'>Canción no encontrada.</p>";
	    }

	    $stmt->close();
	}

	$conn->close();
?>


