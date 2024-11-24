<?php
	// Iniciar la sesión para acceder a $_SESSION
	session_start([
		'cookie_lifetime' => 86400,
		'cookie_httponly' => true,
		'cookie_secure' => true,
	]);

	// Generar un token CSRF si no existe en la sesión
	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	// Obtener el token CSRF para incluirlo en el formulario
	$csrf_token = $_SESSION['csrf_token'];
	?>

	<!DOCTYPE html>
	<html lang="es">  
	<head>
	    <meta charset="UTF-8">  
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Security-Policy"
      content="default-src 'none';
               script-src 'self' 'unsafe-inline';
               style-src 'self' 'unsafe-inline';
               img-src 'self' http://localhost:81;
               form-action 'self';">

		<title>Añadir Canción</title>
	    <link rel="stylesheet" href="../css/styles.css">
	</head>
	<body>
	    <?php
	    if (isset($_SESSION['error_message'])) {
		echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
		unset($_SESSION['error_message']);
	    }
	    ?>

	    <nav>
		<ul style="list-style-type: none; padding: 0;">
		    <!-- Links de navegación -->
		    <li><a href="logout.php">Cerrar Sesión</a></li>
		    <li><a href="modify_user.php">Modificar perfil</a></li>
		    <li><a href="items.php">Mostrar playlist</a></li>
		    <li><a href="add_item.php">Añadir canción</a></li>
		    <li><a href="delete_item.php">Eliminar canción</a></li>
		    <li><a href="modify_item.php">Modificar canción</a></li>
		</ul>
	    </nav>

	    <h2>Añadir Canción</h2>
	    <form id="item_add_form" action="process_add_item.php" method="POST">
		<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

		<label for="nombre">Nombre de la canción:</label>
		<input type="text" id="nombre" name="nombre" required>
		<br>

		<label for="cantante">Cantante:</label>
		<input type="text" id="cantante" name="cantante" required>
		<br>

		<label for="album">Álbum:</label>
		<input type="text" id="album" name="album" required>
		<br>

		<label for="genero">Género musical:</label>
		<input type="text" id="genero" name="genero" required>
		<br>

		<label for="fecha_lanzamiento">Fecha de lanzamiento:</label>
		<input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" required>
		<br>

		<button type="submit">Añadir canción</button>
	    </form>
	</body>
	</html>

