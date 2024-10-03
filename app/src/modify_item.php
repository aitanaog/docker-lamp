<?php

echo '<head>';
  echo '  <link rel="stylesheet" href="../css/styles.css">';
    echo '</head>';
    
// Crear un menú de navegación
  echo '<nav>';
  echo '<ul style="list-style-type: none; padding: 0;">';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/register.php">Página de registro</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/login.php">Log in</a></li>'; 
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/items.php">Mostrar playlist</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/add_item.php">Añadir cancion</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/delete_item.php">Eliminar cancion</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/modify_item.php">Modificar cancion</a></li>';
  echo '</ul>';
  echo '</nav>';


// Mostrar mensajes de estado
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'success') {
        echo "<p style='color: green;'>Canción modificada con éxito.</p>";
    } elseif ($_GET['msg'] == 'error') {
        echo "<p style='color: red;'>Error al modificar la canción: " . htmlspecialchars($_GET['error']) . "</p>";
    } elseif ($_GET['msg'] == 'no_id') {
        echo "<p style='color: orange;'>No se recibió el ID de la canción.</p>";
    }
}

// Conexión a la base de datos
$hostname = "db"; // Cambia por tu hostname
$username = "admin"; // Cambia por tu username
$password = "test"; // Cambia por tu password
$db = "database"; // Cambia por tu database name

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    		die("Database connection failed: " . $conn->connect_error);
}

// Obtener la lista de canciones
$sql = "SELECT * FROM canciones";
$query = $conn->query($sql);

if ($query === false) {
    die("Error en la consulta: " . $conn->error);
}

// Contenedor principal
echo '<div class="container">';

// Contenedor de la tabla
echo '<div class="table-container">';
echo '<h2>Lista de Canciones</h2>';
echo '<table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre de la Canción</th>
            <th>Artista</th>
            <th>Acciones</th> <!-- Nueva columna para acciones -->
        </tr>';

// Iterar sobre los resultados
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
echo '</div>'; // Cerrar contenedor de la tabla

// Contenedor del formulario
echo '<div class="form-container">';

// Mostrar formulario de modificación si se ha seleccionado una canción
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar la canción seleccionada en la base de datos
    $sql = "SELECT * FROM canciones WHERE id = $id";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $selected_song = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Modificar Canción</title>
            <script src="validation.js" defer></script>
        </head>
        <body>
        <form id="modify_item" method="POST" action="process_modify_item.php">
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

echo '</div>'; // Cerrar contenedor del formulario
echo '</div>'; // Cerrar contenedor principal

$conn->close();
?>

$conn->close();
?>
