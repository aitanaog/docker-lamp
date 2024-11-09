<?php
session_start();

	// Validar CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	    die("Error al procesar la solicitud.");
	}

	// Conexión a la base de datos
	$hostname = "db";  
	$username = "admin";  
	$password = "test";  
	$db = "database";  

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
	    die("Database connection failed: " . $conn->connect_error);
	}

	// Verificar si se ha recibido el ID de la canción a eliminar
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
	    // Validar y sanitizar el ID para asegurarse de que es un número entero
	    $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
	    if ($id === false) {
		header("Location: delete_item.php?msg=invalid_id");
		exit();
	    }

	    // Preparar la consulta SQL para eliminar la canción
	    $sql = "DELETE FROM canciones WHERE id = ?";
	    $stmt = $conn->prepare($sql);
	    if ($stmt === false) {
		die("Error en la preparación de la consulta: " . $conn->error);
	    }

	    $stmt->bind_param("i", $id);
	    
	    if ($stmt->execute()) {
		header("Location: delete_item.php?msg=success");
		exit();
	    } else {
		header("Location: delete_item.php?msg=error");
		exit();
	    }

	    $stmt->close();
	}

	mysqli_close($conn);



        
