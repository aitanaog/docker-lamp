<?php
	session_start([
	    'cookie_lifetime' => 86400,
	    'cookie_httponly' => true,
	    'cookie_secure' => true,
	]);
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("Error al procesar la solicitud.");
	    }

	// Conectar a la base de datos
	$hostname = "db"; 
	$username = "admin"; 
	$password = "test"; 
	$db = "database"; 

	$conn = new mysqli($hostname, $username, $password, $db);
	if ($conn->connect_error) {
	    die("Database connection failed: " . $conn->connect_error);
	}

	// Configuración de intentos fallidos
	$limite_intentos = 3;
	$periodo_intentos = 24 * 60 * 60;


	    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
	    $contrasenna = $_POST['contrasenna'];

	    if (!$email || !$contrasenna) {
		$_SESSION['error_message'] = "Credenciales inválidas.";
		header("Location: login.php");
		exit();
	    }

	    // Consulta de autenticación
	    $sql = "SELECT id, contrasenna FROM usuarios WHERE email = ?";
	    $stmt = $conn->prepare($sql);
	    if ($stmt === false) {
            	die("Error en la preparación de la consulta: " . $conn->error);
       	     }

	    $stmt->bind_param("s", $email);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $stmt->close();

	    if ($result->num_rows > 0) {
		$fila = $result->fetch_assoc();
		$id_usuario = $fila['id'];
		$contrasenna_almacenada = $fila['contrasenna'];

		// Consultar intentos fallidos
		$sql_fallidos = "SELECT COUNT(*) AS intentos FROM login_fallidos WHERE id_usuario = ? AND fecha > NOW() - INTERVAL 1 DAY";
		$stmt = $conn->prepare($sql_fallidos);
	    	if ($stmt === false) {
            		die("Error en la preparación de la consulta: " . $conn->error);
       	     	}
		$stmt->bind_param("i", $id_usuario);
		$stmt->execute();
		$result_fallidos = $stmt->get_result();
		$stmt->close();

		$intentos_fallidos = $result_fallidos->fetch_assoc()['intentos'];
		$mostrar_captcha = $intentos_fallidos >= $limite_intentos;

		// Verificación de contraseña y CAPTCHA
		if ($contrasenna === $contrasenna_almacenada && (!$mostrar_captcha || (isset($_POST['g-recaptcha-response']) && validarCaptcha($_POST['g-recaptcha-response'])))) {
		    $_SESSION['user_email'] = $email;
		    $_SESSION['id'] = $id_usuario;

		    // Limpiar intentos fallidos
		    $sql_borrar_intentos = "DELETE FROM login_fallidos WHERE id_usuario = ?";
		    $stmt = $conn->prepare($sql_borrar_intentos);
		    if ($stmt === false) {
		    	die("Error en la preparación de la consulta: " . $conn->error);
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
		    	die("Error en la preparación de la consulta: " . $conn->error);
	       	     }
		    $stmt->bind_param("i", $id_usuario);
		    $stmt->execute();
		    $stmt->close();

		    $_SESSION['error_message'] = "Credenciales incorrectas o CAPTCHA requerido.";
		    $_SESSION['mostrar_captcha'] = $mostrar_captcha;
		    header("Location: login.php");
		    exit();
		}
	    } else {
		$_SESSION['error_message'] = "Credenciales incorrectas.";
		header("Location: login.php");
		exit();
	    }
	}

	$conn->close();

	function validarCaptcha($captcha_response) {
	    $secret_key = "TU_SECRET_KEY";
	    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
	    return json_decode($verify)->success;
	}
?>


