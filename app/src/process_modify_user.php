<?php
	session_start([
	    'cookie_lifetime' => 86400,
	    'cookie_httponly' => true,
	    'cookie_secure' => true,
	]);

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
	    // Verificar el token CSRF
	    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("Error al procesar la solicitud");
	    }

	    // Conexión a la base de datos
	    $hostname = "db";
	    $username = "admin";
	    $password = "sgssi_proyecto";
	    $db = "database";

	    $conn = mysqli_connect($hostname, $username, $password, $db);
	    if ($conn->connect_error) {
		die("Error de conexión a la base de datos: " . $conn->connect_error);
	    }

	    // Validar y obtener los datos del formulario
	    $field = $_POST['field'] ?? null;
	    $new_value = $_POST['new_value'] ?? null;
	    $user_email = $_SESSION['email'] ?? null;

	    if (!$field || !$new_value || !$user_email) {
		die("Datos inválidos. Por favor, inténtelo nuevamente.");
	    }

	    // Validar el campo permitido
	    $allowed_fields = ['nombre', 'apellidos', 'email', 'dni', 'telefono', 'fecha_nacimiento'];
	    if (!in_array($field, $allowed_fields)) {
		die("Campo no válido.");
	    }

	    // Validar valores según el campo
	    if ($field == 'email' && !filter_var($new_value, FILTER_VALIDATE_EMAIL)) {
	    $_SESSION['error_message'] = "Email no válido.";
		//die("Email no válido.");
		header("Location:modify_user.php");
		exit();
		
	    } elseif ($field == 'dni' && !preg_match('/^[0-9]{8}[A-Za-z]$/', $new_value)) {
		//die("DNI no válido.");
		$_SESSION['error_message'] = "DNI no válido.";
		header("Location:modify_user.php");
		exit();
	    } elseif ($field == 'fecha_nacimiento' && !DateTime::createFromFormat('Y-m-d', $new_value)) {
		//die("Fecha de nacimiento no válida.");
		$_SESSION['error_message'] = "DNI no válido.";
		header("Location:modify_user.php");
		exit();
	    }

	    // Obtener el ID del usuario
	    $sql = "SELECT id FROM usuarios WHERE email = ?";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("s", $user_email);
	    $stmt->execute();
	    $result = $stmt->get_result();

	    if ($result->num_rows === 0) {
		die("Usuario no encontrado.");
	    }

	    $user = $result->fetch_assoc();
	    $user_id = $user['id'];
	    $stmt->close();

	    // Actualizar el campo
	    $sql = "UPDATE usuarios SET $field = ? WHERE id = ?";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("si", $new_value, $user_id);

	    if ($stmt->execute()) {
		header("Location: modify_user.php?msg=success");
	    } else {
		header("Location: modify_user.php?msg=error&error=" . urlencode($stmt->error));
	    }

	    $stmt->close();
	    $conn->close();
	}
	?>

