<?php
namespace VectorForms;

class Producto extends Tabla {
    public function customFunc($post) {
        global $config;

        switch ($post["field"]) {
            case "getEdit":
                $numeProd = $post["data"];

                $tabla = $config->cargarTabla("SELECT NumeCate FROM productoscategorias WHERE NumeProd = ". $numeProd);
                $categorias = [];
                while ($fila = $tabla->fetch_assoc()) {
                    $categorias[] = $fila;
                }

                $tabla = $config->cargarTabla("SELECT NumeAtri, Valor FROM productosatributos WHERE NumeProd = ". $numeProd);
                $atributos = [];
                while ($fila = $tabla->fetch_assoc()) {
                    $atributos[] = $fila;
                }

                $resultados = ["categorias" => $categorias, "atributos" => $atributos];
                return $resultados;

                break;

            case "ISBN": 
                $numeProd = $post["dato"];
            
                return $config->buscarDato("SELECT Valor from productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri IN (SELECT NumeAtri FROM atributos WHERE UPPER(NombAtri) = 'ISBN')");
                break;

            case "Autor":
                $numeProd = $post["dato"];

                return $config->buscarDato("SELECT Valor from productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri IN (SELECT NumeAtri FROM atributos WHERE UPPER(NombAtri) = 'AUTOR')");
                break;

            case "CantProd":
                $numeProd = $post["data"]["NumeProd"];
                $cantProd = $post["data"]["CantProd"];

                $strSQL = "UPDATE productos SET CantProd = {$cantProd} WHERE NumeProd = ". $numeProd;
                return $config->ejecutarCMD($strSQL);

                break;

            case "ImpoComp":
                $numeProd = $post["data"]["NumeProd"];
                $impoComp = $post["data"]["ImpoComp"];

                $strSQL = "UPDATE productos SET ImpoComp = {$impoComp} WHERE NumeProd = ". $numeProd;
                return $config->ejecutarCMD($strSQL);

                break;

            case "ImpoVent":
                $numeProd = $post["data"]["NumeProd"];
                $impoVent = $post["data"]["ImpoVent"];

                $strSQL = "UPDATE productos SET ImpoVent = {$impoVent} WHERE NumeProd = ". $numeProd;
                return $config->ejecutarCMD($strSQL);

                break;
        }
    }

    public function insertar($datos) {
		global $config, $crlf;

        $datosProducto = array_slice($datos, 0, 10);
        
        $result = parent::insertar($datosProducto);
		$resultAux = json_decode($result, true);

		if ($resultAux["estado"] === true) {
            $numeProd = $resultAux["id"];

            //Novedades
            $datosNovedades = array(
                "CodiIden"=> "",
                "NumeProd"=> $numeProd
            );
            $novedades = $config->getTabla("productosnovedades");
            $novedades->insertar($datosNovedades);

            //Categorias
            $categorias = explode(",", $datos["Categorias"]);

            for ($I=0; $I < count($categorias); $I++) { 
                $strSQL = "INSERT INTO productoscategorias(NumeProd, NumeCate) VALUES({$numeProd}, {$categorias[$I]})";
                $config->ejecutarCMD($strSQL);
            }

            //Atributos
            $atributos = $config->cargarTabla("SELECT NumeAtri, NombAtri, NumeTipoAtri FROM atributos");
            
            while ($atri = $atributos->fetch_assoc()) {
                $numeAtri = $atri["NumeAtri"];

                if ($atri["NumeTipoAtri"] != "3") {  //Si no es archivo
                    $valor = "'".$datos["Atri". $numeAtri]."'";
                }
                else {
                    if (isset($_FILES["Atri". $numeAtri])) {
                        $temp = explode(".", $_FILES["Atri". $numeAtri]["name"]);
                        $extension = end($temp);
                        
                        $strRnd = $config->get_random_string("abcdefghijklmnopqrstuvwxyz1234567890", 5);

                        $archivo_viejo = $config->buscarDato("SELECT Valor FROM productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri = {$numeAtri}");
                        if ($archivo_viejo != '') {
                            $archivo_viejo = "../". $archivo_viejo;
                        }
                        
                        $archivo = $atri["NombAtri"] ."-". $strRnd .".". $extension;
                        $val =  $atri["NombAtri"] ."/". $archivo;
                            
                        subir_archivo($_FILES["Atri". $numeAtri], "../". $atri["NombAtri"], $archivo, $archivo_viejo);
                        
                        $valor = "'".$val."'";
                    }
                    else {
                        $valor = 'null';
                    }
                }

                $strSQL = "INSERT INTO productosatributos(NumeProd, NumeAtri, Valor) VALUES({$numeProd}, {$numeAtri}, {$valor})";
                $config->ejecutarCMD($strSQL);
            }
            
            return $result;
        }
    }

    public function editar($datos) {
		global $config, $crlf;

        $datosProducto = array_slice($datos, 0, 10);
        
        $result = parent::editar($datosProducto);
		$resultAux = json_decode($result, true);

		if ($resultAux["estado"] === true) {
            $numeProd = $datos["NumeProd"];
            
            //Categorias
            $strSQL = "DELETE FROM productoscategorias WHERE NumeProd = ". $numeProd;
            $config->ejecutarCMD($strSQL);

            $categorias = explode(",", $datos["Categorias"]);

            for ($I=0; $I < count($categorias); $I++) { 
                $strSQL = "INSERT INTO productoscategorias(NumeProd, NumeCate) VALUES({$numeProd}, {$categorias[$I]})";
                $config->ejecutarCMD($strSQL);
            }
            
            //Atributos
            $atributos = $config->cargarTabla("SELECT NumeAtri, NombAtri, NumeTipoAtri FROM atributos");
            
            while ($atri = $atributos->fetch_assoc()) {
                $numeAtri = $atri["NumeAtri"];

                if ($atri["NumeTipoAtri"] != "3") {  //Si no es archivo
                    $valor = "'".$datos["Atri". $numeAtri]."'";
                }
                else {
                    if (isset($_FILES["Atri". $numeAtri])) {
                        $temp = explode(".", $_FILES["Atri". $numeAtri]["name"]);
                        $extension = end($temp);
                        
                        $strRnd = $config->get_random_string("abcdefghijklmnopqrstuvwxyz1234567890", 5);

                        $archivo_viejo = $config->buscarDato("SELECT Valor FROM productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri = {$numeAtri}");
                        if ($archivo_viejo != '') {
                            $archivo_viejo = "../". $archivo_viejo;
                        }
                        
                        $archivo = $atri["NombAtri"] ."-". $strRnd .".". $extension;
                        $val =  $atri["NombAtri"] ."/". $archivo;
                            
                        subir_archivo($_FILES["Atri". $numeAtri], "../". $atri["NombAtri"], $archivo, $archivo_viejo);
                        
                        $valor = "'".$val."'";
                    }
                    else {
                        if (isset($datos["Atri". $numeAtri ."Clear"])) {
                            if ($datos["Atri". $numeAtri ."Clear"] == "1") {
                                $valor = "null";

                                $archivo_viejo = $config->buscarDato("SELECT Valor FROM productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri = {$numeAtri}");
                                if (file_exists($archivo_viejo)) {
                                    unlink($archivo_viejo);
                                }
                            }
                            else {
                                continue;
                            }
                        }
                        else {
                            continue;
                        }
                    }
                }
                
                $strSQL = "DELETE FROM productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri = {$numeAtri}";
                $config->ejecutarCMD($strSQL);

                $strSQL = "INSERT INTO productosatributos(NumeProd, NumeAtri, Valor) VALUES({$numeProd}, {$numeAtri}, {$valor})";
                $config->ejecutarCMD($strSQL);
            }

            return $result;
        }
    }

    public function borrar($datos, $filtro = '') {
        global $config, $crlf;

        $numeProd = $datos["NumeProd"];

        //Categorias
        $strSQL = "DELETE FROM productoscategorias WHERE NumeProd = ". $numeProd;
        $config->ejecutarCMD($strSQL);

        //Atributos
        $archivos = $config->cargarTabla("SELECT Valor FROM productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri IN (SELECT NumeAtri FROM atributos WHERE NumeTipoAtri = 3)");
        while ($fila = $archivos->fetch_assoc()) {
            $archivo = "../". $fila["Valor"];

            if (file_exists($archivo)) {
                unlink($archivo);
            }
        }
        $strSQL = "DELETE FROM productosatributos WHERE NumeProd = ". $numeProd;
        $config->ejecutarCMD($strSQL);

        //Imagenes
        $imagenes = $config->cargarTabla("SELECT RutaImag FROM productosimagenes WHERE NumeProd = ". $numeProd);
        while ($imag = $imagenes->fetch_assoc()) {
            $archivo = "../". $imag["RutaImag"];

            if (file_exists($archivo)) {
                unlink($archivo);
            }
        }
        $strSQL = "DELETE FROM productosimagenes WHERE NumeProd = ". $numeProd;
        $config->ejecutarCMD($strSQL);

        //Novedades
        $strSQL = "DELETE FROM productosnovedades WHERE NumeProd = ". $numeProd;
        $config->ejecutarCMD($strSQL);

        //Favoritos
        $strSQL = "DELETE FROM usuariosfavoritos WHERE NumeProd = ". $numeProd;
        $config->ejecutarCMD($strSQL);

        return parent::borrar($datos, $filtro);
    }

    public function listar($strFiltro = "", $conBotones = true, $btnList = [], $order = '', $pagina = 1) {
        if ($strFiltro != "") {
            if (isset($strFiltro["ISBN"])) {
                $strFiltro["productos.NumeProd"] = array(
                    "type"=>"number",
                    "operator"=>"in",
                    "join"=>"and",
                    "value"=>"(SELECT NumeProd FROM productosatributos WHERE Valor LIKE '%". $strFiltro["ISBN"]["value"] ."%')"
                );

                unset($strFiltro["ISBN"]);
            }

            if (isset($strFiltro["Autor"])) {
                if (isset($strFiltro["productos.NumeProd"])) {
                    $strFiltro["productos.NumeProd"]["value"].= " AND productos.NumeProd IN (SELECT NumeProd FROM productosatributos WHERE Valor LIKE '%". $strFiltro["Autor"]["value"] ."%')";
                }
                else {
                    $strFiltro["productos.NumeProd"] = array(
                        "type"=>"number",
                        "operator"=>"in",
                        "join"=>"and",
                        "value"=>"(SELECT NumeProd FROM productosatributos WHERE Valor LIKE '%". $strFiltro["Autor"]["value"] ."%')"
                    );
                }

                unset($strFiltro["Autor"]);
            }
        }

        parent::listar($strFiltro, $conBotones, $btnList, $order, $pagina);
    }
}
?>