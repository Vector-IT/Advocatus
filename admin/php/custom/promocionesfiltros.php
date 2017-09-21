<?php
namespace VectorForms;

class PromocionFiltro extends Tabla {
    public function customFunc($post) {
        global $config, $crlf;

        switch ($post["field"]) {
            case "ValoFilt":
                $numeFilt = $post["dato"];

                $filtro = $config->buscarDato("SELECT NumeTipoFilt, ValoFilt FROM promocionesfiltros WHERE NumeFilt = ". $numeFilt);
                $strSalida = "";

                switch ($filtro["NumeTipoFilt"]) {  
                    case '1': //PRODUCTO
                        $strSQL = "SELECT NombProd FROM productos WHERE NumeProd IN (". $filtro["ValoFilt"] .") ORDER BY NombProd";
                        $productos = $config->cargarTabla($strSQL);

                        if ($productos) {
                            while ($fila = $productos->fetch_assoc()) {
                                if ($strSalida != '') {
                                    $strSalida.= ', ';
                                }

                                $strSalida.= $fila["NombProd"];
                            }
                        }
                        unset($productos);
                        break;
                    
                    case '2': //CATEGORIA
                        $strSQL = "SELECT NombCate FROM categorias WHERE NumeCate IN (". $filtro["ValoFilt"] .") ORDER BY NombCate";
                        $categorias = $config->cargarTabla($strSQL);

                        if ($categorias) {
                            while ($fila = $categorias->fetch_assoc()) {
                                if ($strSalida != '') {
                                    $strSalida.= ', ';
                                }

                                $strSalida.= $fila["NombCate"];
                            }
                        }
                        unset($categorias);
                        break;
                }

                return $strSalida;

                break;
            
            case "VerProductos":
            case "VerCategorias":
                $strLista = '<select class="form-control input-sm ucase selectpicker" multiple id="ValoFilt" required title="SELECCIONE" >';
                
                if ($post["field"] == "VerProductos") {
                    $strLista.= $crlf.$config->cargarCombo('productos', 'NumeProd', 'NombProd', "NumeEsta = 1", "NombProd");
                }
                else {
                    $strLista.= $crlf.$config->cargarCombo('categorias', 'NumeCate', 'NombCate', "NumePadr IS NULL", "NombCate");
                }
                $strLista.= $crlf.'</select>';
                
                $strSeleccion = '';
                if ($post["dato"] != '') {
                    $numeFilt = $post["dato"];
                    $strSeleccion = $config->buscarDato("SELECT ValoFilt FROM promocionesFiltros WHERE NumeFilt = ". $numeFilt);
                }
                
                return ["lista" => $strLista, "seleccion" => $strSeleccion];
                break;
        }
    }
}
?>