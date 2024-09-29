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
  	<li style="display: inline; margin-right: 15px;"><a href="login.php">Log in</a></li>
  	</ul>
  	</nav>
    <h2>Registro de Usuario</h2>
    <form id="register_form" action="process_register.php" method="POST">               
    
        <!-- Nombre -->
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>    			<!-- required: El campo es obligatorio; el formulario no se puede enviar si está vacío.-->
        <span id="nombre_error" class="error"></span>
        <br>

        <!-- Apellidos -->
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>
        <span id="apellidos_error" class="error"></span>
        <br>

        <!-- DNI -->
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" pattern="\d{8}-[A-Z]" required>		<!--utiliza una expresión regular para exigir que el DNI siga el formato-->
        <span id="dni_error" class="error"></span>
        <br>

        <!-- Teléfono -->
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" pattern="\d{9}" required>
        <span id="telefono_error" class="error"></span>
        <br>

        <!-- Fecha de Nacimiento -->
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
        <span id="fecha_nacimiento_error" class="error"></span>
        <br>

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <span id="email_error" class="error"></span>
        <br>

	<!-- Contrasenna -->
        <label for="Contrasenna">Contraseña:</label>
        <input type="text" id="contrasenna" name="Contrasenna" placeholder="********" required>    			
        <span id="Contrasenna_error" class="error"></span>
        <br>

	<!-- Repetir Contrasenna -->
        <label for="Contrasenna2">Repetir Contraseña:</label>
        <input type="text" id="contrasenna2" name="Contrasenna2" placeholder="********" required>    			
        <span id="Contrasenna2_error" class="error"></span>
        <br>

        <!-- Botón de envío -->
        <button type="submit" id="register_submit">Registrarse</button>
    </form>
</body>
</html>

