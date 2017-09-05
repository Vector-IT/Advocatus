<?php 
	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");

	require_once __DIR__.'/../admin/php/datosdb.php';

	function ejecutarCMD($strSQL, $returnID = false) {
		global $dbhost, $dbuser, $dbpass, $dbschema, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbschema);
		$conn->set_charset("utf8");
	
		$strError = "";
		$blnEstado = true;
	
		if (!$conn->query($strSQL)) {
			$blnEstado = false;
			$strError = $conn->error;
		}

		if ($strError == "" && $returnID) {
			$strError = $conn->insert_id;
		}
		$conn->close();
	
		return array("estado"=>$blnEstado, "msg"=>$strError);
	}
	
	function buscarDato($strSQL) {
		global $dbhost, $dbuser, $dbpass, $dbschema, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbschema);
		$conn->set_charset("utf8");

		$strSalida = "";

		if (!($tabla = $conn->query($strSQL))) {
			$strSalida = "Error al realizar la consulta.";
		}
		else {
			if ($tabla->num_rows > 0) {
				if ($tabla->field_count == 1) {
					$fila = $tabla->fetch_row();
					$strSalida = $fila[0];
				}
				else {
					$strSalida = $tabla->fetch_assoc();
				}
				$tabla->free();
			}
			else {
				$strSalida = '';
			}
		}

		if (is_resource($conn)) {
			$conn->close();
		}

		return $strSalida;
	}

	/**
	 * Funcion de carga de tabla
	 */
	function cargarTabla($strSQL) {
		global $dbhost, $dbuser, $dbpass, $dbschema, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbschema);
		$conn->set_charset("utf8");
		
		$tabla = $conn->query($strSQL);
		
		$conn->close();
		
		return $tabla;
	}
	
	function cargarCombo($strSQL, $CampoNumero, $CampoTexto, $Seleccion = "", $itBlank = false, $itBlankText = 'Seleccione...', $itBlankValue = "-1") {
		global $dbhost, $dbuser, $dbpass, $dbschema, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbschema);
		$conn->set_charset("utf8");
		
		$tabla = $conn->query($strSQL);
		
		$conn->close();
		
		$strSalida = "";
		if ($itBlank)
			$strSalida.= $crlf.'<option value="'.$itBlankValue.'">'.$itBlankText.'</option>';
		
		while ($fila = $tabla->fetch_array()) {
			if ($CampoTexto != "") {
				if (strcmp($fila[$CampoNumero], $Seleccion) != "0")
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'">'.htmlentities($fila[$CampoTexto]).'</option>';
				else
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'" selected>'.htmlentities($fila[$CampoTexto]).'</option>';
			}
			else {
				if (strcmp($fila[$CampoNumero], $Seleccion) != "0")
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'" />';
				else
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'" selected />';
			}
		}
		
		return $strSalida;
	}
	
	function cargarUsuario($numeUser) {
		$_SESSION["is_logged_in"] = "1";
		$_SESSION['NumeUser'] = $numeUser;
		$_SESSION['NombPers'] = buscarDato("SELECT NombPers FROM usuarios WHERE NumeUser = ". $numeUser);
		$_SESSION["remember"] = "1";

		$numeCarr = buscarDato("SELECT NumeCarr FROM carritos WHERE NumeEstaCarr = 1 AND NumeUser = ". $numeUser);
		if ($numeCarr != "") {
			$_SESSION["NumeCarr"] = $numeCarr;
		}
	}

	function crearInvitado($numeCook) {
		$result = ejecutarCMD("INSERT INTO invitados(NumeCook) VALUES('".$numeCook."')", true);
		return $result["msg"];
	}

	function get_random_string($valid_chars, $length)
	{
		// start with an empty random string
		$random_string = "";
	
		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);
	
		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);
	
			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick-1];
	
			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}
	
		// return our finished random string
		return $random_string;
	}
		
?>