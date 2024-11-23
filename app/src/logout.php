<?php
	session_start();

	// Limpiar todas las variables de sesión
	//$_SESSION = array();
	session_unset();

	// Destruir la sesión
	session_destroy();


	header("Location: ../index.php"); 
	exit;
?>

	header("Location: index.php"); 
	exit;
?>

