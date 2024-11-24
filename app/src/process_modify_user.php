<?php
session_start([
	'cookie_lifetime' => 86400,
	'cookie_httponly' => true,
	'cookie_secure' => true,
]);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error al procesar la solicitud. TOKEN");
    }
    
    unset($_SESSION['csrf_token']);

    // Conexión a la base de datos
    $hostname = "db"; 
    $username = "admin"; 
    $password = "sgssi_proyecto"; 
    $db = "database"; 

    $conn = mysqli_connect($hostname, $username, $password, $db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Verificar que se haya recibido el ID
    if (isset($_POST["id"])) {
        // Validar y sanitizar el ID para asegurarse de que es un número entero
        $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
        if ($id === false) {
            header("Location: modify_user.php?msg=invalid_id");
            exit();
        }

        // Obtener el email del usuario de la sesión
        $user_email = $_SESSION['email'];

        // Validar y obtener los datos del formulario
        if (!isset($_POST['field']) || !isset($_POST['new_value'])) {
            die("Error al procesar la solicitud. Vuelva atras");
        }

        $field = $_POST['field'];
        $new_value = $_POST['new_value'];

        // Validar el campo
        $allowed_fields = ['nombre', 'apellidos', 'email', 'dni', 'telefono', 'fecha_nacimiento'];
        if (!in_array($field, $allowed_fields)) {
            die("Error al procesar la solicitud. Campo no válido. Vuelva atras");
        }

        // Validar y sanitizar el valor de new_value según el tipo de campo
        if ($field == 'email' && !filter_var($new_value, FILTER_VALIDATE_EMAIL)) {
            die("Error al procesar la solicitud. Email no válido. Vuelva atras");
        } elseif ($field == 'dni' && !preg_match('/^[0-9]{8}[A-Za-z]$/', $new_value)) {
            die("Error al procesar la solicitud. DNI no válido. Vuelva atras");
        } elseif ($field == 'fecha_nacimiento' && !DateTime::createFromFormat('Y-m-d', $new_value)) {
            die("Error al procesar la solicitud. Fecha no válida. Vuelva atras");
        }

        // CONSULTA1: obtener el ID del usuario por su email
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al procesar la solicitud: " . $conn->error);
            die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
        }

        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // Verificar si se encontró al usuario
        if ($result->num_rows === 0) {
            die("Error al procesar la solicitud. Usuario no encontrado. Vuelva atras");
        }

        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Mapear los campos a las columnas correspondientes
        $field_column_map = [
            'nombre' => 'nombre',
            'apellidos' => 'apellidos',
            'email' => 'email',
            'dni' => 'dni',
            'telefono' => 'telefono',
            'fecha_nacimiento' => 'fecha_nacimiento'
        ];

        $column = $field_column_map[$field];  // Determinar columna segura
        
        // Dependiendo del tipo de campo, elegimos el tipo adecuado para bind_param
        switch ($field) {
            case 'nombre':
            case 'apellidos':
            case 'email':
            case 'telefono':
                $type = "s";  // string
                break;
            case 'dni':
                $type = "s";  // string, porque el DNI puede contener una letra
                break;
            case 'fecha_nacimiento':
                $type = "s";  // string, ya que la fecha será en formato 'Y-m-d'
                break;
            default:
                die("Error al procesar la solicitud. Páramtero. Vuelva atras");
        }

        // CONSULTA2: Actualizar el campo específico
        $sql = "UPDATE usuarios SET $column = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al procesar la solicitud: " . $conn->error);
            die("Error al procesar la solicitud. Campo no actualizado. Por favor, vuelva atras.");
        }

        // Vincular los parámetros
        $stmt->bind_param($type . "i", $new_value, $user_id);
        if ($stmt->execute()) {
            header("Location: modify_user.php?msg=success");
            exit();
        } else {
            header("Location: modify_user.php?msg=error&error=" . urlencode($stmt->error));
            exit();
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        header("Location: modify_user.php?msg=no_id");
        exit();
    }

    $conn->close();
}
?>
