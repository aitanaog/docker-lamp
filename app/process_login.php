<?php
	// Iniciar la sesión
	session_start();
	
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
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$contrasenna = $_POST['contrasenna']; 

	// Verificar si el email ya existe
	$email_query = mysqli_query($conn, "SELECT contrasenna FROM usuarios WHERE email='$email'");
	if (mysqli_num_rows($email_query) > 0) {
   		 // Obtener el hash de la contraseña del usuario
    		$fila = mysqli_fetch_assoc($email_query);
    		$hashed_password = $fila['contrasenna'];

    		// Verificar la contraseña ingresada con el hash almacenado
    		if (password_verify($contrasenna, $hashed_password)) {
    			$_SESSION['user_email'] = $email;		// Almacenamos el email de usuario en la sesión
        		header("Location: inicio.php"); 		// Cambia 'dashboard.php' por la página a la que deseas redirigir
        		exit(); 					// Importante: detiene la ejecución para evitar que se siga ejecutando el script
    		} else {
        	$_SESSION['error_message'] ="La contraseña no coincide.";
        	header("Location: login.php");
    		exit();	
   		}
	} else {
   	 	$_SESSION['error_message'] ="El email no existe.";
   	     	header("Location: login.php");
    		exit();	
	}

	// Cerrar la conexión
	mysqli_close($conn);
?>

