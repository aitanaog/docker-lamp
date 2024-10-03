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
    // Verificar que se haya recibido el ID
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        
        // Recoger los datos del formulario
        $nombre = $_POST['nombre_cancion'];
        $cantante = $_POST['cantante'];
        $album = $_POST['album'];
        $genero = $_POST['genero'];
        $fecha_lanzamiento = $_POST['fecha_lanzamiento'];

        // Preparar la consulta SQL
        $sql = "UPDATE canciones SET nombre_cancion=?, cantante=?, album=?, genero_musical=?, fecha_lanzamiento=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        // Vincular los parámetros
        $stmt->bind_param("sssssi", $nombre, $cantante, $album, $genero, $fecha_lanzamiento, $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si se cambia con éxito, redirigir de vuelta
            header("Location: modify_item.php?msg=success");
            exit(); // Asegúrate de usar exit después de header
        } else {
            // En caso de error, redirigir de vuelta con un mensaje de error
            header("Location: modify_item.php?msg=error");
            exit();
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        // Si no se recibe el ID, redirigir con un mensaje de error
        header("Location: modify_item.php?msg=no_id");
        exit();
    }
}

$conn->close();


?>
