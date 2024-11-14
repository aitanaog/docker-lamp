<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificación del token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error al procesar la solicitud.");
    }

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

    // Verificar si las contraseñas coinciden
    if ($contrasenna !== $contrasenna2) {
        $_SESSION['error_message'] = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        header("Location: register.php");
        exit();
    }

    // Verificar si la contraseña cumple los requisitos de seguridad
    $regex_contrasenna = "/^(?=.*[A-Z])(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_]).{8,}$/";
    if (!preg_match($regex_contrasenna, $contrasenna)) {
        $_SESSION['error_message'] = "La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un símbolo.";
        header("Location: register.php");
        exit();
    }

    // Verificar si el email ya existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Este email ya está registrado. Por favor, elige otro.";
        header("Location: register.php");
        exit();
    }

    // Generar una salt única y un hash de la contraseña usando la salt
    $salt = bin2hex(random_bytes(16));
    $hash_contrasenna = hash('sha256', $contrasenna . $salt);

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, semilla, contrasenna) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("ssssssss", $nombre, $apellidos, $dni, $telefono, $fecha_nacimiento, $email, $salt, $hash_contrasenna);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Se ha registrado correctamente.";
        header("Location: register.php");
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_close($conn);
}
?>
