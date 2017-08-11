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

            case "precio":
                $numeProd = $post["data"]["NumeProd"];
                $impoVent = $post["data"]["Precio"];

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
                    $valor = $datos["Atri". $numeAtri];
                }
                else {
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
                    
                    $valor = $val;
                }

                $strSQL = "INSERT INTO productosatributos(NumeProd, NumeAtri, Valor) VALUES({$numeProd}, {$numeAtri}, '{$valor}')";
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
                    $valor = $datos["Atri". $numeAtri];
                }
                else {
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
                    
                    $valor = $val;
                }
                
                $strSQL = "DELETE FROM productosatributos WHERE NumeProd = {$numeProd} AND NumeAtri = {$numeAtri}";
                $config->ejecutarCMD($strSQL);

                $strSQL = "INSERT INTO productosatributos(NumeProd, NumeAtri, Valor) VALUES({$numeProd}, {$numeAtri}, '{$valor}')";
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

        //Favoritos
        $strSQL = "DELETE FROM usuariosfavoritos WHERE NumeProd = ". $numeProd;
        $config->ejecutarCMD($strSQL);

        return parent::borrar($datos, $filtro);
    }
}
?>