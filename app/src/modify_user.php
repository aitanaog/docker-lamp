<?php
	session_start([
	    'cookie_lifetime' => 86400,
	    'cookie_httponly' => true,
	    'cookie_secure' => true,
	]);

		// Generar un token CSRF si no existe en la sesión
		if (empty($_SESSION['csrf_token'])) {
		    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		// Obtener el token CSRF para incluirlo en el formulario
		$csrf_token = $_SESSION['csrf_token'];
		
	// Mostrar el mensaje de error si existe
if (isset($_SESSION['error_message'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
    unset($_SESSION['error_message']); // Eliminar el mensaje después de mostrarlo
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

	// Obtener el email del usuario desde la sesión
	if (!isset($_SESSION['email'])) {
	    die("Sesión expirada. Por favor, inicia sesión nuevamente.");
	}
	$user_email = $_SESSION['email'];

	// CONSULTA 1: Obtener la información del usuario
	$sql = "SELECT * FROM usuarios WHERE email = ?";
	$stmt = $conn->prepare($sql);
	if ($stmt === false) {
	    die("Error al preparar la consulta.");
	}

	$stmt->bind_param("s", $user_email);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 0) {
	    die("Usuario no encontrado.");
	}

	$user = $result->fetch_assoc();
	$stmt->close();

	// Función para descifrar los datos
	function decryptData($ciphertext)
	{
	    $key = 'clave_secreta_de_32_bytes__';
	    $data = base64_decode($ciphertext);

	    $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
	    $encryptedData = substr($data, openssl_cipher_iv_length('aes-256-cbc'));

	    return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
	}

	// Descifrar valores sensibles
	$decryptedTelefono = decryptData($user['telefono']);
	$decryptedDni = decryptData($user['dni']);

	?>

	<!DOCTYPE html>
	<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Modificar Usuario</title>
	    <link rel="stylesheet" href="../css/styles.css">
	    <script defer src="validation.js"></script>
	    <style>
		.container { display: flex; }
		.table-container { flex: 1; }
		.form-container { flex: 1; padding-left: 20px; }
	    </style>
	</head>
	<body>
	    <nav>
		<ul>
		    <li><a href="/src/items.php">Mostrar playlist</a></li>
		    <li><a href="/src/add_item.php">Añadir canción</a></li>
		    <li><a href="/src/delete_item.php">Eliminar canción</a></li>
		    <li><a href="/src/modify_item.php">Modificar canción</a></li>
		    <li><a href="/src/logout.php">Cerrar sesión</a></li>
		</ul>
	    </nav>

	    <div class="container">
		<!-- Tabla de datos -->
		<div class="table-container">
		    <h2>Datos del Usuario</h2>
		    <table border="1">
		        <tr>
		            <th>Campo</th>
		            <th>Valor</th>
		            <th>Acciones</th>
		        </tr>
		        <?php
		        $fields = [
		            'nombre' => 'Nombre',
		            'apellidos' => 'Apellidos',
		            'email' => 'Email',
		            'dni' => 'DNI',
		            'telefono' => 'Teléfono',
		            'fecha_nacimiento' => 'Fecha de Nacimiento'
		        ];

		        foreach ($fields as $field => $label) {
		            $value = ($field == 'telefono') ? $decryptedTelefono : ($field == 'dni' ? $decryptedDni : $user[$field]);
		            echo "<tr>
		                <td>{$label}</td>
		                <td id='{$field}_value'>{$value}</td>
		                <td><button onclick=\"showEditForm('{$field}', '{$value}')\">Editar</button></td>
		            </tr>";
		        }
		        ?>
		    </table>
		</div>

		<!-- Formulario de edición -->
		<div class="form-container">
		    <h2>Modificar Datos del Usuario</h2>
		    <form id="user_modify_form" method="POST" action="process_modify_user.php" style="display: none;">
		    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
		        <input type="hidden" name="field" id="field">
		        <label id="field_label"></label>
		        <input type="text" name="new_value" id="new_value" required>
		        <button type="submit">Confirmar</button>
		    </form>
		</div>
	    </div>

	    <script>
		function showEditForm(field, value) {
		    document.getElementById('user_modify_form').style.display = 'block';
		    document.getElementById('field').value = field;
		    document.getElementById('field_label').innerText = 'Nuevo ' + field.charAt(0).toUpperCase() + field.slice(1) + ':';
		    document.getElementById('new_value').value = value;
		}
	    </script>
	</body>
	</html>

