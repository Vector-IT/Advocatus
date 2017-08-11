<?php
namespace VectorForms;

class Producto extends Tabla {
    public function insertar($datos) {
		global $config, $crlf;

        $datosProducto = array_slice($datos, 
		$result = parent::insertar($datosProducto);
		$resultAux = json_decode($result, true);

		if ($resultAux["estado"] === true) {
        }
    }
}
?>