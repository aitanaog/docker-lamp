<?php
  
   // Crear un menú de navegación
  echo '<nav>';
  echo '<ul style="list-style-type: none; padding: 0;">';
  echo '<li style="display: inline; margin-right: 15px;"><a href="register.php">Página de registro</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="login.php">Log in</a></li>'; // Puedes añadir más elementos de menú aquí
  echo '</ul>';
  echo '</nav>';


  // phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }



$query = mysqli_query($conn, "SELECT * FROM usuarios")
   or die (mysqli_error($conn));

// Mostrar los resultados en una tabla
    echo '<table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Fecha de Nacimiento</th>
                <th>Email</th>
            </tr>';
    
    // Iterar sobre los resultados
    while ($row = mysqli_fetch_array($query)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['apellidos']}</td>
                <td>{$row['dni']}</td>
                <td>{$row['telefono']}</td>
                <td>{$row['fecha_nacimiento']}</td>
                <td>{$row['email']}</td>
              </tr>";
    }
    
    echo '</table>';
   

?>