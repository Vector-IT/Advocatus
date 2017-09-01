<?php
	session_start();
	require_once 'php/conexion.php';

	$cantNovedades = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'CANTIDAD NOVEDADES HOME'");

	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."INNER JOIN productosnovedades pn ON p.NumeProd = pn.NumeProd";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."ORDER BY pn.NumeOrde";
	$strSQL.= $crlf."LIMIT ". $cantNovedades;
	$novedades = cargarTabla($strSQL);

	$cantPromociones = buscarDato("SELECT ValoConf FROM configuraciones WHERE NombConf = 'CANTIDAD PROMOCIONES HOME'");
	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."WHERE Promocion = 1";
	$strSQL.= $crlf."LIMIT ". $cantPromociones;
	$promociones = cargarTabla($strSQL);

	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."WHERE Destacado = 1";
	$destacados = cargarTabla($strSQL);

	$strSQL = "SELECT RutaImag FROM slidersimagenes WHERE NumeSlid = 1";
	$slider1 = cargarTabla($strSQL);

	$strSQL = "SELECT RutaImag FROM slidersimagenes WHERE NumeSlid = 2";
	$slider2 = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include 'php/links-header.php'; ?>

	<link rel="stylesheet" href="css/equal-height-columns.css">
	<link href="css/sidebar.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
		<div class="overlay"></div>
		<!-- Page Content -->
		<div id="page-content-wrapper">

	<?php include 'php/header.php'; ?>

      <!-- PORTADA -->
      <header class="sombra">
        <div class="container-full">
          <div class="row noMargin">
            <div class="col-lg-12  noPadding noMargin">
                <a class="descargar-catalogo pull-right" href="descargas/Catalogo General Advocatus 2017.pdf" target="_blank">Catálogo de Productos</a>
                <!-- SLIDER INTRO -->
                <div class="container-full noMargin">
                <div class="row noMargin">
                  <div class="col-sm-12 noPadding noMargin">
                  <div id="intro" class="carousel slide noPadding noMargin" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
					<?php
						$strSalida = "";
						$I = 0;
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
                    <a class="left carousel-control" href="#intro" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Anterior</span>
                    </a>
                    <a class="right carousel-control" href="#intro" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Siguiente</span>
                    </a>
                  </div>
                  </div>
                </div>
                </div>
                <!-- /.SLIDER INTRO -->
            </div>
          </div>
        </div>
      </header>
      <!-- /.PORTADA -->
	  
	<!-- TABS PRODUCTOS -->
	<div id="productos" class="container">
		<div class="row">
			<div class="col-sm-11">
				<div id="tabs-productos">
					<ul  class="nav nav-pills">
						<?php if ($novedades->num_rows > 0) {?>
						<li class="active"><a  href="#novedades" data-toggle="tab">Novedades</a></li>
						<?php }?>
						
						<!-- <li><a href="#packs" data-toggle="tab">Packs</a></li> -->

						<?php if ($promociones->num_rows > 0) {?>
						<li><a href="#promociones" data-toggle="tab">Promociones</a></li>
						<?php }?>
					</ul>
					<div class="tab-content clearfix">
						<?php if ($novedades->num_rows > 0) {?>
						<div class="tab-pane active" id="novedades">
							<?php 
								$J = 0;
								$salida = '';
								while ($fila = $novedades->fetch_assoc()) {
									$cantFav = buscarDato("SELECT COUNT(*) FROM usuariosfavoritos WHERE NumeProd = ". $fila["NumeProd"]);

									if ($J == 0) {
										$salida.= $crlf.'<div class="row row-eq-height">';
										$J = 1;
									}
									elseif ($J == 5) {
										$salida.= $crlf.'</div>';
										$salida.= $crlf.'<div class="row row-eq-height">';
										$J = 1;
									}
									
									$salida.= $crlf.'<div class="col-sm-3 producto">';
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
								echo $salida;
							?>
							</div>
							<!-- /.row -->
						</div>
						<?php }?>
						<?php if ($promociones->num_rows > 0) {?>
						<div class="tab-pane" id="promociones">
							<?php 
								$J = 0;
								$salida = '';
								while ($fila = $promociones->fetch_assoc()) {
									$cantFav = buscarDato("SELECT COUNT(*) FROM usuariosfavoritos WHERE NumeProd = ". $fila["NumeProd"]);

									if ($J == 0) {
										$salida.= $crlf.'<div class="row row-eq-height">';
										$J = 1;
									}
									elseif ($J == 5) {
										$salida.= $crlf.'</div>';
										$salida.= $crlf.'<div class="row row-eq-height">';
										$J = 1;
									}

									$salida.= $crlf.'<div class="col-sm-3 producto">';
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
								echo $salida;
							?>
							</div>
							<!-- /.row -->
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.TABS PRODUCTOS -->
	<!-- CONSULTAS & DESCARGAS -->
	<div id="consultanos" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-8 col-sm-offset-2 tel">
				<p>CONSULTANOS AL <span>0351 4461931</span></p>
			</div>
		</div>
	</div>
	</div>
	<!-- /.CONSULTAS & DESCARGAS -->
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
				while ($fila = $slider2->fetch_assoc()) {
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
				$slider2->data_seek(0);
				while ($fila = $slider2->fetch_assoc()) {
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
	<div id="productos-destacados" class="container-full">
	<div class="row noMargin">
		<div class="col-sm-2">
		</div>
		<div class="col-sm-8">
		<h2>Productos Destacados</h2>
		<p>Accedé a las últimas publicaciones de nuestra Editorial</p>
		</div>
		<div class="col-sm-2 noPadRight">
		<img class="img-center pull-right" src="./img/home/promo-semanal.png" alt="">
		</div>
	</div>
	</div>
	<div class="container">
	<div class="row">
	<?php while ($fila = $destacados->fetch_assoc()) {?>
		<div class="col-sm-4">
			<div class="box-circulo">
				<img class="img-center" src="admin/<?php echo $fila["RutaImag"]?>" alt="" style="width: 60%;">
				<a class="info" href="producto/<?php echo $fila["SlugProd"]?>.php">Más info</a>
			</div>
			<h2 class="titulo-destacados"><?php echo $fila["NombProd"]?></h2>
			<p class="precio-destacados">$ <?php echo $fila["ImpoVent"]?></p>
		</div>
	<?php }?>
	</div>
	<!-- /.row -->
	</div>
	<!-- /.DESTACADOS -->
	<!-- /SUSCRIBIRSE -->
	<div id="suscribirse" class="container">
	<div class="row">
		<div class="col-sm-2">
		</div>
		<div class="col-sm-8">
		<a href="#">SUSCRIBIRSE AL BOLETIN DE NOTICIAS</a>
		</div>
		<div class="col-sm-2"></div>
	</div>
	</div>
	<!-- /.SUSCRIBIRSE -->      

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