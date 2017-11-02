<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

if($_SERVER["REQUEST_METHOD"] == "POST") {
	require_once 'datosdb.php';

	require_once 'phpmailer/Exception.php';
	require_once 'phpmailer/SMTP.php';
	require_once 'phpmailer/PHPMailer.php';

	$para = isset($_POST["Para"])? $_POST["Para"]: 'info@eadvocatus.com';
	$titulo = isset($_POST["Titulo"])? $_POST["Titulo"]: 'Editorial Advocatus';
	
	$mensaje = $_POST['Mensaje'];
	$mensaje.= $crlf."<br><br>";
	$mensaje.= $crlf.'<img src="http://'. $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'/mail_advocatus/firma_tienda.jpg" alt="" />';

	$mensajeAlt = isset($_POST["MensajeAlt"])? $_POST["MensajeAlt"]: $_POST["Mensaje"];

	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';
	$mail->isHTML(true);
	$mail->setLanguage('es', $raiz.'admin/php/phpmailer/language/');
	$mail->isSMTP();

	// $mail->SMTPDebug = 1;

	// $mail->Host = 'mail.eadvocatus.com.ar';
	// $mail->SMTPAuth = true;
    // $mail->Username = 'tienda@eadvocatus.com.ar';
    // $mail->Password = 'Vector159';
    // $mail->Port = 587;  
	// $mail->setFrom('tienda@eadvocatus.com.ar', 'Editorial Advocatus');

	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->Username = 'tiendavirtualadvocatus';
	$mail->Password = 'Montevideo635';
	$mail->setFrom('tiendavirtualadvocatus@gmail.com', 'Tienda Virtual Advocatus');
	$mail->addReplyTo("tiendavirtualadvocatus@gmail.com","Tienda Virtual Advocatus");
	
	$mail->addAddress($para);
	$mail->Subject = $titulo;
	$mail->Body = $mensaje;
	$mail->AltBody = $mensajeAlt;

	if (count($_FILES) > 0) {
		$mail->addAttachment($_FILES["Archivo"]["tmp_name"], $_FILES["Archivo"]["name"]);
	}

	if(!$mail->send()) {
		echo "Error al enviar datos!";
		echo $crlf . $mail->ErrorInfo;
	} else {
		echo "Datos enviados!";
	}
} 
else {
	header('HTTP/1.1 403 Forbidden');
	header('Status: 403 Forbidden');
}	
?>
