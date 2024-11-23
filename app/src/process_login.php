<?php
session_start([
	'cookie_lifetime' => 86400,
	'cookie_httponly' => true,
	'cookie_secure' => true,
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Verificar el token de CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    	die("Error al procesar la solicitud.");
	}

	// Verificar reCAPTCHA
	$captcha_secret = '6LcPen4qAAAAAJN8fXyfrXjrgBj7KAX7F5KXw0gN';  
	$captcha_response = $_POST['g-recaptcha-response'];

	// Validar la respuesta de reCAPTCHA con la API de Google
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$captcha_secret&response=$captcha_response");
	$response_keys = json_decode($response, true);

	if (!$response_keys["success"]) {
    	$_SESSION['error_message'] = "Error al procesar la solicitud.";
    	header("Location: login.php");
    	exit();
	}

	// Conectar a la base de datos
	$hostname = "db";
	$username = "admin";
	$password = "sgssi_proyecto";
	$db = "database";
	$conn = new mysqli($hostname, $username, $password, $db);
	if ($conn->connect_error) {
    	die("Database connection failed: " . $conn->connect_error);
	}

	// Configuración de intentos fallidos
	$limite_intentos = 3;
	$periodo_intentos = 24 * 60 * 60;
	$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
	$contrasenna = trim($_POST['contrasenna'] ?? '');
	if (!$email || !$contrasenna) {
    	$_SESSION['error_message'] = "Error al procesar la solicitud.";
    	header("Location: login.php");
    	exit();
	}

	// Consulta de autenticación
	$sql = "SELECT id, semilla, contrasenna FROM usuarios WHERE email = ? LIMIT 1";
	$stmt = $conn->prepare($sql);
	if ($stmt === false) {
	    // Registrar el error para revisión interna
	    error_log("Error al procesar la solicitud: " . $conn->error);

	    // Mostrar mensaje genérico al usuario
	    die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
	}
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result->num_rows > 0) {
    	$fila = $result->fetch_assoc();
    	$id_usuario = $fila['id'];
    	$salt = $fila['semilla'];
    	$contrasenna_almacenada = $fila['contrasenna'];
    	$hash_contrasenna = hash('sha256', $contrasenna . $salt);

    	// Consultar intentos fallidos
    	$sql_fallidos = "SELECT COUNT(*) AS intentos FROM login_fallidos WHERE id_usuario = ? AND fecha > NOW() - INTERVAL 1 DAY";
    	$stmt = $conn->prepare($sql_fallidos);
    	if ($stmt === false) {
	    // Registrar el error para revisión interna
	    error_log("Error al procesar la solicitud: " . $conn->error);

	    // Mostrar mensaje genérico al usuario
	    die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
    	}
    	$stmt->bind_param("i", $id_usuario);
    	$stmt->execute();
    	$result_fallidos = $stmt->get_result();
    	$stmt->close();
    	$intentos_fallidos = $result_fallidos->fetch_assoc()['intentos'];

    	// Verificación de contraseña y control de intentos fallidos
    	if ($hash_contrasenna === $contrasenna_almacenada && $intentos_fallidos < $limite_intentos) {
        	$_SESSION['email'] = $email;
        	$_SESSION['id'] = $id_usuario;

        	// Limpiar intentos fallidos
        	$sql_borrar_intentos = "DELETE FROM login_fallidos WHERE id_usuario = ?";
        	$stmt = $conn->prepare($sql_borrar_intentos);
        	if ($stmt === false) {
		    // Registrar el error para revisión interna
		    error_log("Error al procesar la solicitud: " . $conn->error);

		    // Mostrar mensaje genérico al usuario
		    die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
        	}
        	$stmt->bind_param("i", $id_usuario);
        	$stmt->execute();
        	$stmt->close();

        	header("Location: ../index.php");
        	exit();
    	} else {
        	// Registrar intento fallido
        	$sql_insertar_fallido = "INSERT INTO login_fallidos (id_usuario, fecha) VALUES (?, NOW())";
        	$stmt = $conn->prepare($sql_insertar_fallido);
        	if ($stmt === false) {
		    // Registrar el error para revisión interna
		    error_log("Error al procesar la solicitud: " . $conn->error);

		    // Mostrar mensaje genérico al usuario
		    die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
        	}
        	$stmt->bind_param("i", $id_usuario);
        	$stmt->execute();
        	$stmt->close();
        	$_SESSION['error_message'] = "Error al procesar la solicitud";
        	header("Location: login.php");
        	exit();
    	}
	} else {
    	$_SESSION['error_message'] = "Error al procesar la solicitud";
    	header("Location: login.php");
    	exit();
	}
}

$conn->close();
?>

