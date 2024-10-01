<?php
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
    // Obtener el ID del usuario registrado (asumiendo que está almacenado en la sesión)
    $user_id = $_SESSION['user_id'];
    $field = $_POST['field'];
    $new_value = $_POST['new_value'];

    // Validar el campo
    $allowed_fields = ['nombre', 'email', 'telefono', 'direccion'];
    if (!in_array($field, $allowed_fields)) {
        die("Campo no permitido.");
    }

    // Preparar la consulta SQL
    $sql = "UPDATE usuarios SET $field = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("si", $new_value, $user_id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si se cambia con éxito, redirigir de vuelta
        header("Location: modify_user.php?msg=success");
        exit(); // Asegúrate de usar exit después de header
    } else {
        // En caso de error, redirigir de vuelta con un mensaje de error
        header("Location: modify_user.php?msg=error&error=" . urlencode($stmt->error));
        exit();
    }

    // Cerrar la declaración
    $stmt->close();
}

$conn->close();
?>