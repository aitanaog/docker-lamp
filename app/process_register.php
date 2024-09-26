<?php
// process_register.php

// Conectar a la base de datos
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
$apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
$dni = mysqli_real_escape_string($conn, $_POST['dni']);
$telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
$fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

// Verificar si el DNI ya existe
$dni_query = mysqli_query($conn, "SELECT * FROM usuarios WHERE dni='$dni'");
if (mysqli_num_rows($dni_query) > 0) {
    die("El DNI ya está registrado.");
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email)
        VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fecha_nacimiento', '$email')";

if (mysqli_query($conn, $sql)) {
    echo "Registro exitoso.";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Cerrar la conexión
mysqli_close($conn);
?>

