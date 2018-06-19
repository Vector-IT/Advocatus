<?php
	session_start();
	require_once 'php/conexion.php';

	if (!isset($_REQUEST["slug"])) {
		header("Location: ". $raiz);
		die();
	}

	//Anexo
	$strSQL = "SELECT NumeAnex, Titulo, Subtitulo, Descripcion, ImagAnex";
	$strSQL.= $crlf."FROM anexos";
	$strSQL.= $crlf."WHERE Slug = '{$_REQUEST["slug"]}'";
	$anexo = buscarDato($strSQL);
	if ($anexo == '') {
		header("Location: ". $raiz);
		die();
    }
    $numeAnex = $anexo["NumeAnex"];

	//Archivos
	$strSQL = "SELECT NombFile, RutaFile";
	$strSQL.= $crlf."FROM anexosarchivos";
	$strSQL.= $crlf."WHERE NumeAnex = ". $numeAnex;
	$archivos = cargarTabla($strSQL);
?>
<!doctype html>
<html>
<head>
	<?php include 'php/links-header.php'; ?>

	<!-- Custom CSS -->
	<link href="css/sidebar.css" rel="stylesheet">

</head>
<body>
	<div id="wrapper">
		<div class="overlay"></div>
		<!-- Page Content -->
		<div id="page-content-wrapper">

			<?php include 'php/header.php'; ?>

			<!-- CONTENIDO -->
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<img alt="" title="" style="widtH: 100%; height: auto;" src="admin/<?php echo $anexo["ImagAnex"]?>">
					</div>
					<div class="col-sm-6">
						<div class="info-producto">
                            <h1><?php echo $anexo["Titulo"]?></h1>

                            <h2>Detalles</h2>
                            <p>
                                <?php echo $anexo["Descripcion"]?>
                            </p>
                            <h2>Descargas</h2>
                            <?php
                                if (!isset($_SESSION['NumeUser'])) {
                                    echo '<p><strong>Tienes que estar logueado para poder descargar los archivos.</strong></p>';
                                }
                                $salida = '';
                                while ($fila = $archivos->fetch_assoc()) {
                                    $valor = '<a href="admin/'. $fila["RutaFile"] .'" target="_blank">DESCARGA</a>';
                                    
                                    if ($valor != "") {
                                        $salida.= $crlf.'<p><span class="ucase">'.$fila["NombFile"].':</span> ';
                                        if (isset($_SESSION['NumeUser'])) {
                                            $salida.= $valor;
                                        }
                                        $salida.= '</p>';
                                    }
                                }

                                echo $salida;
                            ?>	
						</div>
					</div>
				</div>
			</div>
			<!-- /.CONTENIDO -->

			<?php include 'php/footer.php'; ?>

		</div>
		 <!-- /#page-content-wrapper --> 

		 <?php include 'php/sidebar.php'; ?>
	  </div>
	  <!-- /#wrapper --> 

	<?php include 'php/scripts-footer.php'; ?>
</body>
</html>