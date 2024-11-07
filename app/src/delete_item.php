<?php
	session_start();
	
	// Generar un token CSRF si no existe en la sesión
	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio de 32 bytes
	}
	$csrf_token = $_SESSION['csrf_token']; // Almacenas el token en una variable
	
	
	echo '<head>';
	    echo'<meta charset="UTF-8">  ';									
	    echo'<meta name="viewport" content="width=device-width, initial-scale=1.0">';
	    echo'<title>localhost:81/delete_item?</title>';
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
	echo '<li style="display: inline; margin-right: 15px;"><a href="items.php">Mostrar playlist</a></li>';
	echo '<li style="display: inline; margin-right: 15px;"><a href="add_item.php">Añadir canción</a></li>';
	echo '<li style="display: inline; margin-right: 15px;"><a href="delete_item.php">Eliminar canción</a></li>';
	echo '<li style="display: inline; margin-right: 15px;"><a href="modify_item.php">Modificar canción</a></li>';
	echo '</ul>';
	echo '</nav>';

	// Conexión a la base de datos
	$hostname = "db";  // Cambia si es necesario
	$username = "admin";  // Cambia si es necesario
	$password = "test";  // Cambia si es necesario
	$db = "database";  // Cambia si es necesario

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
	    die("Database connection failed: " . $conn->connect_error);
	}

	// Mostrar mensajes de estado
	if (isset($_GET['msg'])) {
	    if ($_GET['msg'] == 'success') {
		echo "<p style='color: green;'>Canción eliminada con éxito.</p>";
	    } elseif ($_GET['msg'] == 'error') {
		echo "<p style='color: red;'>Error al eliminar la canción.</p>";
	    } elseif ($_GET['msg'] == 'no_id') {
		echo "<p style='color: orange;'>No se recibió el ID de la canción.</p>";
	    }
	}

	// Consulta para obtener las canciones
	$query = mysqli_query($conn, "SELECT * FROM canciones") or die (mysqli_error($conn));

	// Mostrar los resultados en una tabla
	echo '<h2>Lista de Canciones</h2>';
	echo '<table border="1">
		<tr>
		    <th>ID</th>
		    <th>Nombre de la Canción</th>
		    <th>Artista</th>
		    <th>Acciones</th> <!-- Nueva columna para acciones -->
		</tr>';

	// Iterar sobre los resultados
	while ($row = mysqli_fetch_array($query)) {
	    echo "<tr>
		    <td>{$row['id']}</td>
		    <td>{$row['nombre_cancion']}</td>
		    <td>{$row['cantante']}</td>
		    <td>
		        <form method='POST' action='process_delete_item.php' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta canción?\");'> <!-- Confirmación antes de eliminar -->
		            <input type='hidden' name='id' value='{$row['id']}'> <!-- ID de la canción -->
		            <button type='submit' id='item_delete_submit'>Eliminar</button>
		        </form>
		    </td>
		  </tr>";
	}

	echo '</table>';

	// Cerrar la conexión
	mysqli_close($conn);
?>

