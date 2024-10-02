<?php
	// process_register.php
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
	$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
	$apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
	$dni = mysqli_real_escape_string($conn, $_POST['dni']);
	$telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
	$fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$contrasenna = mysqli_real_escape_string($conn, $_POST['contrasenna']);

	//GENERAR HASH EN CONTRASEÑA
	$hashed_password = password_hash($contrasenna, PASSWORD_BCRYPT);
	
	//Verificar si email ya existe
	$email_query = mysqli_query($conn, "SELECT * FROM usuarios WHERE email='$email'");
	if (mysqli_num_rows($email_query) > 0) {
		// Guardar el mensaje de error en una variable de sesión
    		$_SESSION['error_message'] = "Este email ya está registrado. Por favor, elige otro.";
    		header("Location: register.php");
    		exit();	
	}


	// Insertar los datos en la base de datos, FALTA ALGÚN METODO PARA NO METER LA CONTRASEÑA TAL CUAL
	$sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, contrasenna)
        VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fecha_nacimiento', '$email','$hashed_password')";
	
	if (mysqli_query($conn, $sql)) {
    		echo "Registro exitoso.";
	} else {
    	echo "Error: " . mysqli_error($conn);
	}
	
	// Cerrar la conexión
	mysqli_close($conn);
?>

