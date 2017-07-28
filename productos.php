<?php
	require_once 'php/conexion.php';

	$strSQL = "SELECT NumeCate, NombCate FROM categorias WHERE NiveCate = 3";
	$categorias = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Productos - ADVOCATUS | Editorial · Librería</title>
	<?php include 'php/links-header.php'; ?>
</head>
<body>
	<?php include 'php/header.php'; ?>
	<!-- TABS GRID PRODUCTOS -->
	<div id="grid-productos" class="container-full">
		<div class="row noMargin">
			<div class="col-sm-12">
				<h1>Libreria Online</h1>
				<a href="#" class="cantidad-productos-carrito"><img src="./img/home/carrito.png" alt=""> (<span>2</span>)</a>
				<div id="tabs-grid-productos">
					<ul  class="nav nav-pills">
					<?php
						$I = 0;
						while ($fila = $categorias->fetch_assoc()) {
							if ($I == 0) {
								echo '<li class="active"><a href="#tab'.$fila["NumeCate"].'" data-toggle="tab">'.$fila["NombCate"].'</a></li>';	
							} else {
								echo '<li><a href="#tab'.$fila["NumeCate"].'" data-toggle="tab">'.$fila["NombCate"].'</a></li>';	
							}
							
							$I++;
						}
						$categorias->data_seek(0);
					?>
						<!-- <li><a href="#lectura-general" data-toggle="tab">Lectura General</a></li>
						<li><a href="#derecho" data-toggle="tab">Derecho</a></li>
						<li><a href="#adm-eco-cont" data-toggle="tab">Administración, Contabilidad y Economía</a></li>
						<li><a href="#practica-profesional" data-toggle="tab">Práctica Profesional</a></li>
						<li><a href="#jurisprudencia" data-toggle="tab">Jurisprudencia</a></li>
						<li><a href="#tec-arq-ing" data-toggle="tab">Técnicos, Arquitectura e Ingeniería</a></li> -->
					</ul>
					<div class="tab-content clearfix">
					<?php
						$I = 0;
						$salida = '';
						while ($fila = $categorias->fetch_assoc()) {
							if ($I == 0) {
								$salida.= $crlf.'<div class="tab-pane active" id="tab'.$fila["NumeCate"].'">';
							} else {
								$salida.= $crlf.'<div class="tab-pane" id="tab'.$fila["NumeCate"].'">';
							}
							$salida.= $crlf.'<div class="col-sm-6">';
							$salida.= $crlf.'	<nav class="breadcrumb">';
							$salida.= $crlf.'		<a class="breadcrumb-item active" href="#">'.$fila["NombCate"].'</a>';
							//$salida.= $crlf.'		<span class="breadcrumb-item active">Derecho Penal</span>';
							$salida.= $crlf.'	</nav>';
							$salida.= $crlf.'</div>';
							$salida.= $crlf.'<div class="col-sm-6 text-right">';
							$salida.= $crlf.'	<div class="dropdown show" style="float: right; margin-left: 10px;">';
							$salida.= $crlf.'		<a class="btn btn-secondary dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
							$salida.= $crlf.'			Nombre';
							$salida.= $crlf.'		</a>';
							$salida.= $crlf.'		<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
							$salida.= $crlf.'			<a class="dropdown-item" href="#">Nombre</a><br>';
							$salida.= $crlf.'			<a class="dropdown-item" href="#">Menor precio</a><br>';
							$salida.= $crlf.'			<a class="dropdown-item" href="#">Mayor precio</a><br>';
							$salida.= $crlf.'			<a class="dropdown-item" href="#">Más visto</a><br>';
							$salida.= $crlf.'			<a class="dropdown-item" href="#">Más vendido</a>';
							$salida.= $crlf.'		</div>';
							$salida.= $crlf.'	</div>';
							$salida.= $crlf.'	<span class="filtro">Ordenar por</span>';

							// $salida.= $crlf.'	<div class="col-sm-5">';
							// $salida.= $crlf.'		<p class="filtro">Ordenar por</p>';
							// $salida.= $crlf.'	</div>';
							// $salida.= $crlf.'	<div id="filtros1" class="col-sm-7">';
							// $salida.= $crlf.'		<div class="dropdown show">';
							// $salida.= $crlf.'			<a class="btn btn-secondary dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
							// $salida.= $crlf.'			Nombre';
							// $salida.= $crlf.'			</a>';
							// $salida.= $crlf.'			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
							// $salida.= $crlf.'				<a class="dropdown-item" href="#">Nombre</a>';
							// $salida.= $crlf.'				<a class="dropdown-item" href="#">Menor precio</a>';
							// $salida.= $crlf.'				<a class="dropdown-item" href="#">Mayor precio</a>';
							// $salida.= $crlf.'				<a class="dropdown-item" href="#">Más visto</a>';
							// $salida.= $crlf.'				<a class="dropdown-item" href="#">Más vendido</a>';
							// $salida.= $crlf.'			</div>';
							// $salida.= $crlf.'		</div>';
							// $salida.= $crlf.'	</div>';
							$salida.= $crlf.'</div>';
							$salida.= $crlf.'<div class="row">';


							$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag";
							$strSQL.= $crlf."FROM productos p";
							$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
							$strSQL.= $crlf."WHERE NumeEsta = 1";
							$productos = cargarTabla($strSQL);

							while ($prod = $productos->fetch_assoc()) {
								$salida.= $crlf.'<div class="col-sm-3">';
								$salida.= $crlf.'	<div class="producto">';
								$salida.= $crlf.'		<a href="item-productos.php?NumeProd='.$prod["NumeProd"].'" class="img-producto"><img class="img-center" src="admin/'.$prod["RutaImag"].'" alt="" style="height: 215px;"></a>';
								$salida.= $crlf.'		<a class="titulo-producto">';
								$salida.= $crlf.'			'.$prod["NombProd"].'<br>';
								$salida.= $crlf.'			<p class="precio-producto">$ '.$prod["ImpoVent"].'</p>';
								$salida.= $crlf.'		</a>';
								$salida.= $crlf.'	</div>';
								$salida.= $crlf.'</div>';
							}
							$salida.= $crlf.'</div>';
							$salida.= $crlf.'</div>';

							$I++;
						}
						echo $salida;
					?>
						<!-- <div class="tab-pane active" id="derecho">
							<div class="col-sm-6">
								<nav class="breadcrumb">
									<a class="breadcrumb-item" href="#">Derecho</a>
									<span class="breadcrumb-item active">Derecho Penal</span>
								</nav>
							</div>
							<div class="col-sm-6 text-right">
								<div class="col-sm-5">
									<p class="filtro">Ordenar por</p>
								</div>
								<div id="filtros1" class="col-sm-7">
									<div class="dropdown show">
										<a class="btn btn-secondary dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Derecho Penal
										</a>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
											<a class="dropdown-item" href="#">Derecho Penal</a>
											<a class="dropdown-item" href="#">Derecho Penal</a>
											<a class="dropdown-item" href="#">Derecho Penal</a>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
										<p class="tag-nuevo inactivo">Nuevo</p>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
										<p class="tag-nuevo inactivo">Nuevo</p>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="producto">
										<a href="item-productos.html" class="img-producto"><img class="img-center" src="./img/productos/producto.jpg" alt=""></a>
										<a class="titulo-producto">Delitos contra la propiedad:hurto, abigeato, robo</a>
										<p class="descripcion-producto">• DOCTRINA Y JURISPRUDENCIA VIGENTES<br/>
											• REFERENCIAS AL ANTEPROYECTO
										</p>
										<p class="precio-producto">$ 579</p>
										<p class="tag-nuevo inactivo">Nuevo</p>
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.TABS GRID PRODUCTOS -->
	<?php include 'php/footer.php'; ?>
	<?php include 'php/scripts-footer.php'; ?>
</body>
</html>