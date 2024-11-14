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

        // Validar credenciales
        if ($contrasenna === $contrasenna_almacenada) {
            // Verificar si se ha completado el CAPTCHA
            if (!isset($_POST['g-recaptcha-response']) || !validarCaptcha($_POST['g-recaptcha-response'])) {
                $_SESSION['mostrar_captcha'] = true; // Mostrar CAPTCHA en caso de fallo
                $_SESSION['error_message'] = "Por favor, marque que no es un robot.";
                header("Location: login.php");
                exit();
            }

            // Credenciales correctas y CAPTCHA validado
            $_SESSION['user_email'] = $email;
            $_SESSION['id'] = $id_usuario;
            unset($_SESSION['mostrar_captcha']); // Desactivar CAPTCHA
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Credenciales incorrectas.";
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
    $secret_key = "6LcPen4qAAAAAJN8fXyfrXjrgBj7KAX7F5KXw0gN";
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
    return json_decode($verify)->success;
}
