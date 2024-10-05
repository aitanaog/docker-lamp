<?php
session_start();

// Conexión a la base de datos
$hostname = "db"; // Cambia por tu hostname
$username = "admin"; // Cambia por tu username
$password = "test"; // Cambia por tu password
$db = "database"; // Cambia por tu database name

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el ID del usuario está en la sesión
    if (!isset($_SESSION['user_email'])) {
        die("No se ha encontrado la sesión del usuario.");
    }
    
    // Obtener el email del usuario de la sesión
    $user_email = $_SESSION['user_email'];
    
    // Obtener los datos del formulario
    if (!isset($_POST['field']) || !isset($_POST['new_value'])) {
        die("Datos del formulario no recibidos.");
    }
    
    $field = $_POST['field'];
    $new_value = $_POST['new_value'];
    
    // Validar el campo
    $allowed_fields = ['nombre', 'apellidos', 'email', 'dni', 'telefono', 'fecha_nacimiento'];
    if (!in_array($field, $allowed_fields)) {
        die("Campo no permitido.");
    }

    // Preparar la consulta SQL para obtener el ID del usuario
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

    // Preparar la consulta SQL para actualizar el campo
    $sql = "UPDATE usuarios SET $field = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los parámetros (suponiendo que el nuevo valor es una cadena)
    $stmt->bind_param("si", $new_value, $user_id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir de vuelta con un mensaje de éxito
        header("Location: modify_user.php?msg=success");
        exit();
    } else {
        // Redirigir de vuelta con un mensaje de error
        header("Location: modify_user.php?msg=error&error=" . urlencode($stmt->error));
        exit();
    }

    // Cerrar la declaración
    $stmt->close();
}

$conn->close();
?>

