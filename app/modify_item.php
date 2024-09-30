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
$hostname = "db";  // Cambia si es necesario
$username = "admin";  // Cambia si es necesario
$password = "test";  // Cambia si es necesario
$db = "database";  // Cambia si es necesario

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Mostrar mensajes de estado
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'success') {
        echo "<p style='color: green;'>Canción modificada con éxito.</p>";
    } elseif ($_GET['msg'] == 'error') {
        echo "<p style='color: red;'>Error al modificar la canción.</p>";
    } elseif ($_GET['msg'] == 'no_id') {
        echo "<p style='color: orange;'>No se recibió el ID de la canción.</p>";
    }
}

// Consulta para obtener las canciones
$query = mysqli_query($conn, "SELECT * FROM canciones") or die (mysqli_error($conn));


// Mostrar los resultados en una tabla
echo '<h2>Lista de Canciones</h2>';
echo '<table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre de la Canción</th>
            <th>Artista</th>
            <th>Acciones</th> <!-- Nueva columna para acciones -->
        </tr>';


/// Iterar sobre los resultados
while ($row = $query->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nombre_cancion']}</td>
            <td>{$row['cantante']}</td>
            <td>
                <form method='GET' action='modify_item.php'>
                    <input type='hidden' name='id' value='{$row['id']}'> <!-- ID de la canción -->
                    <input type='submit' value='Modificar'>
                </form>
            </td>
          </tr>";
}
echo '</table>';


// Mostrar formulario de modificación si se ha seleccionado una canción
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    

    // Buscar la canción seleccionada en la base de datos
    $sql = "SELECT * FROM canciones WHERE id = $id";
    $selected_song = $conn->query($sql);

    if ($selected_song === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($selected_song->num_rows > 0) {
        $selected_song = $selected_song->fetch_assoc();
        ?>
        <h2>Modificar Canción</h2>
        <form method="POST" action="modify_item.php">
            <input type="hidden" name="id" value="<?php echo $selected_song['id']; ?>">
            <label for="nombre_cancion">Nombre de la Canción:</label>
            <input type="text" id="nombre_cancion" name="nombre_cancion" value="<?php echo $selected_song['nombre_cancion']; ?>" required>
            <span>(Anterior: <?php echo $selected_song['nombre_cancion']; ?>)</span>
            <br>
            <label for="cantante">Artista:</label>
            <input type="text" id="cantante" name="cantante" value="<?php echo $selected_song['cantante']; ?>" required>
            <span>(Anterior: <?php echo $selected_song['cantante']; ?>)</span>
            <br>
            <label for="fecha_lanzamiento">Fecha de Lanzamiento:</label>
            <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" value="<?php echo $selected_song['fecha_lanzamiento']; ?>" required>
            <span>(Anterior: <?php echo $selected_song['fecha_lanzamiento']; ?>)</span>
            <br>
            <label for="genero">Genero:</label>
            <input type="text" id="genero_musical" name="genero" value="<?php echo $selected_song['genero_musical']; ?>" required>
            <span>(Anterior: <?php echo $selected_song['genero_musical']; ?>)</span>
            <br>
            <label for="album">Álbum:</label>
            <input type="text" id="album" name="album" value="<?php echo $selected_song['album']; ?>" required>
            <span>(Anterior: <?php echo $selected_song['album']; ?>)</span>
            <br>
            <input type="submit" value="Modificar">
        </form>
        <?php
    } else {
        echo "<p style='color: red;'>Canción no encontrada.</p>";
    }
}

// Procesar la modificación de la canción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombre_cancion = $_POST["nombre_cancion"];
    $cantante = $_POST["cantante"];
    $fecha_lanzamiento = $_POST["fecha_lanzamiento"];
    $estilo = $_POST["estilo"];
    $album = $_POST["album"];

    // Aquí deberías actualizar la base de datos con los nuevos datos
    // Por ejemplo:
    // $sql = "UPDATE canciones SET nombre_cancion='$nombre_cancion', cantante='$cantante', fecha_lanzamiento='$fecha_lanzamiento', estilo='$estilo', album='$album' WHERE id=$id";
    // if ($conn->query($sql) === TRUE) {
    //     header("Location: modify_item.php?msg=success");
    // } else {
    //     header("Location: modify_item.php?msg=error");
    // }

    // Simulación de actualización exitosa
    echo "<script>
        if (confirm('¿Está seguro de que desea realizar estos cambios?')) {
            window.location.href = 'modify_item.php?msg=success';
        } else {
            window.location.href = 'modify_item.php?msg=error';
        }
    </script>";
}
?>