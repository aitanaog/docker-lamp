<?php
	// process_add_item.php

	// Conectar a la base de datos
	$hostname = "db"; // Cambia por tu hostname
	$username = "admin"; // Cambia por tu username
	$password = "test"; // Cambia por tu password
	$db = "database"; // Cambia por tu database name

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
    		die("Database connection failed: " . $conn->connect_error);
	}

	// Recoger los datos del formulario
	$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
	$cantante = mysqli_real_escape_string($conn, $_POST['cantante']);
	$album = mysqli_real_escape_string($conn, $_POST['album']);
	$genero = mysqli_real_escape_string($conn, $_POST['genero']);
	$fecha_lanzamiento = mysqli_real_escape_string($conn, $_POST['fecha_lanzamiento ']);

	
	//Verificar si cancion ya existe
	$nombre_query = mysqli_query($conn, "SELECT * FROM canciones WHERE nombre='$nombre");
	if (mysqli_num_rows($nombre_query) > 0) {
    		die("Esta canción ya está en la lista.");
	}

	// Insertar los datos en la base de datos
	$sql = "INSERT INTO canciones (nombre, cantante, genero, album, fecha_lanzamiento)
        VALUES ('$nombre','$cantante', '$album', '$genero', '$fecha_lanzamiento')";

	if (mysqli_query($conn, $sql)) {
    		echo "Canción añadida correctamente";
	} else {
    	echo "Error: " . mysqli_error($conn);
	}

	// Cerrar la conexión
	mysqli_close($conn);
?>

