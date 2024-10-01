<?php
// Crear un menú de navegación
echo '<nav>';
echo '<ul style="list-style-type: none; padding: 0;">';
echo '<li style="display: inline; margin-right: 15px;"><a href="register.php">Página de registro</a></li>';
echo '<li style="display: inline; margin-right: 15px;"><a href="login.php">Log in</a></li>'; 
echo '<li style="display: inline; margin-right: 15px;"><a href="items.php">Mostrar playlist</a></li>';
echo '<li style="display: inline; margin-right: 15px;"><a href="add_item.php">Añadir canción</a></li>';
echo '<li style="display: inline; margin-right: 15px;"><a href="delete_item.php">Eliminar canción</a></li>';
echo '</ul>';
echo '</nav>';
// Conexión a la base de datos
$hostname = "db"; // Cambia por tu hostname
$username = "admin"; // Cambia por tu username
$password = "test"; // Cambia por tu password
$db = "database"; // Cambia por tu database name

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    		die("Database connection failed: " . $conn->connect_error);
}

// Obtener el ID del usuario registrado (asumiendo que está almacenado en la sesión)
$user_id = $_SESSION['user_id'];

// Obtener la información del usuario de la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("Usuario no encontrado.");
}

// Mostrar mensajes de estado
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'success') {
        echo "<p style='color: green;'>Datos del usuario modificados con éxito.</p>";
    } elseif ($_GET['msg'] == 'error') {
        echo "<p style='color: red;'>Error al modificar los datos del usuario: " . htmlspecialchars($_GET['error']) . "</p>";
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
    'email' => 'Email',
    'telefono' => 'Teléfono',
    'direccion' => 'Dirección'
];

foreach ($fields as $field => $label) {
    echo "<tr>
            <td>{$label}</td>
            <td id='{$field}_value'>{$user[$field]}</td>
            <td>
                <button onclick=\"showEditForm('{$field}', '{$user[$field]}')\">Editar</button>
            </td>
          </tr>";
}
echo '</table>';
echo '</div>'; // Cerrar contenedor de la tabla

// Contenedor del formulario
echo '<div class="form-container">';
echo '<h2>Modificar Datos del Usuario</h2>';
echo '<form id="edit_form" method="POST" action="process_modify_user.php" style="display: none;">
        <input type="hidden" name="field" id="field">
        <label id="field_label"></label>
        <input type="text" name="new_value" id="new_value" required>
        <input type="submit" value="Confirmar">
      </form>';
echo '</div>'; // Cerrar contenedor del formulario
echo '</div>'; // Cerrar contenedor principal

$conn->close();
?>

<script>
function showEditForm(field, value) {
    document.getElementById('edit_form').style.display = 'block';
    document.getElementById('field').value = field;
    document.getElementById('field_label').innerText = 'Nuevo ' + field.charAt(0).toUpperCase() + field.slice(1) + ':';
    document.getElementById('new_value').value = value;
}
</script>