<?php
session_start();
echo '<head>';
  echo '  <link rel="stylesheet" href="../css/styles.css">';
    echo '</head>';
   // Crear un menú de navegación
     echo '<header>';
  echo '  <h1 style="text-align: center; color: #1E90FF; font-family: Arial, sans-serif; margin-bottom: 20px;">MusicCloud</h1>';
  echo '<nav>';
  echo '<ul style="list-style-type: none; padding: 0;">';
  
    if (isset($_SESSION['email'])) {
        // La sesión está iniciada, el usuario está autenticado
        echo '<li style="display: inline; margin-right: 15px;"><a href="/src/logout.php">Cerrar Sesión</a></li>';
        echo '<li style="display: inline; margin-right: 15px;"><a href="/src/modify_user.php">Modificar perfil</a></li>';
    } else {
        // No hay sesión iniciada
        echo '<li style="display: inline; margin-right: 15px;"><a href="/src/register.php">Página de registro</a></li>';
        echo '<li style="display: inline; margin-right: 15px;"><a href="/src/login.php">Log in</a></li>';
    }
   
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/items.php">Mostrar playlist</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/add_item.php">Añadir cancion</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/delete_item.php">Eliminar cancion</a></li>';
  echo '<li style="display: inline; margin-right: 15px;"><a href="/src/modify_item.php">Modificar cancion</a></li>';
  echo '</ul>';
  echo '</nav>';


  // phpinfo();
  //$hostname = "db";
  //$username = "admin";
  //$password = "test";
  //$db = "database";

  //$conn = mysqli_connect($hostname,$username,$password,$db);
  //if ($conn->connect_error) {
   // die("Database connection failed: " . $conn->connect_error);
  //}



//$query = mysqli_query($conn, "SELECT * FROM usuarios")
   //or die (mysqli_error($conn));

// Mostrar los resultados en una tabla
    //echo '<table border="1">
            //<tr>
                //<th>ID</th>
               // <th>Nombre</th>
                //<th>Apellidos</th>
                //<th>DNI</th>
                //<th>Teléfono</th>
                //<th>Fecha de Nacimiento</th>
                //<th>Email</th>
            //</tr>';
    
    // Iterar sobre los resultados
    //while ($row = mysqli_fetch_array($query)) {
      //  echo "<tr>
               // <td>{$row['id']}</td>
               // <td>{$row['nombre']}</td>
               // <td>{$row['apellidos']}</td>
               // <td>{$row['dni']}</td>
               // <td>{$row['telefono']}</td>
               // <td>{$row['fecha_nacimiento']}</td>
               // <td>{$row['email']}</td>
              //</tr>";
    //}
    
    //echo '</table>';
   
  // Contenido principal con un diseño más atractivo
  echo '<main style="text-align: center; font-family: Arial, sans-serif;">';
  echo '  <section style="margin: 50px 0;">';
  echo '    <h2 style="color: #1E90FF; font-size: 2.5em;">Bienvenido a <span style="color: #1E90FF;font-size: 2em;">MusicCloud</span></h2>';
  echo '    <h3 style="color: #555;">Crea y personaliza tus playlists en la nube</h3>';
  echo '    <p style="color: #777; font-size: 18px;">Disfruta de música ilimitada, gestiona tus canciones favoritas y llévalas contigo a cualquier lugar.</p>';
?>
