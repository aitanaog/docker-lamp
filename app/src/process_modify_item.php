<?php
	// Conexión a la base de datos
	$hostname = "db"; 
	$username = "admin"; 
	$password = "test"; 
	$db = "database"; 

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
	    die("Database connection failed: " . $conn->connect_error);
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    // Verificar que se haya recibido el ID
	    if (isset($_POST["id"])) {
		// Validar y sanitizar el ID para asegurarse de que es un número entero
		$id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
		if ($id === false) {
		    header("Location: modify_item.php?msg=invalid_id");
		    exit();
		}
		
		// Recoger y sanitizar los datos del formulario 
		$nombre = htmlspecialchars(strip_tags($_POST['nombre_cancion']));
		$cantante = htmlspecialchars(strip_tags($_POST['cantante']));
		$album = htmlspecialchars(strip_tags($_POST['album']));
		$genero = htmlspecialchars(strip_tags($_POST['genero']));
		$fecha_lanzamiento = htmlspecialchars(strip_tags($_POST['fecha_lanzamiento']));

		// Preparar la consulta SQL usando sentencias preparadas
		$sql = "UPDATE canciones SET nombre_cancion=?, cantante=?, album=?, genero_musical=?, fecha_lanzamiento=? WHERE id=?";
		$stmt = $conn->prepare($sql);
		if ($stmt === false) {
		    die("Error en la preparación de la consulta: " . $conn->error);
		}

		// Vincular los parámetros 
		$stmt->bind_param("sssssi", $nombre, $cantante, $album, $genero, $fecha_lanzamiento, $id);

		// Ejecutar la consulta
		if ($stmt->execute()) {
		    // Si se cambia con éxito, redirigir de vuelta
		    header("Location: modify_item.php?msg=success");
		    exit(); 
		} else {
		    // En caso de error, redirigir de vuelta con un mensaje de error
		    header("Location: modify_item.php?msg=error");
		    exit();
		}

		// Cerrar la declaración
		$stmt->close();
	    } else {
		// Si no se recibe el ID, redirigir con un mensaje de error
		header("Location: modify_item.php?msg=no_id");
		exit();
	    }
	}

	// Cerrar la conexión
	$conn->close();

?>

