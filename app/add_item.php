</head>
<body>
	<!-- Crear un menú de navegación -->
  	<nav>
  	<ul style="list-style-type: none; padding: 0;">
  	<li style="display: inline; margin-right: 15px;"><a href="index.php">Inicio</a></li>
  	<li style="display: inline; margin-right: 15px;"><a href="login.php">Log in</a></li>
  	</ul>
  	</nav>
    <h2>Añadir Canción</h2>
    <form id="item_add_form" action="process_add_item.php" method="POST">      
             
        <!-- Nombre -->
        <label for="nombre">Nombre de la canción:</label>
        <input type="text" id="nombre" name="nombre" required>    			
        <span id="nombre_error" class="error"></span>
        <br>

        <!-- Cantante -->
        <label for="cantante">Cantante:</label>
        <input type="text" id="cantante" name="cantante" required>
        <span id="cantante_error" class="error"></span>
        <br>
        
        <!-- album -->
        <label for="album">album:</label>
        <input type="text" id="album" name="album" required>    			
        <span id="album_error" class="error"></span>
        <br>

        <!--Genero-->
        <label for="genero">Genero musical:</label>
        <input type="text" id="genero" name="genero"  required>		
        <span id="genero_error" class="error"></span>
        <br>

        <!-- Fecha lanzamiento -->
        <label for="fecha_lanzamiento">Fecha de lanzamiento:</label>
        <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" required>
        <span id="fecha_lanzamiento_error" class="error"></span>
        <br>



        <!-- Botón de envío -->
        <button type="submit" id="item_add_submit">Añadir canción</button>
    </form>
</body>
</html>
