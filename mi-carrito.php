<?php
    session_start();
    require_once 'php/conexion.php';

    if (isset($_SESSION["NumeCarr"])) {
        $strSQL = $crlf."SELECT c.NumeCarr, cd.NumeProd, cd.NombProd, cd.Peso, cd.CantProd, cd.ImpoUnit, cd.ImpoTota, cd.RutaImag, cd.SlugProd, c.FlagShip";
        $strSQL.= $crlf."FROM carritos c";
        $strSQL.= $crlf."INNER JOIN (SELECT cd.NumeCarr, cd.NumeProd, p.NombProd, p.Peso, cd.CantProd, cd.ImpoUnit, cd.ImpoTota, pi.RutaImag, p.SlugProd";
        $strSQL.= $crlf."			FROM carritosdetalles cd";
        $strSQL.= $crlf."			INNER JOIN productos p ON cd.NumeProd = p.NumeProd";
        $strSQL.= $crlf."			LEFT JOIN productosimagenes pi ON cd.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
        $strSQL.= $crlf."		   ) cd ON c.NumeCarr = cd.NumeCarr";
        $strSQL.= $crlf."WHERE c.NumeCarr = ". $_SESSION["NumeCarr"];

        $carrito = cargarTabla($strSQL);    

        if ($carrito->num_rows == 0) {
            $carrito = false;
        }
    }
    else {
        $carrito = false;
    }
    $peso = 0;
    $subtotal = 0;
    $envio = 0;
    $bonificacion = 0;
    $total = 0;
    $ship = "1";

	//Datos usuario
	$numeUser = isset($_SESSION["NumeUser"])? $_SESSION["NumeUser"]: '';
	$numeInvi = isset($_SESSION["NumeInvi"])? $_SESSION["NumeInvi"]: '';

	if ($numeUser != '') {
        $strSQL = "SELECT u.NombPers, u.MailUser, u.TeleUser, u.DireUser, u.CodiPost, u.NumeProv, p.NombProv";
        $strSQL.= $crlf."FROM usuarios u";
        $strSQL.= $crlf."LEFT JOIN provincias p ON u.NumeProv = p.NumeProv";
        $strSQL.= $crlf."WHERE NumeUser = ". $numeUser;

        $datosUsuario = buscarDato($strSQL);
    
        if (isset($_SESSION["NumeCarr"])) {
            $nombPersCarr = buscarDato("SELECT NombPers FROM carritos WHERE NumeCarr = ". $_SESSION["NumeCarr"]);

            if ($nombPersCarr != '') {
                $strSQL = "SELECT u.NombPers, u.MailUser, u.TeleUser, u.DireUser, u.CodiPost, u.NumeProv, p.NombProv";
                $strSQL.= $crlf."FROM carritos u";
                $strSQL.= $crlf."LEFT JOIN provincias p ON u.NumeProv = p.NumeProv";
                $strSQL.= $crlf."WHERE NumeCarr = ". $_SESSION["NumeCarr"];
                $datosUsuario = buscarDato($strSQL);
            }
        }
	}
	elseif ($numeInvi != '') {
		$strSQL = "SELECT u.NombPers, u.MailUser, u.TeleUser, u.DireUser, u.CodiPost, u.NumeProv, p.NombProv";
		$strSQL.= $crlf."FROM invitados u";
		$strSQL.= $crlf."LEFT JOIN provincias p ON u.NumeProv = p.NumeProv";
		$strSQL.= $crlf."WHERE NumeInvi = ". $numeInvi;
		$datosUsuario = buscarDato($strSQL);
	}
	

    //Mercado pago
    require_once "admin/mercadopago/mercadopago.php";
    
    $mpClientID = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_ID'");
    $mpClientSecret = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_SECRET'");
    
    $mp = new MP($mpClientID, $mpClientSecret);
    
    if ($carrito) {
        while ($fila = $carrito->fetch_assoc()) {
            $ship = $fila["FlagShip"];

            $peso+= floatval($fila["Peso"]);
            $subtotal+= floatval($fila["ImpoTota"]);
        }

        //ENVIO
        if ($ship == "1") {
            if ($datosUsuario["NumeProv"] != '' && $peso > 0) {
                if (buscarDato("SELECT COUNT(*) FROM shipping WHERE NumeProv = {$datosUsuario["NumeProv"]}") != '0') {
                    $maxPeso = buscarDato("SELECT MAX(PesoShip) PesoShip FROM shipping WHERE NumeProv = {$datosUsuario["NumeProv"]}");
                    if ($peso <= $maxPeso) {
                        $envio = floatval(buscarDato("SELECT ImpoShip FROM shipping WHERE PesoShip >= {$peso} AND NumeProv = {$datosUsuario["NumeProv"]} ORDER BY PesoShip LIMIT 1"));
                    }
                    else {
                        $envio = floatval(buscarDato("SELECT MAX(ImpoShip) FROM shipping WHERE NumeProv = {$datosUsuario["NumeProv"]} ORDER BY PesoShip LIMIT 1"));
                    }
                }
                else {
                    $maxPeso = buscarDato("SELECT MAX(PesoShip) PesoShip FROM shipping WHERE NumeProv IS NULL");
                    if ($peso <= $maxPeso) {
                        $envio = floatval(buscarDato("SELECT ImpoShip FROM shipping WHERE PesoShip >= {$peso} AND NumeProv IS NULL ORDER BY PesoShip LIMIT 1"));
                    }
                    else {
                        $envio = floatval(buscarDato("SELECT MAX(ImpoShip) FROM shipping WHERE NumeProv IS NULL ORDER BY PesoShip LIMIT 1"));
                    }
                }
            }
        }

        //CHECKOUT
        if ($subtotal > 0) {
            $preference_data = array(
                "items" => array(
                    array(
                        "title" => "Advocatus",
                        "currency_id" => "ARS",
                        "category_id" => "Libro",
                        "picture_url" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz ."img/logo_transparente.png",
                        "quantity" => 1,
                        "unit_price" => $subtotal + $envio - $bonificacion
                        )
                    ),
                    "external_reference" => $_SESSION["NumeCarr"],
                    "notification_url" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz ."admin/mercadopago/notifications.php",
                    "back_urls" => array(
                        "success" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz,
                        "pending" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz,
                        "failure" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz
                    )
            );
            
            $preference = $mp->create_preference($preference_data);
        }

        $carrito->data_seek(0);
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Mis Compras - ADVOCATUS | Editorial · Librería</title>

    <?php include 'php/links-header.php'; ?>

    <script>
        function quitarProd1(strID) {
            $.post("php/carritosProcesar.php", { 
                "operacion": "2",
                "NumeProd": strID,
                },
                function (data) {
                    if (data.estado === true) {
                        location.reload();
                    }
                }
            );
        }

        function envio() {
            $.post("php/carritosProcesar.php", { 
                "operacion": "3"
                },
                function (data) {
                    if (data.estado === true) {
                        location.reload();
                    }
                }
            );
        }
    </script>
</head>
<body>

<?php include 'php/header.php'; ?>

        <!-- ORDEN DE COMPRA -->
        <div id="mi-carrito" class="container">
        <div class="row">
            <div class="col-lg-6">
                <h1>Mi carrito de compras</h1>
            </div>
            
			<?php if (isset($preference)) {?>
				<?php if ($ship == 1 && $envio == 0) {?>
					<a href="#mdlEnvio" class="btn-carrito-negro pushRight" data-toggle="modal">Cargar datos de envío y Comprar</a>
                <?php } elseif(!isset($datosUsuario) || $datosUsuario["NombPers"] == '') { ?>
                    <a href="#mdlEnvio" class="btn-carrito-negro pushRight" data-toggle="modal">Cargar datos personales y Comprar</a>
                <?php } elseif ($subtotal > 0) {?>
					<div class="col-lg-6"><a href="<?php echo $preference['response']['init_point']; ?>" name="MP-Checkout" class="btn-carrito-negro pushRight">Realizar compra</a></div>
	            <?php }?>
            <?php }?>
        </div>

        <div class="row hidden-xs">
            <div class="col-sm-3 noPadding">
                <h2>Producto</h2>
            </div>
            <div class="col-sm-3 noPadding">		
                <h2>Descripción</h2>
            </div>
            <div class="col-sm-2 noPadding text-right">		
                <h2>Cantidad</h2>
            </div>
            <div class="col-sm-2 noPadding text-right">
                <h2>Precio</h2>
            </div>
            <div class="col-sm-2 noPadding text-right">
                <h2>Total</h2>
            </div>
        </div>
        
        <?php 
        if (!$carrito) {
            echo '<h4>Su carrito está vacío</h4>';
        } else {
            $strHTML = "";

            while($fila = $carrito->fetch_assoc()) {
                $strHTML.= $crlf.'<div class="row">';
                $strHTML.= $crlf.'    <div class="col-sm-3 noPadding">';
                $strHTML.= $crlf.'        <h2 class="visible-xs">Producto</h2>';
                $strHTML.= $crlf.'        <article>';
                $strHTML.= $crlf.'            <a href="producto/'. $fila["SlugProd"] .'.php"><img class="img-producto-carrito" src="admin/'. $fila["RutaImag"] .'" alt=""></a>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-sm-3 noPadding">';
                $strHTML.= $crlf.'        <h2 class="visible-xs">Descripción</h2>';
                $strHTML.= $crlf.'        <article>';
                $strHTML.= $crlf.'            <p class="info-producto">'. $fila["NombProd"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-sm-2 noPadding">';
                $strHTML.= $crlf.'        <h2 class="visible-xs">Cantidad</h2>';
                $strHTML.= $crlf.'        <article id="1">';
                $strHTML.= $crlf.'            <p class="info-producto text-right">'. $fila["CantProd"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-sm-2 noPadding">';
                $strHTML.= $crlf.'        <h2 class="visible-xs">Precio</h2>';
                $strHTML.= $crlf.'        <article id="1">';
                $strHTML.= $crlf.'            <p class="info-producto text-right">$ '. $fila["ImpoUnit"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-sm-2 noPadding">';
                $strHTML.= $crlf.'        <h2 class="visible-xs">Total</h2>';
                $strHTML.= $crlf.'        <article id="1">';
                $strHTML.= $crlf.'            <p class="info-producto text-right">$ '. $fila["ImpoTota"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'</div>';
                $strHTML.= $crlf.'<div class="row">';
                $strHTML.= $crlf.'    <div class="eliminar-item"><span class="clickable" data-js="quitarProd1('. $fila["NumeProd"] .');">ELIMINAR ITEM X</span></div>';
                $strHTML.= $crlf.'</div>';
            }
            echo $strHTML;
            $total = $subtotal + $envio - $bonificacion;

            $strSQL = "UPDATE carritos SET ImpoSubt = {$subtotal}, ImpoDesc = {$bonificacion}, ImpoShip = {$envio}, ImpoTota = {$total} WHERE NumeCarr = ". $_SESSION["NumeCarr"];
            ejecutarCMD($strSQL);
        }
        ?>
        <div class="row">
            <div class="col-lg-6">
                <!-- <h4>Agregar código de bonificación</h4> -->
				<!-- <input class="codigo-bonificacion" value="" placeholder="Ingrese su código aquí"> <a href="#" class="btn-carrito-negro">Aplicar</a>   -->
				<h4>Datos de envío y contacto</h4>
				<?php 
					if (isset($datosUsuario)) {
						$strSalida = '';
						if ($subtotal > 0 && $envio == 0 && $ship == "1") {
							$strSalida.= $crlf.'<a href="#mdlEnvio" class="btn-carrito-negro" data-toggle="modal">Cargar datos de envío y Comprar</a>';
							$strSalida.= $crlf.'<a href="javascript:void(0);" class="btn-carrito-negro" onclick="envio()">Retiro en tienda</a>';
						}
						else {
                            if ($ship == "1") {
                                $strSalida.= $crlf.'<div><strong>ENVIO A DOMICILIO</strong></div>';
                            }
                            else {
                                $strSalida.= $crlf.'<div><strong>RETIRO EN TIENDA</strong></div>';
                            }
							$strSalida.= $crlf.'<div><strong>Nombre completo: </strong> '.$datosUsuario["NombPers"].'</div>';
							$strSalida.= $crlf.'<div><strong>Teléfono: </strong> '.$datosUsuario["TeleUser"].'</div>';
							$strSalida.= $crlf.'<div><strong>Mail: </strong> '.$datosUsuario["MailUser"].'</div>';
							$strSalida.= $crlf.'<div><strong>Dirección: </strong> '.$datosUsuario["DireUser"].'</div>';
							$strSalida.= $crlf.'<div><strong>Código postal: </strong> '.$datosUsuario["CodiPost"].'</div>';
							$strSalida.= $crlf.'<div><strong>Provincia: </strong> '.$datosUsuario["NombProv"].'</div>';
                            $strSalida.= $crlf.'<a href="#mdlEnvio" class="btn-carrito-negro" data-toggle="modal">Modificar datos</a>';
                            if ($ship == "1") {
                                $strSalida.= $crlf.'<a href="javascript:void(0);" class="btn-carrito-negro" onclick="envio()">Retiro en tienda</a>';
                            }
                            else {
                                $strSalida.= $crlf.'<a href="javascript:void(0);" class="btn-carrito-negro" onclick="envio()">Envío a domicilio</a>';
                            }
						}
						echo $strSalida;
					}
				?>
            </div>
            <div class="col-sm-6">
                <div class="col-xs-6">
                    <h3>SUBTOTAL</h3>
                    <h3>ENVIO</h3>
                    <!-- <h3>BONIFICACION</h3> -->
                    <h3>TOTAL:</h3>
                </div>
                <div class="col-xs-6">
                    <h3 class="alignRight">$ <?php echo number_format($subtotal, 2)?></h3>
                    <h3 class="alignRight">$ <?php echo number_format($envio, 2)?></h3>
                    <!-- <h3 class="alignRight">$ <?php echo number_format($bonificacion, 2)?></h3> -->
                    <h3 class="alignRight">$ <?php echo number_format($total, 2)?></h3>
                </div>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-lg-6"> </div>
			<?php if (isset($preference)) {?>
				<?php if ($ship == 1 && $envio == 0) {?>
					<a href="#mdlEnvio" class="btn-carrito-negro pushRight" data-toggle="modal">Cargar datos de envío y Comprar</a>
                <?php } elseif(!isset($datosUsuario) || $datosUsuario["NombPers"] == '') { ?>
                    <a href="#mdlEnvio" class="btn-carrito-negro pushRight" data-toggle="modal">Cargar datos personales y Comprar</a>
                <?php } elseif ($subtotal > 0) {?>
					<div class="col-lg-6"><a href="<?php echo $preference['response']['init_point']; ?>" name="MP-Checkout" class="btn-carrito-negro pushRight">Realizar compra</a></div>
	            <?php }?>
            <?php }?>
        </div>
        <br/><br/>
        <div class="col-lg-11">
            <div class="row">
                <div class="col-lg-3">
                    <p class="envios">Envíos a todo el país</p>
                </div>
                <div class="col-lg-9"><img class="img-center" src="./img/mi-carrito/formas-de-pago.png" alt="" style="max-width: 500px;"></div>
            </div>
        </div>
        <div class="col-lg-1"></div>
        </div>
        <!-- /.ORDEN DE COMPRA -->

    </div>

    <?php include 'php/footer.php'; ?>

	<?php if (isset($datosUsuario)) {?>
	<div class="modal fade" id="mdlEnvio" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form id="frmEnvio" method="post">
				<div class="modal-content">
					<div class="modal-header" align="center" style="padding: 5px;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</button>
					</div>

					<h4 class="modal-title">Datos de Envío</h4>
					<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="NombPers">Nombre completo *</label>
								<input type="text" class="form-control form-custom" id="NombPers" placeholder="Nombre Completo *" value="<?php echo $datosUsuario["NombPers"]?>" required>
							</div>
							<div class="form-group">
								<label for="TeleUser">Teléfono *</label>
								<input type="text" class="form-control form-custom" id="TeleUser" placeholder="Teléfono *" value="<?php echo $datosUsuario["TeleUser"]?>" required>
							</div>
							<div class="form-group">
								<label for="MailUser">Mail *</label>   
								<input type="email" class="form-control form-custom" id="MailUser" placeholder="Correo Electrónico *" value="<?php echo $datosUsuario["MailUser"]?>" required>
							</div>
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="DireUser">Dirección</label>
										<input type="text" class="form-control form-custom" id="DireUser" placeholder="Dirección *" value="<?php echo $datosUsuario["DireUser"]?>" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="CodiPost">Código Postal</label>
										<input type="text" class="form-control form-custom" id="CodiPost" placeholder="Código Postal" value="<?php echo $datosUsuario["CodiPost"]?>" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="NumeProv">Provincia *</label>
								<select class="form-control form-custom" id="NumeProv" required>
								<?php echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv", $datosUsuario["NumeProv"]);?>
								</select>
							</div>
						</div>
					</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-small">Confirmar</button>
					</div>
				</div><!-- /.modal-content -->
			</form>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->    
	<?php }?>

    <script type="text/javascript" src="//resources.mlstatic.com/mptools/render.js"></script>
    <!-- <script type="text/javascript" src="http://mp-tools.mlstatic.com/buttons/render.js"></script> -->
    <?php include 'php/scripts-footer.php'; ?>
</body>
</html>