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


// Recoger los datos del formulario
$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
$cantante = mysqli_real_escape_string($conn, $_POST['cantante']);
$album = mysqli_real_escape_string($conn, $_POST['album']);
$genero = mysqli_real_escape_string($conn, $_POST['genero_musical']);
$fecha_lanzamiento = mysqli_real_escape_string($conn, $_POST['fecha_lanzamiento ']);


// Modifcar los datos en la base de datos
$sql = "UPDATE canciones SET nombre='$nombre', cantante='$cantante', album='$album', genero_musical='$genero', fecha_lanzamiento='$fecha_lanzamiento' WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    // Si se elimina con éxito, redirigir de vuelta
    header("Location: items.php?msg=success");
    exit(); // Asegúrate de usar exit después de header
} else {
    // En caso de error, redirigir de vuelta con un mensaje de error
    header("Location: items.php?msg=error");
    exit();
}


$conn->close();
?>


