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


// Conexión a la base de datos
$hostname = "db";  // Cambia si es necesario
$username = "admin";  // Cambia si es necesario
$password = "test";  // Cambia si es necesario
$db = "database";  // Cambia si es necesario

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
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
        </tr>';

// Iterar sobre los resultados
while ($row = mysqli_fetch_array($query)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nombre_cancion']}</td>
            <td>{$row['cantante']}</td>
          </tr>";
}

echo '</table>';

// Cerrar la conexión
mysqli_close($conn);
?>
