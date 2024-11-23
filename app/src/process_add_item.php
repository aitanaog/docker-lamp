<?php
// Iniciar la sesión
session_start();

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error al procesar la solicitud");
    }

    // Conectar a la base de datos
    $hostname = "db";
    $username = "admin";
    $password = "sgssi_proyecto";
    $db = "database";

    $conn = new mysqli($hostname, $username, $password, $db);

    if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    // Recoger y sanitizar los datos del formulario
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $cantante = htmlspecialchars(strip_tags($_POST['cantante']));
    $album = htmlspecialchars(strip_tags($_POST['album']));
    $genero = htmlspecialchars(strip_tags($_POST['genero']));
    $fecha_lanzamiento = htmlspecialchars(strip_tags($_POST['fecha_lanzamiento']));

    // Verificar si la canción ya existe
    $sql = "SELECT * FROM canciones WHERE nombre_cancion = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
 		    // Registrar el error para revisión interna
		    error_log("Error al procesar la solicitud: " . $conn->error);

		    // Mostrar mensaje genérico al usuario
		    die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
    }

    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Error al procesar la solicitud.";
        header("Location: add_item.php");
        exit();
    }
    $stmt->close();

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO canciones (nombre_cancion, cantante, genero_musical, album, fecha_lanzamiento) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
		    // Registrar el error para revisión interna
		    error_log("Error al procesar la solicitud: " . $conn->error);

		    // Mostrar mensaje genérico al usuario
		    die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
    }

    $stmt->bind_param("sssss", $nombre, $cantante, $genero, $album, $fecha_lanzamiento);

    if ($stmt->execute()) {
        $_SESSION['error_message'] = "Canción añadida correctamente.";
    } else {
        $_SESSION['error_message'] = "Error al procesar la solicitud";
    }

    // Cerrar las conexiones y redirigir
    $stmt->close();
    $conn->close();
    header("Location: add_item.php");
    exit();
}
?>


