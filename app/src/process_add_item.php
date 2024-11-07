<?php
	// process_add_item.php

	// Iniciar la sesión
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("Error al procesar la solicitud.");
	    }
	    
	// Conectar a la base de datos
	$hostname = "db"; 
	$username = "admin"; 
	$password = "test"; 
	$db = "database"; 

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
    		die("Database connection failed: " . $conn->connect_error);
	}

	// Recoger los datos del formulario
	$nombre = htmlspecialchars(strip_tags($_POST['nombre']));
	$cantante = htmlspecialchars(strip_tags($_POST['cantante']));
	$album = htmlspecialchars(strip_tags($_POST['album']));
	$genero = htmlspecialchars(strip_tags($_POST['genero']));
	$fecha_lanzamiento = htmlspecialchars(strip_tags($_POST['fecha_lanzamiento']));

	
	// Preparar las consultas SQL usando sentencias preparadas
	
	//CONSULTA 1: Verificar si cancion ya existe
        $sql = "SELECT * FROM canciones WHERE nombre_cancion= ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        
        // Vincular los parámetros
        $stmt->bind_param("s", $nombre);

        // Ejecutar la consulta
        $stmt->execute();
	$nombre_cancion= $stmt->get_result();
	if ($nombre_cancion->num_rows > 0){
    		$_SESSION['error_message'] ="Esta canción ya está en la lista.";
    		header("Location:add_item.php"); 		
        	exit();
        }
        
        // Cerrar la declaración
        $stmt->close();

	//CONSULTA 2: Insertar los datos en la base de datos
	$sql = "INSERT INTO canciones (nombre_cancion, cantante, genero_musical, album, fecha_lanzamiento)
        VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

	// Vincular los parámetros
        $stmt->bind_param("sssss", $nombre, $cantante, $album, $genero, $fecha_lanzamiento);
        
        // Ejecutar la consulta
	if ($stmt->execute()) {
    		$_SESSION['error_message'] ="Canción añadida correctamente.";
    		header("Location:add_item.php"); 
    		exit();		
	} else {
    	    $_SESSION['error_message'] = "Error al añadir la canción: " . $stmt->error;
    	    header("Location: add_item.php"); 
    	    exit();
	}
	
	// Cerrar la declaración
        $stmt->close();


	mysqli_close($conn);
?>

