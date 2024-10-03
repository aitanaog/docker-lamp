<?php
	session_start();

	// Limpiar todas las variables de sesión
	$_SESSION = array();

	// Destruir la sesión
	session_destroy();

	header("Location: ../index.php"); 
	exit;
?>
