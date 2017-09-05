<?php
    session_start();
    require_once 'php/conexion.php';

    if (isset($_SESSION["NumeCarr"])) {
        $strSQL = $crlf."SELECT c.NumeCarr, cd.NumeProd, cd.NombProd, cd.CantProd, cd.ImpoUnit, cd.ImpoTota, cd.RutaImag, cd.SlugProd";
        $strSQL.= $crlf."FROM carritos c";
        $strSQL.= $crlf."INNER JOIN (SELECT cd.NumeCarr, cd.NumeProd, p.NombProd, cd.CantProd, cd.ImpoUnit, cd.ImpoTota, pi.RutaImag, p.SlugProd";
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
    $subtotal = 0;
    $bonificacion = 0;
    $total = 0;

    //Mercado pago
    require_once "admin/mercadopago/mercadopago.php";
    
    $mpClientID = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_ID'");
    $mpClientSecret = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'MP_CLIENT_SECRET'");
    
    $mp = new MP($mpClientID, $mpClientSecret);
    
    if ($carrito) {
        while ($fila = $carrito->fetch_assoc()) {
            $subtotal+= floatval($fila["ImpoTota"]);
        }

        if ($subtotal > 0) {
            $preference_data = array(
                "items" => array(
                    array(
                        "title" => "Advocatus",
                        "currency_id" => "ARS",
                        "category_id" => "Libro",
                        "picture_url" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz ."img/logo_transparente.png",
                        "quantity" => 1,
                        "unit_price" => $subtotal
                    )
                    ),
                    "external_reference" => $_SESSION["NumeCarr"],
                    "notification_url" => "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz ."admin/mercadopago/notifications.php"
            );
            
            $preference = $mp->create_preference($preference_data);
        }

        $subtotal = 0;
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
                <div class="col-lg-6"><a href="<?php echo $preference["response"]["sandbox_init_point"]; ?>" name="MP-Checkout" class="btn-carrito-negro pushRight">Realizar compra</a></div>
            <?php }?>
        </div>
        <div class="row">
            <div class="col-lg-3 noPadding">
                <h2>Producto</h2>
            </div>
            <div class="col-lg-3 noPadding">
                <h2>Descripción</h2>
            </div>
            <div class="col-lg-2 noPadding">
                <h2>Cantidad</h2>
            </div>
            <div class="col-lg-2 noPadding">
                <h2>Precio</h2>
            </div>
            <div class="col-lg-2 noPadding">
                <h2>Total</h2>
            </div>
        </div>
        <?php 
        if (!$carrito) {
            echo '<h4>Su carrito está vacío</h4>';
        } else {
            $strHTML = "";

            while($fila = $carrito->fetch_assoc()) {
                $subtotal+= floatval($fila["ImpoTota"]);

                $strHTML.= $crlf.'<div class="row">';
                $strHTML.= $crlf.'    <div class="col-lg-3 noPadding">';
                $strHTML.= $crlf.'        <article>';
                $strHTML.= $crlf.'            <a href="producto/'. $fila["SlugProd"] .'.php"><img class="img-producto-carrito" src="admin/'. $fila["RutaImag"] .'" alt=""></a>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-lg-3 noPadding">';
                $strHTML.= $crlf.'        <article>';
                $strHTML.= $crlf.'            <p class="info-producto">'. $fila["NombProd"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-lg-2 noPadding">';
                $strHTML.= $crlf.'        <article id="1">';
                $strHTML.= $crlf.'            <p class="info-producto text-right">'. $fila["CantProd"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-lg-2 noPadding">';
                $strHTML.= $crlf.'        <article id="1">';
                $strHTML.= $crlf.'            <p class="info-producto text-right">$ '. $fila["ImpoUnit"] .'</p>';
                $strHTML.= $crlf.'        </article>';
                $strHTML.= $crlf.'    </div>';
                $strHTML.= $crlf.'    <div class="col-lg-2 noPadding">';
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
            $total = $subtotal - $bonificacion;
        }
        ?>
        <div class="row">
            <div class="col-lg-6">
                <h4>Agregar código de bonificación</h4>
                <input class="codigo-bonificacion" value="" placeholder="Ingrese su código aquí"> <a href="#" class="btn-carrito-negro">Aplicar</a>  
            </div>
            <div class="col-sm-6">
                <div class="col-xs-6">
                    <h3>SUBTOTAL</h3>
                    <h3>ENVIO</h3>
                    <h3>BONIFICACION</h3>
                    <h3>TOTAL:</h3>
                </div>
                <div class="col-xs-6">
                    <h3 class="alignRight">$ <?php echo number_format($subtotal, 2)?></h3>
                    <h3 class="alignRight">GRATIS</h3>
                    <h3 class="alignRight">$ <?php echo number_format($bonificacion, 2)?></h3>
                    <h3 class="alignRight">$ <?php echo number_format($total, 2)?></h3>
                </div>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-lg-6"> </div>
            <?php if (isset($preference)) {?>
                <div class="col-lg-6"><a href="<?php echo $preference["response"]["sandbox_init_point"]; ?>" name="MP-Checkout" class="btn-carrito-negro pushRight">Realizar compra</a></div>
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
    <?php include 'php/scripts-footer.php'; ?>

    <script type="text/javascript" src="//resources.mlstatic.com/mptools/render.js"></script>
</body>
</html>