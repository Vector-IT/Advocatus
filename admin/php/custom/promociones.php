<?php
namespace VectorForms;

class Promocion extends Tabla {
    public function borrar($datos, $filtro = '') {
        global $config;

        $strSQL = "DELETE FROM promocionesfiltros WHERE NumeProm = ". $datos["NumeProm"];

        $result = $config->ejecutarCMD($strSQL);
        
        if ($result === true) {
            return parent::borrar($datos, $filtro);
        }
        else {
            return false;
        }
	}
}