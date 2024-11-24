<?php
	session_start();
	// Generar un token CSRF si no existe en la sesión
	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio de 32 bytes
	}

	// Obtener el token CSRF para incluirlo en el formulario
	$csrf_token = $_SESSION['csrf_token'];

	echo '<head>';
  	echo' <meta charset="UTF-8"> '; 									
    echo'<meta name="viewport" content="width=device-width, initial-scale=1.0">';
   	echo' <title>localhost:81/modify_user?</title>';
  	echo '<script defer src="validation.js"></script> <!-- JavaScript externo -->';
  	echo '  <link rel="stylesheet" href="../css/styles.css">';
    echo '</head>';
	// Iniciar la sesión
	
	// Crear un menú de navegación
	  echo '<nav>';
	  echo '<ul style="list-style-type: none; padding: 0;">';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/items.php">Mostrar playlist</a></li>';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/add_item.php">Añadir cancion</a></li>';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/delete_item.php">Eliminar cancion</a></li>';
	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/modify_item.php">Modificar cancion</a></li>';

	  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/logout.php">Cerrar sesión</a></li>';

	  echo '</ul>';
	  echo '</nav>';

	
	// Conexión a la base de datos
	$hostname = "db"; // Cambia por tu hostname
	$username = "admin"; // Cambia por tu username
	$password = "sgssi_proyecto"; // Cambia por tu password
	$db = "database"; // Cambia por tu database name

	$conn = mysqli_connect($hostname, $username, $password, $db);
	if ($conn->connect_error) {
    		die("Database connection failed: " . $conn->connect_error);
	}

	
	// Obtener el ID del usuario registrado (asumiendo que está almacenado en la sesión)
	$user_email = $_SESSION['email'];

	// Preparar la consulta SQL usando sentencias preparadas
	// CONSULTA 1: Obtener la información del usuario de la base de datos
	$sql = "SELECT * FROM usuarios WHERE email = ?";
	$stmt = $conn->prepare($sql);
	if ($stmt === false) {
	    die("Error al procesar la solicitud.");
	}
	   
	// Vincular los parámetros  
	$stmt->bind_param("s", $user_email);
	
	// Ejecutar la consulta
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
   		 $user = $result->fetch_assoc();
	} else {
   		 die("Usuario no encontrado.");
	}
       
        
        // Cerrar la declaración
       	$stmt->close();
        
        // Función para descifrar los datos
        function decryptData($ciphertext) {
            $key = 'clave_secreta_de_32_bytes__'; // Asegúrate de usar la misma clave que usaste para cifrar
            $data = base64_decode($ciphertext);

            $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
            $encryptedData = substr($data, openssl_cipher_iv_length('aes-256-cbc'));

            return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
        }

        // Descifrar los valores de telefono y dni
        $decryptedTelefono = decryptData($user['telefono']);
        $decryptedDni = decryptData($user['dni']);
        
	// Mostrar mensajes de estado
	if (isset($_GET['msg'])) {
    		if ($_GET['msg'] == 'success') {
       			 echo "<p style='color: green;'>Datos del usuario modificados con éxito.</p>";
    		} elseif ($_GET['msg'] == 'error') {
       			 echo "<p style='color: red;'>Error al procesar la solicitud." . htmlspecialchars($_GET['error']) . "</p>";
   		 }
	}

	// Estilos CSS para el diseño de dos columnas
	echo '<style>
	.container {
    	display: flex;
	}
	.table-container {
    	flex: 1;
	}
	.form-container {
    	flex: 1;
    	padding-left: 20px;
	}
	</style>';

	// Contenedor principal
	echo '<div class="container">';

	// Contenedor de la tabla
	echo '<div class="table-container">';
	echo '<h2>Datos del Usuario</h2>';
	echo '<table border="1">
        <tr>
            <th>Campo</th>
            <th>Valor</th>
            <th>Acciones</th>
        </tr>';

	$fields = [
    		'nombre' => 'Nombre',
			'apellidos' => 'Apellidos',
    		'email' => 'Email',
    		'dni' => 'Dni',
			'telefono' => 'Telefono',
			'fecha_nacimiento' => 'Fecha_Nacimiento',
			'email' => 'Email'
			
	];

	foreach ($fields as $field => $label) {
   	 // Usamos los valores descifrados para dni y telefono
   	 $value = ($field == 'telefono') ? $decryptedTelefono : ($field == 'dni' ? $decryptedDni : $user[$field]);
   	 echo "<tr>
            	<td>{$label}</td>
            	<td id='{$field}_value'>{$value}</td>
            	<td>
                	<button onclick=\"showEditForm('{$field}', '{$value}')\">Editar</button>
           	 </td>
          </tr>";
	}
	echo '</table>';
echo '</div>'; // Cerrar contenedor de la tabla

	// Contenedor del formulario
	echo '<div class="form-container">';
	echo '<h2>Modificar Datos del Usuario</h2>';
	echo '<form id="user_modify_form" method="POST" action="process_modify_user.php" style="display: none;">
		<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        	<input type="hidden" name="field" id="field">
        	<label id="field_label"></label>
        	<input type="text" name="new_value" id="new_value" required>
        	
        	<button type="submit" id="user_modify_submit">Confirmar</button>
      		</form>';
echo '</div>'; // Cerrar contenedor del formulario
echo '</div>'; // Cerrar contenedor principal

$conn->close();
?>

<script>
function showEditForm(field, value) {
    document.getElementById('user_modify_form').style.display = 'block';
    document.getElementById('field').value = field;
    document.getElementById('field_label').innerText = 'Nuevo ' + field.charAt(0).toUpperCase() + field.slice(1) + ':';
    document.getElementById('new_value').value = value;
}
</script>
<script src="path/to/validation.js"></script> <!-- Asegúrate de incluir el archivo de validación -->
