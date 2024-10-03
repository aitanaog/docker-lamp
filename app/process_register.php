<?php
session_start();

// Conectar a la base de datos
$hostname = "db";  // Asegúrate de que todos usan el mismo hostname
$username = "admin";  // El mismo username para todos
$password = "test";  // La misma contraseña para todos
$db = "database";  // El mismo nombre de base de datos para todos

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}

// Recoger los datos del formulario
$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
$apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
$dni = mysqli_real_escape_string($conn, $_POST['dni']);
$telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
$fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$contrasenna = mysqli_real_escape_string($conn, $_POST['contrasenna']);

// Generar hash de la contraseña
$hashed_password = password_hash($contrasenna, PASSWORD_BCRYPT);

// Verificar si el email ya existe
$email_query = "SELECT * FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conn, $email_query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Guardar el mensaje de error en una variable de sesión
    $_SESSION['error_message'] = "Este email ya está registrado. Por favor, elige otro.";
    header("Location: register.php");
    exit();
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, contrasenna)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "sssssss", $nombre, $apellidos, $dni, $telefono, $fecha_nacimiento, $email, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    echo "Registro exitoso.";
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Cerrar la conexión
mysqli_close($conn);
?>


