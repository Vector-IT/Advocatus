<?php
session_start();
/**
 * Archivo de interconexion entre el html y las clases de tabla
 *
 * @author Vector-IT
 *
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'datos.php';
	require_once 'upload_file.php';

	$urlLogout = "Location:". "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $config->raiz ."admin/logout.php";
    
	if (!isset($_SESSION['is_logged_in'])) {
		$strSalida = 'Error al actualizar los datos, su sesión a caducado.<br>';
		$strSalida.= $crlf.'Debe ingresar nuevamente.<br>';
		$strSalida.= $crlf.'<button class="btn btn-default" onclick="location.href = \'http://'. $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $config->raiz .'admin/logout.php\';">Iniciar Sesión</button>';
	    exit($strSalida);
	}
	
	$operacion = $_POST["operacion"];
	$tabla = $config->getTabla($_POST["tabla"]);
	
	switch ($operacion) {
		//Insert
		case "0":
		//Update
		case "1":
		//Delete
		case "2":
			$datos = [];
			$esDetalle = false;

			$masterID = "";
			
			$iterator = 1;
			
			//Archivos
			foreach ($_FILES as $name => $val) {
				if (isset($tabla->fields[$name])) {
					$temp = explode(".", $_FILES[$name]["name"]);
					$extension = end($temp);
					
					if ($tabla->fields[$name]['nomFileField'] != '') {
						$strRnd = $_POST[$tabla->fields[$name]['nomFileField']];
					} else {
						$strRnd = $config->get_random_string("abcdefghijklmnopqrstuvwxyz1234567890", 5);
					}

					$archivo_viejo = $config->buscarDato("SELECT {$name} FROM {$tabla->tabladb} WHERE {$tabla->IDField} = '{$_POST[$tabla->IDField]}'");
					if ($archivo_viejo != '') {
						$archivo_viejo = "../". $archivo_viejo;
					}
					
					$archivo = $name ."-". $strRnd .".". $extension;
					$val =  $tabla->fields[$name]['ruta'] ."/". $archivo;
						
					subir_archivo($_FILES[$name], "../". $tabla->fields[$name]['ruta'], $archivo, $archivo_viejo);
					
					$datos[$name] = $val;
				}
			}
			
			//Campos
			foreach ($_POST as $name => $val) {
				//Me fijo si hay que borrar algun archivo
				if (substr($name, 0, 12) == 'vectorClear-') {
					$nameAux = substr($name, 12);
					
					if ($val == "1") {
						$archivo_viejo = $config->buscarDato("SELECT {$nameAux} FROM {$tabla->tabladb} WHERE {$tabla->IDField} = '{$_POST[$tabla->IDField]}'");
						if ($archivo_viejo != '') {
							$archivo_viejo = "../". $archivo_viejo;
						}
						
						if (file_exists($archivo_viejo)) {
							unlink($archivo_viejo);
						}
						
						$datos[$nameAux] = '';
					}
				} else {
					if (($name != 'operacion') && ($name != 'tabla')) {
							$datos[$name] = $val;
					}
				}
			}
			
			$result = ejecutar($operacion, $tabla, $datos);
			
			if ($result["estado"] === true) {
				exit("Datos actualizados!");
			} else {
				exit("Error al actualizar los datos.<br>".$result["estado"]);
			}
			
			break;
		
		//Subir y Bajar de Orden
		case "3":
		case "4":
			$datos = [];
			foreach ($_POST as $name => $val) {
				if (($name != 'operacion') && ($name != 'tabla')) {
					$datos[$name] = $val;
				}
			}

			$result = ejecutar($operacion, $tabla, $datos);
			
			if ($result["estado"] === true) {
				exit("Datos actualizados!");
			} else {
				exit("Error al actualizar los datos.<br>".$result["estado"]);
			}
			break;
		
		//Listar
		case "10":
			$strFiltro = (isset($_POST["filtro"])? $_POST["filtro"]: "");
			$conBotones = (isset($_POST["conBotones"])? $_POST["conBotones"]: true);
			$btnList = (isset($_POST["btnList"])? $_POST["btnList"]: []);
			$order = (isset($_POST["order"])? $_POST["order"]: "");
			$pagina = (isset($_POST["pagina"])? $_POST["pagina"]: 1);
			
			$tabla->listar($strFiltro, $conBotones, $btnList, $order, $pagina);
			break;
			
		//Funcion propia de cada clase
		case "100":
			$array = array(
					"post"=>$_POST,
					"valor"=>$tabla->customFunc($_POST)
			);
				
			header('Content-Type: application/json');
			echo json_encode($array);
			break;
	}
}

function ejecutar($operacion, $tabla, $datos)
{
	$result = [];
	
	switch ($operacion) {
		case "0": //Insert
			$result = json_decode($tabla->insertar($datos), true);
			break;
				
		case "1": //Update
			$result = json_decode($tabla->editar($datos), true);
			break;
				
		case "2": //Delete
			if ($tabla->allowDelete) {
				$result = json_decode($tabla->borrar($datos), true);
			} else {
				$result["estado"] = "Operación inválida.";
			}
			break;
		
		//Subir y bajar el orden
		case "3":
		case "4":
			$result = json_decode($tabla->subirBajar($operacion, $datos), true);
			break;
	}
	
	return $result;
}
