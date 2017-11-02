<?php
	session_start();
	require_once 'php/conexion.php';

	$strSQL = "SELECT RutaImag FROM slidersimagenes WHERE NumeSlid = 1";
	$slider1 = cargarTabla($strSQL);
  
	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd, p.CantProd";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."INNER JOIN productosnovedades pn ON p.NumeProd = pn.NumeProd";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."ORDER BY pn.NumeOrde";
	$novedades = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Novedades - ADVOCATUS | Editorial · Librería</title>
    
    <?php include 'php/links-header.php'; ?>
	<link rel="stylesheet" href="css/equal-height-columns.css">
	<link href="css/sidebar.css" rel="stylesheet">
</head>
<body class="promociones">
<div id="wrapper">
		<div class="overlay"></div>
		<!-- Page Content -->
		<div id="page-content-wrapper">

	<?php include 'php/header.php'; ?>

    <!-- SLIDER PROMOS -->
	<div class="container-full">
		<div class="row noMargin">
			<div class="col-sm-12 noPadding">
				<div id="promos" class="carousel slide" data-ride="carousel" data-interval="4000">
				<!-- Indicators -->
					<ol class="carousel-indicators">
					<?php
						$strSalida = "";
						$I = 0;
						while ($fila = $slider1->fetch_assoc()) {
							if ($I == 0) {
								$strSalida.= $crlf.'<li data-target="#promos" data-slide-to="'.$I.'" class="active"></li>';
							}
							else {
								$strSalida.= $crlf.'<li data-target="#promos" data-slide-to="'.$I.'"></li>';
							}
							$I++;
						}
						echo $strSalida;
					?>
					</ol>
					<!-- Wrapper for slides -->
					<div class="carousel-inner">
					<?php
						$strSalida = "";
						$I = 0;
						$slider1->data_seek(0);
						while ($fila = $slider1->fetch_assoc()) {
							if ($I == 0) {
								$I++;
								$strSalida.= $crlf.'<div class="item active">';
							}
							else {
								$strSalida.= $crlf.'<div class="item">';
							}
							$strSalida.= $crlf.'<img src="admin/'. $fila["RutaImag"] .'" alt="">';
							$strSalida.= $crlf.'</div>';
						}
						echo $strSalida;
					?>
					</div>
					<!-- Left and right controls -->
					<a class="left carousel-control" href="#promos" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
						<span class="sr-only">Anterior</span>
					</a>
					<a class="right carousel-control" href="#promos" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<span class="sr-only">Siguiente</span>
					</a>
				</div>
			</div>
		</div>
	</div>
      <!-- /.SLIDER PROMOS -->
      <!-- DESTACADOS -->
    <div id="productos-destacados" class="container promos">
        <div class="row">
    	    <div class="col-sm-12">
        	    <h2>Novedades Destacadas</h2>
            	<p>Accedé a las últimas novedades de nuestra Editorial</p>
			</div>
        </div>
    </div>
      <!-- /.DESTACADOS -->

 
      <!-- PROMOS -->
    <div id="productos-promo" class="container">
	<?php 
		if ($novedades->num_rows > 0) {
			$J = 0;
			$salida = '';

			//Promociones
			$strSQL = "SELECT NumeTipoProm, ValoProm, NumeTipoFilt, ValoFilt";
			$strSQL.= $crlf."FROM promociones pr";
			$strSQL.= $crlf."LEFT JOIN promocionesfiltros pf ON pr.NumeProm = pf.NumeProm";
			$strSQL.= $crlf."WHERE pr.NumeEsta = 1";
			$strSQL.= $crlf."AND (pr.NombCupo IS NULL OR pr.NombCupo = '')";
			$strSQL.= $crlf."AND (pr.FechDesd IS NULL OR pr.FechDesd <= SYSDATE())";
			$strSQL.= $crlf."AND (pr.FechHast IS NULL OR pr.FechHast > SYSDATE())";
			$strSQL.= $crlf."AND (pr.CantPerm IS NULL OR pr.CantUtil < pr.CantPerm)";
			$strSQL.= $crlf."AND (pf.NumeEsta = 1 OR pf.NumeEsta IS NULL)";
			$promociones = cargarTabla($strSQL);
			
			while ($fila = $novedades->fetch_assoc()) {
				//Categorias
				$strSQL = "SELECT NumeCate FROM productoscategorias WHERE NumeProd = ". $fila["NumeProd"];
				$categorias = cargarTabla($strSQL);
				$filtroCategorias = [];
				while ($cate = $categorias->fetch_assoc()) {
					$filtroCategorias[] = $cate["NumeCate"];
				}


				//Cantidad de favoritos
				$cantFav = buscarDato("SELECT COUNT(*) FROM usuariosfavoritos WHERE NumeProd = ". $fila["NumeProd"]);

				if ($J == 0) {
					$salida.= $crlf.'<div class="row row-eq-height">';
					$J = 1;
				}
				elseif ($J == 4) {
					$salida.= $crlf.'</div>';
					$salida.= $crlf.'<div class="row row-eq-height">';
					$J = 1;
				}

				$salida.= $crlf.'<div class="col-sm-4 producto">';
				$salida.= $crlf.'	<div class="">';
				$salida.= $crlf.'		<div class="acciones-producto">';
				$salida.= $crlf.'			<p class="cantidad-favorito">'.$cantFav.'</p>';
				$salida.= $crlf.'			<a href="javascript:void(0);" class="favorito activo_"></a>';
				if ($fila["CantProd"] > 0) {
					$salida.= $crlf.'			<a href="javascript:void(0);" onclick="agregarProd('.$fila["NumeProd"].', 1)" class="carrito"><img src="./img/home/carrito.png" alt=""></a>';
				}
				$salida.= $crlf.'		</div>';
				$salida.= $crlf.'		<a href="producto/'.$fila["SlugProd"].'.php" class="img-producto"><img class="img-center" src="admin/'.$fila["RutaImag"].'" alt="" style="width: 150px; height: 219px;"></a>';
				$salida.= $crlf.'		<a href="producto/'.$fila["SlugProd"].'.php" class="titulo-producto">'.$fila["NombProd"].'</a>';
				
				$precio = $fila["ImpoVent"];
				if ($promociones->num_rows > 0) {
					$promociones->data_seek(0);

					while ($promo = $promociones->fetch_assoc()) {
						if ($promo["ValoFilt"] != '') {
							$blnFalse = false;

							switch ($promo["NumeTipoFilt"]) {
								case '1':
									$arProds = explode(",", $promo["ValoFilt"]);
									$blnPromo = (array_search($fila["NumeProd"], $arProds) !== false ? true : false);
									break;
								
								case '2':
									$arCates = explode(",", $promo["ValoFilt"]);

									for ($I = 0; $I < count($filtroCategorias); $I++) {
										$blnPromo = $blnPromo || (array_search($filtroCategorias[$I], $arCates) !== false ? true : false);
									}
									break;
							}

							if ($blnPromo === false) {
								continue;
							}
						}

						switch ($promo["NumeTipoProm"]) {
							case '1': //Porcentaje de descuento
								$precio = number_format($precio * (100 - $promo["ValoProm"]) / 100, 2);
								break;

							case '2': //Monto de descuento
								if ($precio < floatval($promo["ValoProm"])) {
									$precio = 0;
								}
								else {
									$precio = number_format($precio - $promo["ValoProm"], 2);
								}
								break;
						}
					}
				}
				if ($precio == $fila["ImpoVent"]) {
					$salida.= $crlf.'<p class="precio-producto">$ '. $fila["ImpoVent"] .'</p>';
				}
				else {
					$salida.= $crlf.'<p class="precio-producto">';
					$salida.= $crlf.'<s>$ '. $fila["ImpoVent"] .'</s>';
					$salida.= $crlf.'<strong style="color: red;"><i class="fa fa-fire" aria-hidden="true"></i>En Oferta</strong>';
					$salida.= $crlf.'<br>$ '. $precio .'</p>';
				}

				$salida.= $crlf.'	</div>';
				$salida.= $crlf.'</div>';

				$J++;
			}
			$salida.= $crlf.'</div>';
			echo $salida;
		}
	?>
	</div>
	<!-- /.PROMOS -->

	<?php include 'php/footer.php'; ?>
	</div>
	<!-- /#page-content-wrapper --> 

	<?php include 'php/sidebar.php'; ?>

</div>
	
	<?php include 'php/scripts-footer.php'; ?>

</body>
</html>