<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $operacion = $_POST["operacion"];

    $salida = [];

    switch ($operacion) {
        case "1": //Agregar
            $numeProd = $_POST["NumeProd"];
            $cantProd = $_POST["CantProd"];
            if (isset($_SESSION["NumeUser"])) {
                $numeUser = $_SESSION["NumeUser"];
                $numeInvi = 'null';
                $numeCarr = buscarDato("SELECT NumeCarr FROM carritos WHERE NumeEstaCarr = 1 AND NumeUser = ". $numeUser);
            }
            else {
                $params = session_get_cookie_params();
                
                $numeUser = 'null';

                if (!isset($_SESSION["NumeInvi"])) {
                    $_SESSION["NumeInvi"] = crearInvitado();
                    
                    setcookie("v-commerce_numeInvi", $_SESSION["NumeInvi"], time() + (60*60*24*365), $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }
                $numeInvi = $_SESSION["NumeInvi"];
                $numeCarr = buscarDato("SELECT NumeCarr FROM carritos WHERE NumeEstaCarr = 1 AND NumeInvi = ". $numeInvi);

                setcookie("v-commerce_numeInvi", $_SESSION["NumeInvi"], time() + (60*60*24*365), $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }
            
            $impoUnit = buscarDato("SELECT ImpoVent FROM productos WHERE NumeProd = ". $numeProd);
            $cantProdOld = 0;

            
            if ($numeCarr == "") {
                //Creo un carrito nuevo
                $strSQL = $crlf."INSERT INTO carritos(FechCarr, NumeUser, NumeInvi, ImpoSubt, ImpoShip, ImpoDesc, ImpoTota, NumeEstaCarr)";
                $strSQL.= $crlf."VALUES(SYSDATE(), {$numeUser}, {$numeInvi}, 0, 0, 0, 0, 1);";

                $result = ejecutarCMD($strSQL, true);

                if ($result["estado"]) {
                    $numeCarr = $result["msg"];
                }
                else {
                    $salida = array("estado"=>false, "html"=>"Error al crear carrito!");
                    break;
                }
            }
            else {
                $cantProdOld = buscarDato("SELECT COALESCE(CantProd, 0) FROM carritosdetalles WHERE NumeCarr = ". $numeCarr ." AND NumeProd = ". $numeProd);
            }
            $_SESSION["NumeCarr"] = $numeCarr;

            if (intval($cantProdOld) == 0) {
                $impoTota = $cantProd * $impoUnit;

                $strSQL = $crlf."INSERT INTO carritosdetalles(NumeCarr, NumeProd, CantProd, ImpoUnit, ImpoTota)";
                $strSQL.= $crlf."VALUES({$numeCarr}, {$numeProd}, {$cantProd}, {$impoUnit}, {$impoTota});";
            }
            else {
                $cantProd+= $cantProdOld;
                $impoTota = $cantProd * $impoUnit;
                
                $strSQL = $crlf."UPDATE carritosdetalles SET CantProd = {$cantProd}, ImpoUnit = {$impoUnit}, ImpoTota = {$impoTota}";
                $strSQL.= $crlf."WHERE NumeCarr = {$numeCarr} AND NumeProd = {$numeProd};";
            }
            $result = ejecutarCMD($strSQL);

            if ($result["estado"]) {
                $salida = carrito();
            }
            else {
                $salida = array("estado"=>false, "html"=>"Error al agregar producto!");
            }
            break;

        case "2": //Quitar
            $numeProd = $_POST["NumeProd"];
            $numeCarr = $_SESSION["NumeCarr"];

            $strSQL = "DELETE FROM carritosdetalles WHERE NumeCarr = {$numeCarr} AND NumeProd = {$numeProd}";
            $result = ejecutarCMD($strSQL);

            if ($result["estado"]) {
                $salida = carrito();
            }
            else {
                $salida = array("estado"=>false, "html"=>"Error al quitar producto!");
            }
            break;

        case "3": //Pre-compra
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($salida);
}

function carrito() {
    global $crlf;

    $strSQL = $crlf."SELECT cd.NumeProd, cd.NombProd, cd.CantProd, cd.ImpoUnit, cd.ImpoTota, cd.RutaImag";
    $strSQL.= $crlf."FROM carritos c";
    $strSQL.= $crlf."INNER JOIN (SELECT cd.NumeCarr, cd.NumeProd, p.NombProd, cd.CantProd, cd.ImpoUnit, cd.ImpoTota, pi.RutaImag";
    $strSQL.= $crlf."			FROM carritosdetalles cd";
    $strSQL.= $crlf."			INNER JOIN productos p ON cd.NumeProd = p.NumeProd";
    $strSQL.= $crlf."			LEFT JOIN productosimagenes pi ON cd.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
    $strSQL.= $crlf."		   ) cd ON c.NumeCarr = cd.NumeCarr";
    $strSQL.= $crlf."WHERE c.NumeCarr = ". $_SESSION["NumeCarr"];
    $carrito = cargarTabla($strSQL);

    $strHTML = "";
    $subtotal = 0;
    $bonificacion = 0;
    $total = 0;

    if ($carrito->num_rows > 0) {
        while ($fila = $carrito->fetch_assoc()) {
            $strHTML.= $crlf.'<article>';
            $strHTML.= $crlf.'	<div class="row">';
            $strHTML.= $crlf.'		<div class="col-lg-5">';
            $strHTML.= $crlf.'			<img class="img-center" alt="" src="admin/'. $fila["RutaImag"] .'">';
            $strHTML.= $crlf.'			<a href="javascript:void(0);" class="quitar" onclick="quitarProd('. $fila["NumeProd"] .')">Quitar</a>';
            $strHTML.= $crlf.'		</div>';
            $strHTML.= $crlf.'		<div class="col-lg-6">';
            $strHTML.= $crlf.'			<p class="titulo">'. $fila["NombProd"] .'</p>';
            $strHTML.= $crlf.'			<p class="cantidad">Cantidad: <span>'. $fila["CantProd"] .'</span></p>';
            $strHTML.= $crlf.'			<p class="precio">$ <span>'. $fila["ImpoTota"] .'</span></p>';
            $strHTML.= $crlf.'		</div>';
            $strHTML.= $crlf.'	</div>';
            $strHTML.= $crlf.'</article>';

            $subtotal+= floatval($fila["ImpoTota"]);
        }
        $total = $subtotal - $bonificacion;
    }
    else {
        $strHTML.= $crlf."<h4>Tu carrito está vacío</h4>";
        $strHTML.= $crlf."<br><br><br>";
    }

    $strSQL = "UPDATE carritos SET ImpoSubt = {$subtotal}, ImpoDesc = 0 WHERE NumeCarr = ". $_SESSION["NumeCarr"];
    ejecutarCMD($strSQL);
    
    $salida = array(
        "estado"=>true, 
        "html"=>$strHTML,
        "subtotal"=>$subtotal,
        "bonificacion"=>$bonificacion,
        "total"=>$total,
        "cantProds"=>$carrito->num_rows
    );

    return $salida;
}