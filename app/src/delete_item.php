<?php
	// Iniciar la sesión para acceder a $_SESSION
	session_start();

	// Generar un token CSRF si no existe en la sesión
	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio de 32 bytes
	}

	// Obtener el token CSRF para incluirlo en el formulario
	$csrf_token = $_SESSION['csrf_token'];

// Conexión a la base de datos
$hostname = "db"; 
$username = "admin"; 
$password = "sgssi_proyecto"; 
$db = "database"; 

$conn = mysqli_connect($hostname, $username, $password, $db);
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Mostrar mensajes de estado
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'success') {
        echo "<p style='color: green;'>Canción eliminada con éxito.</p>";
    } elseif ($_GET['msg'] == 'error') {
        echo "<p style='color: red;'>Error al eliminar la canción.</p>";
    } elseif ($_GET['msg'] == 'no_id') {
        echo "<p style='color: orange;'>Error al procesar la solicitud.</p>";
    }
}

// Consulta para obtener las canciones
$query = mysqli_query($conn, "SELECT * FROM canciones") or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Canción</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<nav>
    <ul>
        <?php if (isset($_SESSION['user_email'])): ?>
            <li><a href="logout.php">Cerrar Sesión</a></li>
            <li><a href="modify_user.php">Modificar perfil</a></li>
        <?php else: ?>
            <li><a href="register.php">Página de registro</a></li>
            <li><a href="login.php">Log in</a></li>
        <?php endif; ?>
        <li><a href="items.php">Mostrar playlist</a></li>
        <li><a href="add_item.php">Añadir canción</a></li>
        <li><a href="delete_item.php">Eliminar canción</a></li>
        <li><a href="modify_item.php">Modificar canción</a></li>
    </ul>
</nav>

<h2>Lista de Canciones</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre de la Canción</th>
        <th>Artista</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = mysqli_fetch_array($query)): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']); ?></td>
            <td><?= htmlspecialchars($row['nombre_cancion']); ?></td>
            <td><?= htmlspecialchars($row['cantante']); ?></td>
            <td>
                <!-- Formulario para eliminar una canción -->
                <form method="POST" action="process_delete_item.php" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta canción?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
	    		<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php mysqli_close($conn); ?>
</body>
</html>


