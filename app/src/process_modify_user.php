<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
		die("Error al procesar la solicitud.");
	}
// Conexión a la base de datos
$hostname = "db"; 
$username = "admin"; 
$password = "sgssi_proyecto"; 
$db = "database"; 

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se haya recibido el ID
    if (isset($_POST["id"])) {
        // Validar y sanitizar el ID para asegurarse de que es un número entero
        $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
        if ($id === false) {
            header("Location: modify_user.php?msg=invalid_id");
            exit();
        }

        // Obtener el email del usuario de la sesión
        $user_email = $_SESSION['user_email'];
        
        // Validar y obtener los datos del formulario
        if (!isset($_POST['field']) || !isset($_POST['new_value'])) {
            die("Error al procesar la solicitud");
        }

        $field = $_POST['field'];
        $new_value = $_POST['new_value'];

        // Validar el campo
        $allowed_fields = ['nombre', 'apellidos', 'email', 'dni', 'telefono', 'fecha_nacimiento'];
        if (!in_array($field, $allowed_fields)) {
            die("Error al procesar la solicitud");
        }
        // Validar y sanitizar el valor de `new_value` según el tipo de campo
        if ($field == 'email' && !filter_var($new_value, FILTER_VALIDATE_EMAIL)) {
            die("Error al procesar la solicitud.");
        } elseif ($field == 'dni' && !preg_match('/^[0-9]{8}[A-Za-z]$/', $new_value)) {
            die("Error al procesar la solicitud.");
        } elseif ($field == 'fecha_nacimiento' && !DateTime::createFromFormat('Y-m-d', $new_value)) 		{
            die("Error al procesar la solicitud.");
        }
        // CONSULTA1: obtener el ID del usuario por su email
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al procesar la solicitud " . $conn->error);
        }

        // Vincular el parámetro y ejecutar la consulta
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        // Verificar si se encontró al usuario
        if ($result->num_rows === 0) {
            die("Error al procesar la solicitud");
        }

        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // CONSULTA2: Actualizar el campo específico usando un mapeo seguro
        $field_column_map = [
            'nombre' => 'nombre',
            'apellidos' => 'apellidos',
            'email' => 'email',
            'dni' => 'dni',
            'telefono' => 'telefono',
            'fecha_nacimiento' => 'fecha_nacimiento'
        ];

        $column = $field_column_map[$field];  // Determinar columna segura
        
        $sql = "UPDATE usuarios SET $column = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al procesar la solicitud" . $conn->error);
        }

        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("si", $new_value, $user_id);
        if ($stmt->execute()) {
            // Redirigir con un mensaje de éxito
            header("Location: modify_user.php?msg=success");
            exit();
        } else {
            // Redirigir con un mensaje de error
            header("Location: modify_user.php?msg=error&error=" . urlencode($stmt->error));
            exit();
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        // Si no se recibe el ID, redirigir con un mensaje de error
        header("Location: modify_user.php?msg=no_id");
        exit();
    }
}

$conn->close();
?>

