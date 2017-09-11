<?php
namespace VectorForms;

class Carrito extends Tabla {
    public function customFunc($post) {
        global $config;

        switch ($post["field"]) {
            case 'Procesar':
                $strSQL = "UPDATE carritos SET NumeEstaCarr = 8 WHERE NumeCarr = ". $post["data"];
                return $config->ejecutarCMD($strSQL);
                break;
        }
	}
}