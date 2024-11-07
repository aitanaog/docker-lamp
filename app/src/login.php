<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">  									
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- Script de reCAPTCHA -->
</head>
<body>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

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
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="contrasenna">Contraseña:</label>
        <input type="password" id="contrasenna" name="contrasenna" required>
        <br>

        <!-- CAPTCHA Condicional -->
        <?php if (isset($_SESSION['mostrar_captcha']) && $_SESSION['mostrar_captcha']): ?>
            <div class="g-recaptcha" data-sitekey="TU_SITE_KEY"></div>
        <?php endif; ?>

        <button type="submit" id="login_submit">Iniciar Sesión</button>
    </form>
</body>
</html>

