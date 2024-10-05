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
    <script defer src="validation.js"></script> <!-- JavaScript externo -->
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
    <h2>Registro de Usuario</h2>

    <form id="register_form" action="process_register.php" method="POST">               
    
        <!-- Nombre -->
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ejemp. Juan" required>
        <span id="nombre_error" class="error"></span>
        <br>

        <!-- Apellidos -->
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" placeholder="Ejemp. Perez" required>
        <span id="apellidos_error" class="error"></span>
        <br>

        <!-- DNI -->
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" pattern="\d{8}-[A-Z]"placeholder="Ejemp. 16207982-Z" required>		<!--utiliza una expresión regular para exigir que el DNI siga el formato-->
        <span id="dni_error" class="error"></span>
        <br>

        <!-- Teléfono -->
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" pattern="\d{9}" placeholder="Ejemp. 666 55 44 33" required>
        <span id="telefono_error" class="error"></span>
        <br>

        <!-- Fecha de Nacimiento -->
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Ejemp. 1999-09-14" required>
        <span id="fecha_nacimiento_error" class="error"></span>
        <br>

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Ejemp. Juanperez@gmail.com" required>
        <span id="email_error" class="error"></span>
        <br>

	<!-- Contrasenna -->
        <label for="Contrasenna">Contraseña:</label>
        <input type="password" id="contrasenna" name="contrasenna" placeholder="********" required>    			
        <span id="Contrasenna_error" class="error"></span>
        <br>

    <!-- Contrasenna -->
        <label for="Contrasenna2">Repite contraseña:</label>
        <input type="password" id="contrasenna2" name="contrasenna2" placeholder="********" required>    			
        <span id="Contrasenna_error" class="error"></span>
        <br>


        <!-- Botón de envío -->
        <button type="submit" id="register_submit">Registrarse</button>
    </form>
</body>
</html>

