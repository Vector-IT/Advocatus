<?php
require_once '../php/datos.php';
require_once "mercadopago.php";

$mpClientID = $config->buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_ID'");
$mpClientSecret = $config->buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_SECRET'");

$mp = new MP($mpClientID, $mpClientSecret);

//Activo modo prueba
$mp->sandbox_mode(TRUE);

if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}


// Get the payment and the corresponding merchant_order reported by the IPN.
try {
	if($_GET["topic"] == 'payment'){
		$payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
		//$payment_info = $mp->get_payment_info($_GET["id"]);
		$numeCarr = $payment_info["response"]["collection"]["external_reference"];

		$merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
	// Get the merchant_order reported by the IPN.
	} else if($_GET["topic"] == 'merchant_order'){
		$merchant_order_info = $mp->get("/merchant_orders/" . $_GET["id"]);
	}
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
		$strSQL = "UPDATE carritos SET NumeEstaCarr = {$numeEstaCarr} WHERE NumeCarr = ".$numeCarr;
		$config->ejecutarCMD($strSQL);
	} else {
		// Not paid yet. Do not release your item
	}

	http_response_code(200);
}
?>