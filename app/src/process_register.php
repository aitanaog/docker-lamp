<?php
	session_start();

	// Conectar a la base de datos
	$hostname = "db";  
	$username = "admin";  
	$password = "test";  
	$db = "database";  

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if (!$conn) {
	    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
	}

	// Recoger los datos del formulario
	$nombre = htmlspecialchars(strip_tags($_POST['nombre']));
	$apellidos = htmlspecialchars(strip_tags($_POST['apellidos']));
	$dni = htmlspecialchars(strip_tags($_POST['dni']));
	$telefono = htmlspecialchars(strip_tags($_POST['telefono']));
	$fecha_nacimiento = htmlspecialchars(strip_tags($_POST['fecha_nacimiento']));
	$email = htmlspecialchars(strip_tags($_POST['email']));
	$contrasenna = $_POST['contrasenna'];
	$contrasenna2 = $_POST['contrasenna2'];


	//verificar si las contraseñas coinciden
	if ($contrasenna !== $contrasenna2) {
	    $_SESSION['error_message'] = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
	    header("Location: register.php");
	    exit();
	}

	// Preparar las consultas SQL usando sentencias preparadas
	// CONSULTA 1: Verificar si el email ya existe
	$sql = "SELECT * FROM usuarios WHERE email = ?";
	$stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        
        // Vincular el parámetro y ejecutar la consulta
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
	// Cerrar la declaración
	$stmt->close();


	if ($result->num_rows > 0) {
	    // Guardar el mensaje de error en una variable de sesión
	    $_SESSION['error_message'] = "Este email ya está registrado. Por favor, elige otro.";
	    header("Location: register.php");
	    exit();
	}

	// CONSULTA2: Insertar los datos en la base de datos
	$sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, contrasenna)
		VALUES (?, ?, ?, ?, ?, ?, ?)";

	$stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        
        //Vincular los parámetros
	stmt->bind_param("sssssss", $nombre, $apellidos, $dni, $telefono, $fecha_nacimiento, $email, $contrasenna);

	//Ejecutar la consulta
	if ($stmt->execute()) {
	    $_SESSION['error_message'] = "Se ha registrado correctamente.";
	    header("Location: register.php");
	} else {
	    echo "Error: " . mysqli_stmt_error($stmt);
	}

	// Cerrar la conexión
	mysqli_close($conn);
?>
