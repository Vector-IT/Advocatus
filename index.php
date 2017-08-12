<?php
	require_once 'php/conexion.php';

	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."WHERE Novedad = 1";
	$novedades = cargarTabla($strSQL);

	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."WHERE Promocion = 1";
	$promociones = cargarTabla($strSQL);

	$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag";
	$strSQL.= $crlf."FROM productos p";
	$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
	$strSQL.= $crlf."WHERE Destacado = 1";
	$destacados = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include 'php/links-header.php'; ?>

</head>
<body>

	<?php include 'php/header.php'; ?>

	<!-- PORTADA -->
	<header class="portada home">
	<div class="container-full">
		<div class="row noMargin">
		<div class="col-lg-12">
			<a class="descargar-catalogo pull-right" href="#">Catálogo de Productos</a>
		</div>
		</div>
	</div>
	</header>
	<!-- /.PORTADA -->
	<!-- Buscador -->
	<div id="buscador-portada">
		<div class="row noMargin">
			<div class="col-md-3">
				<img style="padding-top: 20px;" class="img-responsive img-center" src="./img/home/logo-buscador.jpg" alt="">
			</div>
			<div class="buscador col-md-8">
				<div class="input-group" id="adv-search">
					<input type="text" class="form-control busqueda" placeholder="" />
					<div class="input-group-btn">
						<div class="btn-group" role="group">
							<div class="dropdown dropdown-lg">
								<button type="submit" class="btn btn-primary">Buscar</button>
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span>Búsqueda Avanzada</span></button>
								<div class="dropdown-menu dropdown-menu-right" role="menu">
                    <form class="form-horizontal" role="form">
                      <div class="form-group">
                          <label for="filter">Filtrar por</label>
                          <select class="form-control">
                            <option value="0" selected>Todos</option>
                            <option value="1">Destacados</option>
                            <option value="2">Promociones</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="filter">Filtrar por Editorial</label>
                          <select class="form-control">
                            <option value="0" selected>Advocatus Ediciones</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="filter">Filtrar por Categoría</label>
                          <select class="form-control">
                            <option value="0" selected>Catálogo</option>
                            <option value="1">Derecho</option>
                            <option value="2">Administración, Contabilidad y Economía</option>
                            <option value="3">Práctica Profesional</option>
                            <option value="4">Jurisprudencia</option>
                            <option value="5">Técnicos, Arquitectura e Ingeniería</option>
                            <option value="6">Códigos y Leyes</option>
                            <option value="7">Otros Productos</option>
                            <option value="8">Lectura General</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="contain">Autor</label>
                          <input class="form-control" type="text" />
                        </div>
                        <div class="form-group">
                          <label for="contain">Año</label>
                          <input class="form-control" type="text" />
                        </div>
                        <div class="form-group">
                          <label for="contain">Contiene las palabras</label>
                        <input class="form-control" type="text" />
                        </div>
                      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-1"></div>
		</div>
	</div>
	<!-- /.Buscador -->
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
							<div class="row">
							<?php 
								while ($fila = $novedades->fetch_assoc()) {
									$cantFav = buscarDato("SELECT COUNT(*) FROM usuariosfavoritos WHERE NumeProd = ". $fila["NumeProd"]);
							?>
								<div class="col-sm-4">
									<div class="producto">
										<div class="acciones-producto">
											<p class="cantidad-favorito"><?php echo $cantFav?></p>
											<a href="#" class="favorito activo_"></a>
											<a href="#" class="carrito"><img src="./img/home/carrito.png" alt=""></a>
										</div>
										<a href="item-productos.php?NumeProd=<?php echo $fila["NumeProd"]?>" class="img-producto"><img class="img-center" src="admin/<?php echo $fila["RutaImag"]?>" alt="" style="width: 150px; height: 219px;"></a>
										<a class="titulo-producto"><?php echo $fila["NombProd"]?></a>
										<p class="precio-producto">$ <?php echo $fila["ImpoVent"]?></p>
									</div>
								</div>
							<?php }?>
							</div>
							<!-- /.row -->
						</div>
						<?php }?>
						<!-- <div class="tab-pane" id="packs">
							<div class="row">
								<div class="col-sm-4">
									<div class="producto">
									<div class="acciones-producto">
										<p class="cantidad-favorito">85</p>
										<a href="#" class="favorito activo"></a>
										<a href="#" class="carrito"><img src="./img/home/carrito.png" alt=""></a>
									</div>
									<a href="item-productos.php?NumeProd=<?php echo $fila["NumeProd"]?>" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
									<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
									<p class="precio-producto">$ 579</p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="producto">
									<div class="acciones-producto">
										<p class="cantidad-favorito">85</p>
										<a href="#" class="favorito"></a>
										<a href="#" class="carrito"><img src="./img/home/carrito.png" alt=""></a>
									</div>
									<a href="item-productos.php?NumeProd=<?php echo $fila["NumeProd"]?>" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
									<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
									<p class="precio-producto">$ 579</p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="producto">
									<div class="acciones-producto">
										<p class="cantidad-favorito">85</p>
										<a href="#" class="favorito  activo"></a>
										<a href="#" class="carrito"><img src="./img/home/carrito.png" alt=""></a>
									</div>
									<a href="item-productos.php?NumeProd=<?php echo $fila["NumeProd"]?>" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
									<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
									<p class="precio-producto">$ 579</p>
									</div>
								</div>
							</div>
						</div> -->
						<?php if ($promociones->num_rows > 0) {?>
						<div class="tab-pane" id="promociones">
							<div class="row">
							<?php 
								while ($fila = $promociones->fetch_assoc()) {
									$cantFav = buscarDato("SELECT COUNT(*) FROM productosfavoritos WHERE NumeProd = ". $fila["NumeProd"]);
							?>
								<div class="col-sm-4">
									<div class="producto">
										<div class="acciones-producto">
											<p class="cantidad-favorito"><?php echo $cantFav?></p>
											<a href="#" class="favorito activo_"></a>
											<a href="#" class="carrito"><img src="./img/home/carrito.png" alt=""></a>
										</div>
										<a href="item-productos.php?NumeProd=<?php echo $fila["NumeProd"]?>" class="img-producto"><img class="img-center" src="admin/<?php echo $fila["RutaImag"]?>" alt="" style="width: 150px; height: 219px;"></a>
										<a class="titulo-producto"><?php echo $fila["NombProd"]?></a>
										<p class="precio-producto">$ <?php echo $fila["ImpoVent"]?></p>
									</div>
								</div>
							<?php  }?>
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
			<li data-target="#promos" data-slide-to="0" class="active"></li>
			<li data-target="#promos" data-slide-to="1"></li>
			<li data-target="#promos" data-slide-to="2"></li>
			</ol>
			<!-- Wrapper for slides -->
			<div class="carousel-inner">
			<div class="item active">
				<img src="./img/home/banner-1.jpg" alt="">
			</div>
			<div class="item">
				<img src="./img/home/banner-1.jpg" alt="">
			</div>
			<div class="item">
				<img src="./img/home/banner-1.jpg" alt="">
			</div>
			</div>
			<!-- Left and right controls -->
			<a class="left carousel-control" href="#myCarousel" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
			<span class="sr-only">Anterior</span>
			</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next">
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
				<a class="info" href="item-productos.php?NumeProd=<?php echo $fila["NumeProd"]?>">Más info</a>
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
	<?php include 'php/scripts-footer.php'; ?>

</body>
</html>