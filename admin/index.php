<?php 
	session_start();

	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");

	require_once 'php/datos.php';

	if (!isset($_SESSION['is_logged_in'])){
		header("Location:login.php");
		die();
	}

	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
	header("Pragma: no-cache"); // HTTP 1.0.
	header("Expires: 0"); // Proxies.	
?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once 'php/linksHeader.php';?>
</head>
<body>
	<?php
		$config->crearMenu();
		
		require_once 'php/header.php';
	?>
	
	<div class="container-fluid">	
		<div class="page-header">
			<h2>Consola de Administraci&oacute;n</h2>
		</div>
		
		<p class="lead">
			Bienvenido a la consola de administraci&oacute;n de <?php echo $config->titulo ?><br>
			Utilice el men&uacute; situado en el margen izquierdo de la pantalla para acceder a las distintas 
			secciones del sistema.			
		</p>
		<hr>
	</div>	
	
	<?php
		require_once 'php/footer.php';
	?>
</body>
</html>