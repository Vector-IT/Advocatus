<?php
require_once '../php/datos.php';
require_once "mercadopago.php";

$mpClientID = $config->buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_ID'");
$mpClientSecret = $config->buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_SECRET'");

$mp = new MP($mpClientID, $mpClientSecret);

//Activo modo prueba
//$mp->sandbox_mode(TRUE);

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}


// Get the payment and the corresponding merchant_order reported by the IPN.
try {
    $paymentInfo = $mp->get_payment ($_GET["id"]);
    $payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
    //$payment_info = $mp->get_payment_info($_GET["id"]);
    $numeCarr = $payment_info["response"]["collection"]["external_reference"];
    
    error_log("Procesando carrito NRO: ". $numeCarr ." - ID: ". $_GET["id"]);

    $merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
}
catch (Exception $e) {
	http_response_code(200);
	return;
}

if ($merchant_order_info["status"] == 200) {
	// If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items 
	$paid_amount = 0;

	switch ($merchant_order_info["response"]["payments"][0]["status"]) {
		case 'pending':
		case 'in_process':
			$numeEstaCarr = 2;
			break;

		case 'rejected':
			$numeEstaCarr = 3;
			break;

		case 'in_mediation':
			$numeEstaCarr = 4;
			break;

		case 'cancelled':
		case 'charged_back':
			$numeEstaCarr = 5;
			break;

		case 'refunded':
			$numeEstaCarr = 6;
			break;

		case 'approved':
			$numeEstaCarr = 7;
			break;
	}

	$paid_amount = $merchant_order_info["response"]["payments"][0]['transaction_amount'];
	/*
	foreach ($merchant_order_info["response"]["payments"] as  $payment) {
		if ($payment['status'] == 'approved'){
			$paid_amount += $payment['transaction_amount'];
		}	
	}
	*/

	if($paid_amount >= $merchant_order_info["response"]["total_amount"]){
		// Totally paid. Release your item
		$datosUsuario = $config->buscarDato("SELECT NumeUser, NumeInvi, NombPers, MailUser, TeleUser, DireUser, CodiPost, NombLoca, NumeProv FROM carritos WHERE NumeCarr = ".$numeCarr);

		if ($datosUsuario["NombPers"] == '') {
			if ($datosUsuario["NumeUser"] != '') {
				$strSQL = "SELECT u.NombPers, u.MailUser, u.TeleUser, u.DireUser, u.CodiPost, u.NombLoca, u.NumeProv";
				$strSQL.= $crlf."FROM usuarios u";
				$strSQL.= $crlf."WHERE u.NumeUser = ". $datosUsuario["NumeUser"];
		
				$datosUsuario = $config->buscarDato($strSQL);
			}
			else {
				$strSQL = "SELECT u.NombPers, u.MailUser, u.TeleUser, u.DireUser, u.CodiPost, u.NombLoca, u.NumeProv";
				$strSQL.= $crlf."FROM invitados u";
				$strSQL.= $crlf."WHERE u.NumeInvi = ". $datosUsuario["NumeInvi"];
		
				$datosUsuario = $config->buscarDato($strSQL);
			}
		}

		$strSQL = "UPDATE carritos SET ";
		$strSQL.= $crlf."ID_MP = '{$_GET["id"]}'";
		$strSQL.= $crlf.", NumeEstaCarr = ". $numeEstaCarr;
		$strSQL.= $crlf.", NombPers = '{$datosUsuario["NombPers"]}'";
		$strSQL.= $crlf.", TeleUser = '{$datosUsuario["TeleUser"]}'";
		$strSQL.= $crlf.", MailUser = '{$datosUsuario["MailUser"]}'";
		$strSQL.= $crlf.", DireUser = '{$datosUsuario["DireUser"]}'";
		$strSQL.= $crlf.", CodiPost = '{$datosUsuario["CodiPost"]}'";
		$strSQL.= $crlf.", NombLoca = '{$datosUsuario["NombLoca"]}'";
		$strSQL.= $crlf.", NumeProv = ". $datosUsuario["NumeProv"];
		$strSQL.= $crlf." WHERE NumeCarr = ".$numeCarr;

		$config->ejecutarCMD($strSQL);

		//Descuento stock
		$strSQL = $crlf."SELECT cd.NumeProd, cd.CantProd";
        $strSQL.= $crlf."FROM carritosdetalles cd";
		$strSQL.= $crlf."WHERE cd.NumeCarr = ". $numeCarr;
		$tblCarrito = $config->cargarTabla($strSQL);

		while ($fila = $tblCarrito->fetch_assoc()) {
			$strSQL = $crlf."UPDATE productos SET CantProd = CantProd - {$fila["CantProd"]} WHERE NumeProd = ". $fila["NumeProd"];
			$config->ejecutarCMD($strSQL);
		}

		//Envio mail
		$titulo = "Tienda Advocatus - Nueva compra";
		$mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
		$mensajeHtml.= "<br>Nueva compra registrada en eadvocatus.com.ar";
		$mensajeHtml.= "<br>";
		$mensajeHtml.= "<br>Nro Carrito: <strong>{$numeCarr}</strong>";
		$mensajeHtml.= "<br>Importe: <strong>$ {$paid_amount}</strong>";
		
		$mensaje = "Este es un mensaje automatico. Por favor no lo responda";
		$mensaje.= "\nNueva compra registrada en eadvocatus.com.ar";
		$mensaje.= "\n";
		$mensaje.= "\nNro Carrito: ". $numeCarr;
		$mensaje.= "\nImporte: $". $paid_amount;
		
		$url = "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'admin/php/enviarMail.php';
		$fields = array(
				'Para' => 'administracion@eadvocatus.com.ar',
				'Titulo' => $titulo,
				'Mensaje' => $mensajeHtml,
				'MensajeAlt' => $mensaje
		);
		$datos = http_build_query($fields);
			
		//open connection
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		
		//execute post
		$response = curl_exec($handle);
		if (!$response) {
			$salida = array("estado"=>false, "msg"=>"Error al recuperar contraseña!");
		}
		//close connection
		curl_close($handle);
		
		if (strripos($response, "error") === false) {
			$salida = array("estado"=>true, "msg"=>"Enviamos un correo electrónico con su contraseña!");
		}
		else {
			$salida = array("estado"=>false, "msg"=>"Su correo no está registrado!");
		}
	} else {
		// Not paid yet. Do not release your item
	}

	http_response_code(200);
}
?>