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

	// Verificar si se ha recibido el ID de la canción a eliminar
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
	    // Verificar que se haya recibido el ID
	    if (isset($_POST["id"])) {
		    // Validar y sanitizar el ID para asegurarse de que es un número entero
		     $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
		     if ($id === false) {
			   header("Location: delete_item.php?msg=invalid_id");
			   exit();
		     }
	   
		    // Preparar la consulta SQL usando sentencias preparadas
		    // Consulta para eliminar la canción
		    $sql = "DELETE FROM canciones WHERE id= ?";
		    $stmt = $conn->prepare($sql);
	       	    if ($stmt === false) {
		    	die("Error en la preparación de la consulta: " . $conn->error);
		     }
		     
		    // Vincular los parámetros 
		    $stmt->bind_param("i", $id);
		
		    if ($stmt->execute()) {
			// Si se elimina con éxito, redirigir de vuelta
			header("Location: delete_item.php?msg=success");
			exit(); 
		    } else {
			// En caso de error, redirigir de vuelta con un mensaje de error
			header("Location: delete_item.php?msg=error");
			exit();
		    }
		} 
	else {
		// Si no se recibe el ID, redirigir con un mensaje de error
		header("Location: delete_item.php?msg=no_id");
		exit();
	    }
	}

	// Cerrar la conexión
	mysqli_close($conn);
?>




        
