 <?php
  
  //MUESTRA LA LISTA DE LAS CANCIONES 
  
  //Conectar con la base de datos
  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }



	$query = mysqli_query($conn, "SELECT nombre,cantante FROM canciones")
   		or die (mysqli_error($conn));

	// Mostrar los resultados en una tabla
    	echo '<table border="1">
           	 <tr>
                	<th>nombre</th>
                	<th>cantante</th>
            	</tr>';
    
    // Iterar sobre los resultados
    while ($row = mysqli_fetch_array($query)) {
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['cantante']}</td>
              </tr>";
    }
    
    echo '</table>';
?>
