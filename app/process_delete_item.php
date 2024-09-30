<?php
// Conexión a la base de datos
$hostname = "db";  // Cambia si es necesario
$username = "admin";  // Cambia si es necesario
$password = "test";  // Cambia si es necesario
$db = "database";  // Cambia si es necesario

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Verificar si se ha recibido el ID de la canción a eliminar
if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']); // Escapar el ID para evitar inyecciones SQL

    // Consulta para eliminar la canción
    $query = "DELETE FROM canciones WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        // Si se elimina con éxito, redirigir de vuelta
        header("Location: items.php?msg=success");
        exit(); // Asegúrate de usar exit después de header
    } else {
        // En caso de error, redirigir de vuelta con un mensaje de error
        header("Location: items.php?msg=error");
        exit();
    }
} else {
    // Redirigir de vuelta si no se recibió el ID
    header("Location: items.php?msg=no_id");
    exit();
}

// Cerrar la conexión
mysqli_close($conn);
?>

