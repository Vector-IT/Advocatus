<?php
namespace VectorForms;

class Categoria extends Tabla {
    public function borrar($datos, $filtro = '') {
        global $config;

        $strSQL = "DELETE FROM productoscategorias WHERE NumeCate = ". $datos["NumeCate"];

        $result = $config->ejecutarCMD($strSQL);
        
        if ($result === true) {
            return parent::borrar($datos, $filtro);
        }
        else {
            return false;
        }
	}
}