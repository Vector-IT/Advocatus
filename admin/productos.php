<?php
    namespace VectorForms;

    ini_set("log_errors", 1);
    ini_set("error_log", "php-error.log");

    session_start();
    require_once 'php/datos.php';

    $urlLogin = "Location:". "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $config->raiz ."admin/login.php?returnUrl=" . $_SERVER['REQUEST_URI'];
    $urlIndex = "Location:". "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $config->raiz ."admin/";
    
	if (!isset($_SESSION['is_logged_in'])) {
		header($urlLogin);
		die();
	}
			
	if ($_SESSION['is_logged_in'] !== 1) {
		header($urlLogin);
		die();
	}
			
	$tabla = $config->tablas['productos'];
			
	if ($tabla->numeCarg != '') {
		if (intval($tabla->numeCarg) < intval($config->buscarDato("SELECT NumeCarg FROM ".$config->tbLogin." WHERE NumeUser = ". $_SESSION["NumeUser"]))) {
			header($urlIndex);
			die();
		}
	}

	$atributos = $config->cargarTabla("SELECT NumeAtri, NombAtri, NumeTipoAtri, FlagRequ FROM atributos ORDER BY NumeOrde");
			
    (isset($_REQUEST["id"]))? $item = $_REQUEST["id"]: $item = "";
 
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
?>
<!DOCTYPE html>
<html>
<head>
    <?php 
        require_once 'php/linksHeader.php'; 
        // require_once 'js/custom/productos.php';
    ?>

	<script src="js/custom/productos.js"></script>
	
	<link rel="stylesheet" href="css/equal-height-columns.css">
</head>
<body>
    <?php
    if (!isset($_REQUEST["menu"]) || $_REQUEST["menu"] == 1) {
        $config->crearMenu();
    }
        
        require_once 'php/header.php';
    ?>
    
    <div class="container-fluid">
        <div class="page-header">
            <h2>
            <?php
            $icono = "";
            if ($tabla->icono != '') {
                $icono = '<i class="fa fa-fw '.$tabla->icono.'" aria-hidden="true"></i> ';
            }

            if ($tabla->masterTable == '') {
                echo $icono.$tabla->titulo;
            } else {
                if (isset($_REQUEST[$tabla->masterFieldId])) {
                    $strAux = $config->buscarDato("SELECT {$tabla->masterFieldName} FROM {$tabla->masterTable} WHERE {$tabla->masterFieldId} = '{$_REQUEST[$tabla->masterFieldId]}'");
                    echo $icono.$tabla->titulo. ' de ' .$strAux;
                } else {
                    echo $icono.$tabla->titulo;
                }
            }
            ?>
            </h2>
        </div>
        
        <?php
        if ((($tabla->masterTable != '') && isset($_REQUEST[$tabla->masterFieldId])) || (isset($_REQUEST["id"]))) {
            //echo '<button class="btn btn-sm btn-info clickable" data-js="location.href = \''. $config->tablas[$tabla->masterTable]->url .'\';"><i class="fa fa-chevron-circle-left fa-fw" aria-hidden="true"></i> Volver</button>';
            echo '<button class="btn btn-sm btn-info clickable" data-js="history.go(-1);"><i class="fa fa-chevron-circle-left fa-fw" aria-hidden="true"></i> Volver</button>';
        }
        ?>

        <button id="btnNuevo" type="button" class="btn btn-sm btn-primary" onclick="editarproductos(0);"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> Nuevo</button>
        <form id="frmproductos" class="form-horizontal marginTop20 frmObjeto" method="post" onSubmit="return false;">
            <input type="hidden" id="hdnTabla" value="productos" />
            <input type="hidden" id="hdnOperacion" value="0" />

            <div class="form-group form-group-sm ">
                <label for="NumeProd" class="control-label col-md-2 col-lg-2">Número:</label>
                <div class="col-md-2 col-lg-2">
                    <input type="number" step="1" class="form-control input-sm " id="NumeProd" disabled   readonly  />
                </div>
            </div>

            <div class="form-group form-group-sm ">
	            <label for="NombProd" class="control-label col-md-2 col-lg-2">Nombre:</label>
    	        <div class="col-md-6 col-lg-6">
        		    <input type="text" class="form-control input-sm " id="NombProd"  required size="200"   />
            	</div>
            </div>

            <div class="form-group form-group-sm ">
	            <label for="DescProd" class="control-label col-md-2 col-lg-2">Descripción:</label>
    	        <div class="col-md-10 col-lg-10">
        		    <textarea class="form-control input-sm autogrow " id="DescProd" required ></textarea>
					<script type="text/javascript">
						$("#DescProd").autogrow({vertical: true, horizontal: false, minHeight: 36});
					</script>
				</div>
            </div>

            <div class="form-group form-group-sm ">
				<label for="CantProd" class="control-label col-md-2 col-lg-2">Cantidad:</label>
				<div class="col-md-2 col-lg-2">
					<input type="number" step="0.1" class="form-control input-sm " id="CantProd"  required    />
				</div>
            </div>

            <div class="form-group form-group-sm ">
				<label for="ImpoComp" class="control-label col-md-2 col-lg-2">Imp. Compra:</label>
				<div class="col-md-2 col-lg-2">
					<input type="number" step="0.1" class="form-control input-sm " id="ImpoComp"  required    />
				</div>
            </div>

            <div class="form-group form-group-sm ">
				<label for="ImpoVent" class="control-label col-md-2 col-lg-2">Imp. Venta:</label>
				<div class="col-md-2 col-lg-2">
					<input type="number" step="0.1" class="form-control input-sm " id="ImpoVent"  required    />
				</div>
            </div>

            <div class="form-group form-group-sm ">
				<div class="col-md-4 col-lg-4 col-md-offset-2 col-lg-offset-2">
					<label class="labelCheck ucase">
						<input type="checkbox" id="Novedad"> Es Novedad?
					</label>
	            </div>
            </div>

            <div class="form-group form-group-sm ">
				<div class="col-md-4 col-lg-4 col-md-offset-2 col-lg-offset-2">
					<label class="labelCheck ucase">
						<input type="checkbox" id="Promocion"> Es Promoción?
					</label>
				</div>
            </div>

            <div class="form-group form-group-sm ">
				<div class="col-md-4 col-lg-4 col-md-offset-2 col-lg-offset-2">
					<label class="labelCheck ucase">
						<input type="checkbox" id="Destacado"> Es Destacado?
					</label>
				</div>
            </div>

            <div class="form-group form-group-sm ">
				<label for="NumeEsta" class="control-label col-md-2 col-lg-2">Estado:</label>
				<div class="col-md-2 col-lg-2">
					<select class="form-control input-sm ucase " id="NumeEsta" required  >
						<?php echo $config->cargarCombo("estados", "NumeEsta", "NombEsta");?>
					</select>
	            </div>
            </div>

			<hr>
			<div class="row row-eq-height">
				<div class="col-md-6" style="border-right: 1px solid;">
                    <h4>Categorías</h4>
                    <?php
                        $categorias = $config->cargarTabla("SELECT NumeCate, NombCate FROM categorias WHERE NumePadr IS NULL ORDER BY NombCate");

                        $strSalida = '';
                        $strSalida.= $crlf. '<div class="form-group form-group-sm ">';
                        $strSalida.= $crlf. '	<div class="list-group">';
                        while ($cate = $categorias->fetch_assoc()) {
                            $strSalida.= $crlf. '	    <a id="NumeCate'.$cate["NumeCate"].'" href="#" class="list-group-item" onclick="selectCate('.$cate["NumeCate"].');">'.$cate["NombCate"].'</a>';

                            $subcategorias = $config->cargarTabla("SELECT NumeCate, NombCate FROM categorias WHERE NumePadr = ".$cate["NumeCate"]." ORDER BY NombCate");
                            while ($subcate = $subcategorias->fetch_assoc()) {
                                $strSalida.= $crlf. '	    <a id="NumeCate'.$subcate["NumeCate"].'" href="#" class="list-group-item" onclick="selectCate('.$subcate["NumeCate"].');" style="padding-left: 35px;"><i class="fa fa-caret-right" aria-hidden="true"></i> '.$subcate["NombCate"].'</a>';
                            }
                        }
                        $strSalida.= $crlf. '   </div>';
                        $strSalida.= $crlf. '</div>';

                        echo $strSalida;
                    ?>
				</div>
				<div class="col-md-6">
					<h4>Atributos</h4>
					<?php
						$strSalida = '';
						if ($atributos->num_rows == 0) { 
							$strSalida.= $crlf.'<h5>Sin atributos</h5>';
						}

						while ($atrib = $atributos->fetch_assoc()) {
							$strSalida.= $crlf. '<div class="form-group form-group-sm ">';
							$strSalida.= $crlf. '	<label for="Atri'. $atrib["NumeAtri"] .'" class="control-label col-md-2 col-lg-2">'. $atrib["NombAtri"] .':</label>';
							$strSalida.= $crlf. '	<div class="col-md-10 col-lg-10">';
							switch ($atrib["NumeTipoAtri"]) {
								case "1": //Text
									$strSalida.= $crlf. '		<input type="text" class="form-control input-sm " id="Atri'. $atrib["NumeAtri"] .'" '. ($atrib["FlagRequ"] == "1"? 'required': '') .'/>';
									break;

								case "2": //Textarea
									$strSalida.= $crlf. '		<textarea class="form-control input-sm autogrow " id="Atri'. $atrib["NumeAtri"] .'" '. ($atrib["FlagRequ"] == "1"? 'required': '') .'></textarea>';
									$strSalida.= $crlf. '		<script type="text/javascript">';
									$strSalida.= $crlf. '			$("#Atri'. $atrib["NumeAtri"] .'").autogrow({vertical: true, horizontal: false, minHeight: 36});';
									$strSalida.= $crlf. '		</script>';
									break;

                                case "3": //Archivo
                                    if ($atrib["FlagRequ"] != "1") {
                                        $strSalida.= $crlf. '		<input id="hdnAtri'. $atrib["NumeAtri"] .'Clear" type="hidden" value="0">';
                                    }
									$strSalida.= $crlf. '		<input type="file" onchange="archivoNuevo('. $atrib["NumeAtri"] .');" class="form-control input-sm" id="Atri'. $atrib["NumeAtri"] .'" '. ($atrib["FlagRequ"] == "1"? 'required': '') .' size="80">';
									$strSalida.= $crlf. '		<span id="btnBorrarAtri'. $atrib["NumeAtri"] .'"></span>';
									break;

								case "7":
									$strSalida.= $crlf. '		<select class="form-control input-sm ucase " id="Atri'. $atrib["NumeAtri"] .'" '. ($atrib["FlagRequ"] == "1"? 'required': '') .'>';
									$strSalida.= $crlf. '			'.$config->cargarCombo("atributosopciones", "NumeAtriOpci", "Valor", "NumeAtri = ". $atrib["NumeAtri"], "Valor");
									$strSalida.= $crlf. '		</select>';
									break;
							}
							$strSalida.= $crlf. '	</div>';
							$strSalida.= $crlf. '</div>';
						}
						echo $strSalida;
					?>
				</div>
			</div>
			
			<hr>

			<div class="form-group">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check fa-fw" aria-hidden="true"></i> Aceptar</button>
            		&nbsp;
                    <button type="reset" class="btn btn-sm btn-default" onclick="editarproductos(-1);"><i class="fa fa-times fa-fw" aria-hidden="true"></i> Cancelar</button>
                </div>
            </div>
        </form>

        <?php
        //Botones opcionales
        if (count($tabla->btnForm) > 0) {
            for ($I = 0; $I < count($tabla->btnForm); $I++) {
                echo $crlf.'<button class="btn btn-sm '. $tabla->btnForm[$I][2] .'" onclick="'. $tabla->btnForm[$I][1] .'">'. $tabla->btnForm[$I][0] .'</button>';
            }
        }
        ?>
        
        <div id="actualizando" class="alert alert-info" role="alert">
            <i class="fa fa-refresh fa-fw fa-spin"></i> Actualizando datos, por favor espere...
        </div>
        
        <div id="divMsj" class="alert alert-danger" role="alert">
            <span id="txtHint">Info</span>
        </div>

        <?php $tabla->searchForm(); ?>

        <div id="divDatos" class="marginTop40">
            <?php
            if ($tabla->listarOnLoad) {
                $tabla->listar();
            }
            ?>
        </div>
    </div>
    
    <?php
    for ($I = 0; $I < count($tabla->modalList); $I++) {
        require_once $tabla->modalList[$I];
    }
    ?>

    <?php require_once 'php/footer.php';?>
</body>
</html>
