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
	$strSQL = "SELECT CodiIden, NombFile, RutaFile";
	$strSQL.= $crlf."FROM anexosarchivos";
	$strSQL.= $crlf."WHERE NumeAnex = ". $numeAnex;
	$strSQL.= $crlf."AND NumePadr IS NULL";
	$strSQL.= $crlf."ORDER BY NumeOrde";
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
					<div class="col-sm-4">
						<img style="width: 100%; height: auto;" src="admin/<?php echo $anexo["ImagAnex"]?>">
					</div>
					<div class="col-sm-8">
						<div class="info-anexo">
							<h1 style="margin-top: 0;"><?php echo $anexo["Titulo"]?></h1>
							<p><?php echo $anexo["Subtitulo"]?></p>

							<h2>Información Adicional</h2>
                            <p><?php echo $anexo["Descripcion"]?></p>

                            <h2>Contenido</h2>
                            <?php
                                if (!isset($_SESSION['NumeUser'])) {
                                    echo '<p><strong>Tienes que estar logueado para poder descargar los archivos.</strong> <a href="#" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a></p>';
								}
								
                                $salida = '';
                                while ($archivo = $archivos->fetch_assoc()) {
									if ($archivo["RutaFile"] != '') {
										$valor = '<a href="admin/'. $archivo["RutaFile"] .'" target="_blank" title="Descargar"><i class="fa fa-fw fa-download" aria-hidden="true"></i></a>';
									}
									else {
										$valor = '';
									}
                                    
									$salida.= $crlf.'<p><strong>'.$archivo["NombFile"].'</strong>';
									if (isset($_SESSION['NumeUser'])) {
										$salida.= ' '.$valor;
									}
									$salida.= '</p>';

									//Archivos
									$salida.= cargarHijos($archivo["CodiIden"], 1);
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
<?php 
	function cargarHijos($CodiIden, $I) {
		global $crlf;

		$strSQL = "SELECT CodiIden, NombFile, RutaFile";
		$strSQL.= $crlf."FROM anexosarchivos";
		$strSQL.= $crlf."WHERE NumePadr = ". $CodiIden;
		$strSQL.= $crlf."ORDER BY NumeOrde";
		$archivos = cargarTabla($strSQL);

		$salida = '';
		while ($archivo = $archivos->fetch_assoc()) {
			$valor = '';

			if ($archivo["RutaFile"] != '') {
				$valor = '<a href="admin/'. $archivo["RutaFile"] .'" target="_blank" title="Descargar"><i class="fa fa-fw fa-download" aria-hidden="true"></i></a>';
			}
			
			$salida.= $crlf.'<p style="margin-left: '.(20*$I).'px;">'.$archivo["NombFile"];
			if (isset($_SESSION['NumeUser'])) {
				$salida.= ' '.$valor;
			}
			$salida.= '</p>';

			//Archivos
			$salida.= cargarHijos($archivo["CodiIden"], $I+1);
		}

		return $salida;		
	}
?>