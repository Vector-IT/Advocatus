<?php
	session_start();
    require_once 'php/conexion.php';
    
    $strSQL = "SELECT NumeAnex, Titulo, Subtitulo, ImagAnex, Slug";
    $strSQL.= $crlf."FROM anexos";
    $strSQL.= $crlf."WHERE NumeEsta = 1";
    $strSQL.= $crlf."ORDER BY NumeOrde";
    $anexos = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Anexos - ADVOCATUS | Editorial · Librería</title>
    
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

        <!-- DESTACADOS -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Anexos</h2>
                    <p>Accedé a los anexos de nuestra Editorial</p>
                </div>
            </div>
        </div>
        <!-- /.DESTACADOS -->

    
        <!-- ANEXOS -->
        <div class="container">
        <?php 
            if ($anexos->num_rows > 0) {
                $J = 0;
                $salida = '';

                while ($fila = $anexos->fetch_assoc()) {
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
                    $salida.= $crlf.'		<a href="anexo/'.$fila["Slug"].'.php" class="img-producto"><img class="img-center" src="admin/'.$fila["ImagAnex"].'" alt="" style="width: 150px; height: 219px;"></a>';
                    $salida.= $crlf.'		<a href="anexo/'.$fila["Slug"].'.php" class="titulo-producto text-center">'.$fila["Titulo"].'</a>';
                    $salida.= $crlf.'       <p class="text-center">'. $fila["Subtitulo"] .'</p>';
                    $salida.= $crlf.'	</div>';
                    $salida.= $crlf.'</div>';

                    $J++;
                }
                $salida.= $crlf.'</div>';
                echo $salida;
            }
        ?>
        </div>
        <!-- /.ANEXOS -->

        <?php include 'php/footer.php'; ?>
	</div>
	<!-- /#page-content-wrapper --> 

	<?php include 'php/sidebar.php'; ?>
</div>
	
<?php include 'php/scripts-footer.php'; ?>

</body>
</html>