<?php
	session_start();
	require_once 'php/conexion.php';

	$strSQL = "SELECT NumeCate, NombCate FROM categorias WHERE NumePadr IS NULL";
	$categorias = cargarTabla($strSQL);

	if (isset($_SESSION["NumeCarr"])) {
		$cantCarrito = buscarDato("SELECT COUNT(*) FROM carritos WHERE NumeCarr = ". $_SESSION["NumeCarr"]);
	}
	else {
		$cantCarrito = 0;
	}

	$strFiltro = "";
	if (isset($_GET["tipo"])) {
		if ($_GET["tipo"] == "1") {
			$strFiltro.= "Destacado = 1";
		} elseif ($_GET["tipo"] == "2") {
			$strFiltro.= "Promocion = 1";
		}
	}

	if (isset($_GET["editorial"]) && $_GET["editorial"] != "") {
		if ($strFiltro != "") {
			$strFiltro.= $crlf. " AND ";
		}
		$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 81 AND Valor = '". $_GET["editorial"] ."')";
	}

	if (isset($_GET["categoria"]) && $_GET["categoria"] != "") {
		if ($strFiltro != "") {
			$strFiltro.= $crlf. " AND ";
		}
		$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productoscategorias WHERE NumeCate = ". $_GET["categoria"] .")";
	}
	
	if (isset($_GET["autor"]) && $_GET["autor"] != "") {
		if ($strFiltro != "") {
			$strFiltro.= $crlf. " AND ";
		}
		$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 139 AND Valor LIKE '%". $_GET["autor"] ."%')";
	}
	
	if (isset($_GET["fecha"]) && $_GET["fecha"] != "") {
		if ($strFiltro != "") {
			$strFiltro.= $crlf. " AND ";
		}
		$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 141 AND Valor LIKE '%". $_GET["fecha"] ."%')";
	}

	if (isset($_GET["texto"]) && $_GET["texto"] != "") {
		if ($strFiltro != "") {
			$strFiltro.= $crlf. " AND ";
		}
		$strFiltro.= "p.NombProd LIKE '%". $_GET["texto"] ."%'";
	}

	if (isset($_REQUEST["orden"])) {
		switch ($_REQUEST["orden"]) {
			case 'nombre':
				$orden = "p.NombProd";
				break;
			case 'precioa':
				$orden = "p.ImpoVent";
				break;
			case 'preciod':
				$orden = "p.ImpoVent DESC";
				break;
			case 'vistas':
				$orden = "p.Vistas DESC";
				break;
			case 'ventas':
				$orden = "p.NombProd DESC";
				break;

			default:
				$orden = "p.NombProd";
				break;
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include 'php/links-header.php'; ?>

	<link rel="stylesheet" href="css/equal-height-columns.css">

	<script>
		function ordenar() {

		}
	</script>
</head>
<body>
	<?php include 'php/header.php'; ?>
	<!-- TABS GRID PRODUCTOS -->
	<div id="grid-productos" class="container-full">
		<div class="row noMargin">
			<div class="col-sm-12">
				<h1>Libreria Online</h1>
				<a href="mi-carrito.php" class="cantidad-productos-carrito"><img src="./img/home/carrito.png" alt=""> (<span><?php echo $cantCarrito?></span>)</a>
				<div id="tabs-grid-productos">
					<ul  class="nav nav-pills">
					<?php
						$I = 0;
						if ($strFiltro == "") {
							while ($fila = $categorias->fetch_assoc()) {
								if ($I == 0) {
									echo '<li class="active"><a href="#tab'.$fila["NumeCate"].'" data-toggle="tab">'.$fila["NombCate"].'</a></li>';	
								} else {
									echo '<li><a href="#tab'.$fila["NumeCate"].'" data-toggle="tab">'.$fila["NombCate"].'</a></li>';	
								}
								
								$I++;
							}
							$categorias->data_seek(0);
						}
						else {
							echo '<li class="active"><a href="#tabResultados" data-toggle="tab">Resultados</a></li>';	
						}
					?>
					</ul>
					<div class="tab-content clearfix">
					<?php
						$salida = '';
						if ($strFiltro == "") {
							$I = 0;
							
							while ($fila = $categorias->fetch_assoc()) {
								if ($I == 0) {
									$salida.= $crlf.'<div class="tab-pane active" id="tab'.$fila["NumeCate"].'">';
								} else {
									$salida.= $crlf.'<div class="tab-pane" id="tab'.$fila["NumeCate"].'">';
								}
								$salida.= $crlf.'<div class="row">';
								$salida.= $crlf.'	<div class="col-sm-6">';
								$salida.= $crlf.'		<nav class="breadcrumb">';
								$salida.= $crlf.'			<a class="breadcrumb-item active" href="javascript:void(0);">'.$fila["NombCate"].'</a>';
								$salida.= $crlf.'		</nav>';
								$salida.= $crlf.'	</div>';
								$salida.= $crlf.'	<div class="col-sm-6 text-right">';
								$salida.= $crlf.'		<div class="dropdown show" style="float: right; margin-left: 10px;">';
								$salida.= $crlf.'			<a class="btn btn-secondary dropdown-toggle" href="javascript:void(0);" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
								$salida.= $crlf.'				Nombre';
								$salida.= $crlf.'			</a>';
								$salida.= $crlf.'			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">';
								$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="ordenar(1)">Nombre</a></li>';
								$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="ordenar(2)">Menor precio</a></li>';
								$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="ordenar(3)">Mayor precio</a></li>';
								$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="ordenar(4)">Más visto</a></li>';
								$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="ordenar(5)">Más vendido</a></li>';
								$salida.= $crlf.'			</ul>';
								$salida.= $crlf.'		</div>';
								$salida.= $crlf.'		<span class="filtro">Ordenar por</span>';
								$salida.= $crlf.'	</div>';
								$salida.= $crlf.'</div>';


								$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd";
								$strSQL.= $crlf."FROM productos p";
								$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
								$strSQL.= $crlf."WHERE p.NumeEsta = 1";
								$strSQL.= $crlf."AND p.NumeProd IN (SELECT NumeProd FROM productoscategorias WHERE NumeCate = {$fila["NumeCate"]})";
								$productos = cargarTabla($strSQL);

								if ($productos->num_rows > 0) {
									$I = 0;
									while ($prod = $productos->fetch_assoc()) {
										if ($I == 0) {
											$salida.= $crlf.'<div class="row row-eq-height">';
											$I = 1;
										}
										elseif ($I == 5) {
											$salida.= $crlf.'</div>';
											$salida.= $crlf.'<div class="row row-eq-height">';
											$I = 1;
										}
										$salida.= $crlf.'<div class="col-sm-3">';
										$salida.= $crlf.'	<div class="producto">';
										$salida.= $crlf.'		<a href="producto/'.$prod["SlugProd"].'.php" class="img-producto"><img class="img-center" src="admin/'.$prod["RutaImag"].'" alt="" style="height: 215px;"></a>';
										$salida.= $crlf.'		<a class="titulo-producto">';
										$salida.= $crlf.'			'.$prod["NombProd"].'<br>';
										$salida.= $crlf.'			<p class="precio-producto">$ '.$prod["ImpoVent"].'</p>';
										$salida.= $crlf.'		</a>';
										$salida.= $crlf.'	</div>';
										$salida.= $crlf.'</div>';

										$I++;
									}
									$salida.= $crlf.'</div>';
								}
								else {
									$salida.= $crlf.'<div class="row row-eq-height">';
									$salida.= $crlf.'<h4>No hay productos</h4>';	
									$salida.= $crlf.'</div>';
								}
								
								$salida.= $crlf.'</div>';

								$I++;
							}
						}
						else {
							$salida.= $crlf.'<div class="tab-pane active" id="tabResultados">';
							$salida.= $crlf.'<div class="row">';
							$salida.= $crlf.'	<div class="col-sm-6 col-sm-offset-6 text-right">';
							$salida.= $crlf.'		<div class="dropdown show" style="float: right; margin-left: 10px;">';
							$salida.= $crlf.'			<a class="btn btn-secondary dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
							$salida.= $crlf.'				Nombre';
							$salida.= $crlf.'			</a>';
							$salida.= $crlf.'			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">';
							$salida.= $crlf.'				<a class="dropdown-item" href="#">Nombre</a><br>';
							$salida.= $crlf.'				<a class="dropdown-item" href="#">Menor precio</a><br>';
							$salida.= $crlf.'				<a class="dropdown-item" href="#">Mayor precio</a><br>';
							$salida.= $crlf.'				<a class="dropdown-item" href="#">Más visto</a><br>';
							$salida.= $crlf.'				<a class="dropdown-item" href="#">Más vendido</a>';
							$salida.= $crlf.'			</div>';
							$salida.= $crlf.'		</div>';
							$salida.= $crlf.'		<span class="filtro">Ordenar por</span>';
							$salida.= $crlf.'	</div>';
							$salida.= $crlf.'</div>';


							$strSQL = "SELECT p.NumeProd, p.NombProd, p.ImpoVent, pi.RutaImag, p.SlugProd";
							$strSQL.= $crlf."FROM productos p";
							$strSQL.= $crlf."LEFT JOIN productosimagenes pi ON p.NumeProd = pi.NumeProd AND pi.NumeOrde = 1";
							$strSQL.= $crlf."WHERE p.NumeEsta = 1";
							$strSQL.= $crlf."AND ". $strFiltro;
							$productos = cargarTabla($strSQL);

							if ($productos->num_rows > 0) {
								$I = 0;
								while ($prod = $productos->fetch_assoc()) {
									if ($I == 0) {
										$salida.= $crlf.'<div class="row row-eq-height">';
										$I = 1;
									}
									elseif ($I == 5) {
										$salida.= $crlf.'</div>';
										$salida.= $crlf.'<div class="row row-eq-height">';
										$I = 1;
									}
									$salida.= $crlf.'<div class="col-sm-3">';
									$salida.= $crlf.'	<div class="producto">';
									$salida.= $crlf.'		<a href="producto/'.$prod["SlugProd"].'.php" class="img-producto"><img class="img-center" src="admin/'.$prod["RutaImag"].'" alt="" style="height: 215px;"></a>';
									$salida.= $crlf.'		<a class="titulo-producto">';
									$salida.= $crlf.'			'.$prod["NombProd"].'<br>';
									$salida.= $crlf.'			<p class="precio-producto">$ '.$prod["ImpoVent"].'</p>';
									$salida.= $crlf.'		</a>';
									$salida.= $crlf.'	</div>';
									$salida.= $crlf.'</div>';

									$I++;
								}
								$salida.= $crlf.'</div>';
							}
							else {
								$salida.= $crlf.'<div class="row row-eq-height">';
								$salida.= $crlf.'<h4>No hay productos</h4>';	
								$salida.= $crlf.'</div>';
							}
							
							$salida.= $crlf.'</div>';
						}
						echo $salida;
					?>
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