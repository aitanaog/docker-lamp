<!DOCTYPE html>
<html lang="es">  
<head>
	
    <!--<meta charset="UTF-8">  									
    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css"> <!-- CSS externo -->
    <script defer src="validation.js"></script> <!-- JavaScript externo -->
</head>
<body>
	<!-- Crear un menú de navegación -->
  	<nav>
  	<ul style="list-style-type: none; padding: 0;">
  	<li style="display: inline; margin-right: 15px;"><a href="index.php">Inicio</a></li>
  	<li style="display: inline; margin-right: 15px;"><a href="register.php">Registrarsein</a></li>
  	</ul>
  	</nav>
    <h2>Inicio de Sesión</h2>
    <form id="login_form" action="login_register.php" method="POST">               <!--contenedor que agrupa todos los campos de entrada (inputs) del formulario. Cuando el formulario es enviado, los datos se enviarán al archivo PHP process_register.php para ser procesados, El método POST es utilizado para enviar los datos de forma segura al servidor. A diferencia del método GET, los datos no se ven en la URL.-->
        <!-- Nombre de Usuario -->
        <label for="nombreUsuario">NombreUsuario:</label>
        <input type="text" id="nombreUsuario" name="nombreUsuario" required>    			<!-- required: El campo es obligatorio; el formulario no se puede enviar si está vacío.-->
        <span id="nombreUsuario_error" class="error"></span>
        <br>

        <!-- Contraseña -->
        <label for="Contrasenna">Contrasenna:</label>
        <input type="text" id="Contrasenna" name="Contrasenna" required>
        <span id="Contrasenna_error" class="error"></span>
        <br>

        <!-- Botón de envío -->
        <button type="submit" id="login_submit">login</button>
    </form>
</body>
</html>
