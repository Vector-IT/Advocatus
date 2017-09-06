<?php
	session_start();
	require_once 'php/conexion.php';

	$strSQL = "SELECT RutaImag FROM slidersimagenes WHERE NumeSlid = 1";
	$slider1 = cargarTabla($strSQL);
  
	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd";
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
				<div id="promos" class="carousel slide" data-ride="carousel">
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
			while ($fila = $novedades->fetch_assoc()) {
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
				$salida.= $crlf.'			<a href="javascript:void(0);" onclick="agregarProd('.$fila["NumeProd"].', 1)" class="carrito"><img src="./img/home/carrito.png" alt=""></a>';
				$salida.= $crlf.'		</div>';
				$salida.= $crlf.'		<a href="producto/'.$fila["SlugProd"].'.php" class="img-producto"><img class="img-center" src="admin/'.$fila["RutaImag"].'" alt="" style="width: 150px; height: 219px;"></a>';
				$salida.= $crlf.'		<a href="producto/'.$fila["SlugProd"].'.php" class="titulo-producto">'.$fila["NombProd"].'</a>';
				$salida.= $crlf.'		<p class="precio-producto">$ '.$fila["ImpoVent"].'</p>';
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

	<!-- Sidebar -->
	<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
	<ul class="nav sidebar-nav">
		<button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas"> <span class="hamb-top"></span> <span class="hamb-bottom"></span> </button>
		<h1>Carrito de Compras</h1>

		<div id="divCarrito"></div>

		<p class="subtotal">Subtotal: $ <span id="subtotal">0</span></p>
		<p class="bonificacion">Bonificación: <span id="bonificacion">0</span></p>
		<p class="total">Total: $ <span id="total">0</span></p>
		<a href="mi-carrito.php" class="btn-carrito-negro">Comprar</a>  
	</ul>
	</nav>
	<!-- /#sidebar-wrapper --> 

</div>
	
	<?php include 'php/scripts-footer.php'; ?>

</body>
</html>