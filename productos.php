<?php
	session_start();
	require_once 'php/conexion.php';

	$strSQL = "SELECT NumeCate, NombCate FROM categorias WHERE NumePadr IS NULL";
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

	<link rel="stylesheet" href="css/equal-height-columns.css">

	<script>
		function cargarCategoria(strID) {
			$.post("php/tablaHandler.php", { 
				operacion: "100",
				tabla: "productos",
				field: "NumeCate",
				data: strID
				},
				function (data) {
					if (data.valor === true) {
						$("#txtHint").html(nombProd + "<br>Nueva cantidad: " + cantProd);
						$("#divMsj").removeClass("alert-danger");
						$("#divMsj").addClass("alert-success");
					}
					else {
						$("#txtHint").html(nombProd + "<br>Error al actualizar cantidad.");
						$("#divMsj").addClass("alert-danger");
						$("#divMsj").removeClass("alert-success");
					}
					$("#divMsj").show();
				}
			);
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
							$salida.= $crlf.'<div class="row">';
							$salida.= $crlf.'	<div class="col-sm-6">';
							$salida.= $crlf.'		<nav class="breadcrumb">';
							$salida.= $crlf.'			<a class="breadcrumb-item active" href="javascript:void(0);">'.$fila["NombCate"].'</a>';
							$salida.= $crlf.'		</nav>';
							$salida.= $crlf.'	</div>';
							$salida.= $crlf.'	<div class="col-sm-6 text-right">';
							$salida.= $crlf.'		<div class="dropdown show" style="float: right; margin-left: 10px;">';
							$salida.= $crlf.'			<a class="btn btn-secondary dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
							$salida.= $crlf.'				Nombre';
							$salida.= $crlf.'			</a>';
							$salida.= $crlf.'			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
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