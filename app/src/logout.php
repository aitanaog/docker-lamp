<?php
	session_start([
		'cookie_lifetime' => 86400,
		'cookie_httponly' => true,
		'cookie_secure' => true,
	]);

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

