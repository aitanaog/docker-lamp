<?php
	session_start([
		'cookie_lifetime' => 86400,
		'cookie_httponly' => true,
		'cookie_secure' => true,
	]);

	// Restablecer CAPTCHA si es una sesión nueva o no está configurado
	if (!isset($_SESSION['mostrar_captcha'])) {
		$_SESSION['mostrar_captcha'] = true;
	}

	// Generar un token CSRF si no existe en la sesión
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	// Obtener el token CSRF para incluirlo en el formulario
	$csrf_token = $_SESSION['csrf_token'];
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
	    	<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
			<label for="email">Correo Electrónico:</label>
			<input type="email" id="email" name="email" required>
			<br>

			<label for="contrasenna">Contraseña:</label>
			<input type="password" id="contrasenna" name="contrasenna" required>
			<br>

        <!-- Mostrar CAPTCHA siempre en el primer intento al cargar la página -->
        <?php if ($_SESSION['mostrar_captcha']): ?>
            <div class="g-recaptcha" data-sitekey="6LcPen4qAAAAACFbC-izfYUSIAGsROZmClPTdifW"></div>
        <?php endif; ?>

        <button type="submit" id="login_submit">Iniciar Sesión</button>
    </form>
</body>
</html>
