<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="es">  
<head>
    <meta charset="UTF-8">  									
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- CSS externo -->
</head>
<body>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']); // Eliminar el mensaje de la sesión después de mostrarlo
    }	
    ?>
	<!-- Crear un menú de navegación -->
  	<nav>
        <ul style="list-style-type: none; padding: 0;">
        <li style="display: inline; margin-right: 15px;"><a href="register.php">Página de registro</a></li>
        <li style="display: inline; margin-right: 15px;"><a href="login.php">Log in</a></li>
        <li style="display: inline; margin-right: 15px;"><a href="items.php">Mostrar playlist</a></li>
        <li style="display: inline; margin-right: 15px;"><a href="add_item.php">Añadir canción</a></li>
        <li style="display: inline; margin-right: 15px;"><a href="delete_item.php">Eliminar canción</a></li>
        <li style="display: inline; margin-right: 15px;"><a href="modify_item.php">Modificar canción</a></li>
        </ul>
        </nav>
    <h2>Inicio de Sesión</h2>
    <form id="login_form" action="process_login.php" method="POST">              
        <!-- Nombre de Usuario -->
        <label for="email">email:</label>
        <input type="text" id="email" name="email" required>    			<!-- required: El campo es obligatorio; el formulario no se puede enviar si está vacío.-->
        <span id="email_error" class="error"></span>
        <br>

        <!-- Contraseña -->
        <label for="contrasenna">Contraseña:</label>
        <input type="password" id="contrasenna" name="contrasenna" required>
        <span id="Contrasenna_error" class="error"></span>
        <br>

        <!-- Botón de envío -->
        <button type="submit" id="login_submit">login</button>
    </form>
</body>
</html>
