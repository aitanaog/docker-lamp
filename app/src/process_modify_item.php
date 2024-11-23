<?php
	session_start();

	// Verificar que la solicitud sea POST
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    // Validar el token CSRF
	    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("Error al procesar la solicitud");
	    }

	    // Validar el ID de la canción
	    if (isset($_POST["id"])) {
		$id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
		if ($id === false) {
		    header("Location: modify_item.php?msg=invalid_id");
		    exit();
		}

		// Conexión a la base de datos
		$hostname = "db";
		$username = "admin";
		$password = "sgssi_proyecto";
		$db = "database";

		$conn = new mysqli($hostname, $username, $password, $db);
		if ($conn->connect_error) {
		    die("Database connection failed: " . $conn->connect_error);
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
		    die("Error al procesar la solicitud" . $conn->error);
		}

		$stmt->bind_param("sssssi", $nombre, $cantante, $album, $genero, $fecha_lanzamiento, $id);

		// Ejecutar la consulta
		if ($stmt->execute()) {
		    header("Location: modify_item.php?msg=success");
		    exit();
		} else {
		    header("Location: modify_item.php?msg=error");
		    exit();
		}

		// Cerrar la declaración y la conexión
		$stmt->close();
		$conn->close();
	    } else {
		header("Location: modify_item.php?msg=no_id");
		exit();
	    }
	}
?>


