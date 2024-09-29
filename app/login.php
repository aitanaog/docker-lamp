<!DOCTYPE html>
<html lang="es">  
<head>
	
    <!--<meta charset="UTF-8">  									
    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="styles.css"> <!-- CSS externo -->

</head>
<body>
	<!-- Crear un menú de navegación -->
  	<nav>
  	<ul style="list-style-type: none; padding: 0;">
  	<li style="display: inline; margin-right: 15px;"><a href="index.php">Inicio</a></li>
  	<li style="display: inline; margin-right: 15px;"><a href="register.php">Registrarse</a></li>
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
        <label for="Contrasenna">Contraseña:</label>
        <input type="text" id="Contrasenna" name="Contrasenna" required>
        <span id="Contrasenna_error" class="error"></span>
        <br>

        <!-- Botón de envío -->
        <button type="submit" id="login_submit">login</button>
    </form>
</body>
</html>
