<?php
	session_start();
	require_once 'php/conexion.php';

	$strSQL = "SELECT NumeCate, NombCate FROM categorias WHERE NumePadr IS NULL ORDER BY NombCate";
	$categorias = cargarTabla($strSQL);

	$strFiltro = "";
	$cate = 0;
	$subcat = "";
	$subcatTxt["NombCate"] = "TODAS LAS SUBCATEGORIAS";

	if (count($_GET) > 0) {
		if ($_GET["buscar"] == "1") {
			
			if (isset($_GET["tipo"])) {
				if ($_GET["tipo"] == "1") {
					$strFiltro.= "Destacado = 1";
				} elseif ($_GET["tipo"] == "2") {
					$strFiltro.= "Promocion = 1";
				}
			}

			if ($_GET["editorial"] != "") {
				if ($strFiltro != "") {
					$strFiltro.= $crlf. " AND ";
				}
				$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 81 AND Valor = '". $_GET["editorial"] ."')";
			}

			if ($_GET["categoria"] != "") {
				if (!isset($_GET["subcat"]) || $_GET["subcat"] == "") {
					if ($strFiltro != "") {
						$strFiltro.= $crlf. " AND ";
					}
					$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productoscategorias WHERE NumeCate = ". $_GET["categoria"] .")";
				}
			}
			
			if ($_GET["autor"] != "") {
				if ($strFiltro != "") {
					$strFiltro.= $crlf. " AND ";
				}
				$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 139 AND Valor LIKE '%". $_GET["autor"] ."%')";
			}
			
			if ($_GET["fecha"] != "") {
				if ($strFiltro != "") {
					$strFiltro.= $crlf. " AND ";
				}
				$strFiltro.= "p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 141 AND Valor LIKE '%". $_GET["fecha"] ."%')";
			}

			if ($_GET["texto"] != "") {
				if ($strFiltro != "") {
					$strFiltro.= $crlf. " AND ";
				}
				$strFiltro.= "(p.NombProd LIKE '%". $_GET["texto"] ."%'";
				$strFiltro.= " OR p.DescProd LIKE '%". $_GET["texto"] ."%'";
				$strFiltro.= "OR p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 139 AND Valor LIKE '%". $_GET["texto"] ."%')";
				$strFiltro.= "OR p.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE NumeAtri = 81 AND Valor = '". $_GET["texto"] ."')";
				$strFiltro.= ")";
			}
		}
		else {
			$cate = intval($_GET["cate"]);
			
			//Subcategorias
			if (isset($_GET["subcat"]) && $_GET["subcat"] != "") {
				$subcat = $_GET["subcat"];
				$subcatTxt = buscarDato("SELECT NombCate, NumePadr FROM categorias WHERE NumeCate = ". $subcat);
			}
		}
	}

	$strOrden = "p.NombProd";
	$ordenTxt = "NOMBRE";

	if (isset($_REQUEST["orden"])) {
		$orden = $_REQUEST["orden"];

		switch ($_REQUEST["orden"]) {
			case '1':
				$strOrden = "p.NombProd";
				$ordenTxt = "NOMBRE";
				break;
			case '2':
				$strOrden = "p.ImpoVent";
				$ordenTxt = "MENOR PRECIO";
				break;
			case '3':
				$strOrden = "p.ImpoVent DESC";
				$ordenTxt = "MAYOR PRECIO";
				break;
			case '4':
				$strOrden = "p.Vistas DESC";
				$ordenTxt = "MAS VISTO";
				break;
			case '5':
				$strOrden = "p.NombProd DESC";
				$ordenTxt = "MAS VENDIDO";
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
		function ordenar(valor) {
			$("#bsqOrden").val(valor);
			$("#bsqCate").val($("#nav-categorias li.active").index());
			$("#bsqBuscar").val('<?php echo (isset($_REQUEST["buscar"])? $_REQUEST["buscar"]: '1')?>');

			$("#frmBusqueda").submit();
		}

		function categoria(valor) {
			$("#bsqCate").val(valor);
		}

		function subcategoria(valor) {
			$("#bsqCate").val($("#nav-categorias li.active").index());
			$("#bsqSubcat").val(valor);
			$("#bsqBuscar").val('2');

			$("#frmBusqueda").submit()
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
					<ul  class="nav nav-pills" id="nav-categorias">
					<?php
						$I = 0;
						if ($strFiltro == "") {
							while ($fila = $categorias->fetch_assoc()) {
								if ($I == $cate) {
									echo '<li id="liCate'.$fila["NumeCate"].'" class="active"><a href="#tab'.$fila["NumeCate"].'" data-toggle="tab" onclick="categoria('.$I.')">'.$fila["NombCate"].'</a></li>';	
								} else {
									echo '<li id="liCate'.$fila["NumeCate"].'"><a href="#tab'.$fila["NumeCate"].'" data-toggle="tab" onclick="categoria('.$I.')">'.$fila["NombCate"].'</a></li>';	
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
								if ($I == $cate) {
									$salida.= $crlf.'<div class="tab-pane active" id="tab'.$fila["NumeCate"].'">';
								} else {
									$salida.= $crlf.'<div class="tab-pane" id="tab'.$fila["NumeCate"].'">';
								}
								$salida.= $crlf.'<div class="row">';
								$salida.= $crlf.'	<div class="col-sm-6">';
								$salida.= $crlf.'		<div class="dropdown">';
								$salida.= $crlf.'			<a class="btn btn-secondary dropdown-toggle" id="dropdownSubcategorias" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
								if ($I == $cate) {
									$salida.= $crlf.'				'.$subcatTxt["NombCate"];
								}
								else {
									$salida.= $crlf.'				TODAS LAS SUBCATEGORIAS';
								}

								$salida.= $crlf.'			</a>';
								$salida.= $crlf.'			<ul class="dropdown-menu" aria-labelledby="dropdownSubcategorias">';
								$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="subcategoria(\'\')">TODAS LAS SUBCATEGORIAS</a></li>';
								
								$subcategorias = cargarTabla("SELECT NumeCate, NombCate FROM categorias WHERE NumePadr = ". $fila["NumeCate"]);
								if ($subcategorias->num_rows > 0) {
									while ($subc = $subcategorias->fetch_assoc()) {
										$salida.= $crlf.'				<li><a href="javascript:void(0);" onclick="subcategoria('.$subc["NumeCate"].')">'.$subc["NombCate"].'</a></li>';
									}
								}
								$salida.= $crlf.'			</ul>';
								$salida.= $crlf.'		</div>';
								$salida.= $crlf.'	</div>';

								$salida.= $crlf.'	<div class="col-sm-6 text-right">';
								$salida.= $crlf.'		<div class="dropdown show" style="float: right; margin-left: 10px;">';
								$salida.= $crlf.'			<a class="btn btn-secondary dropdown-toggle" href="javascript:void(0);" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
								$salida.= $crlf.'				'.$ordenTxt;
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
								if ($I == $cate) {
									if ($subcat == "") {
										$strSQL.= $crlf."AND p.NumeProd IN (SELECT NumeProd FROM productoscategorias WHERE NumeCate = {$fila["NumeCate"]})";
									}
									else {
										$strSQL.= $crlf."AND p.NumeProd IN (SELECT NumeProd FROM productoscategorias WHERE NumeCate = {$subcat})";
									}
								}
								else {
									$strSQL.= $crlf."AND p.NumeProd IN (SELECT NumeProd FROM productoscategorias WHERE NumeCate = {$fila["NumeCate"]})";
								}

								$strSQL.= $crlf."ORDER BY ". $strOrden;
								$productos = cargarTabla($strSQL);

								if ($productos->num_rows > 0) {
									$J = 0;
									while ($prod = $productos->fetch_assoc()) {
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
										$salida.= $crlf.'		<a href="producto/'.$prod["SlugProd"].'.php" class="img-producto"><img class="img-center" src="admin/'.$prod["RutaImag"].'" alt="" style="height: 215px;"></a>';
										$salida.= $crlf.'		<a href="producto/'.$prod["SlugProd"].'.php" class="titulo-producto">';
										$salida.= $crlf.'			'.$prod["NombProd"].'<br>';
										$salida.= $crlf.'			<p class="precio-producto">$ '.$prod["ImpoVent"].'</p>';
										$salida.= $crlf.'		</a>';
										$salida.= $crlf.'	</div>';
										$salida.= $crlf.'</div>';

										$J++;
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
							$salida.= $crlf.'				'.$ordenTxt;
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
							$strSQL.= $crlf."AND ". $strFiltro;
							$strSQL.= $crlf."ORDER BY ". $strOrden;
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
									$salida.= $crlf.'<div class="col-sm-3 producto">';
									$salida.= $crlf.'	<div class="">';
									$salida.= $crlf.'		<a href="producto/'.$prod["SlugProd"].'.php" class="img-producto"><img class="img-center" src="admin/'.$prod["RutaImag"].'" alt="" style="height: 215px;"></a>';
									$salida.= $crlf.'		<a href="producto/'.$prod["SlugProd"].'.php" class="titulo-producto">';
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