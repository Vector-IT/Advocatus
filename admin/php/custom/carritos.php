<?php
namespace VectorForms;

class Carrito extends Tabla {
    public function customFunc($post) {
        global $config, $crlf, $raiz;

        switch ($post["field"]) {
            case 'Procesar':
                $numeCarr = $post["data"]["NumeCarr"];
                $numeFact = $post["data"]["NumeFact"];
                $obseCarr = $post["data"]["ObseCarr"];

                $strSQL = "UPDATE carritos SET NumeEstaCarr = 8, NumeFact = '{$numeFact}', ObseCarr = '{$obseCarr}' WHERE NumeCarr = ". $numeCarr;
                $result =  $config->ejecutarCMD($strSQL);

                // if ($result === true) {
                //     $MailUser = $config->buscarDato("SELECT MailUser FROM carritos WHERE NumeCarr = ". $numeCarr);

                //     $titulo = "Editorial Advocatus - Carrito Procesado";
                //     $mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
                //     $mensajeHtml.= $crlf."<br>";
                //     $mensajeHtml.= $crlf.$obseCarr;
                    
                //     $mensaje = "Este es un mensaje automatico. Por favor no lo responda";
                //     $mensaje.= "\n\n";
                //     $mensaje.= "\n". $obseCarr;
                    
                //     $url = "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'admin/php/enviarMail.php';
                //     $fields = array(
                //             'Para' => $MailUser,
                //             'Titulo' => $titulo,
                //             'Mensaje' => $mensajeHtml,
                //             'MensajeAlt' => $mensaje
                //     );
                //     $datos = http_build_query($fields);
                        
                //     //open connection
                //     $handle = curl_init();
                //     curl_setopt($handle, CURLOPT_URL, $url);
                //     curl_setopt($handle, CURLOPT_POST, true);
                //     curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
                //     curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                //     curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
                    
                //     //execute post
                //     $response = curl_exec($handle);
                //     if (!$response) {
                //         $salida = array("estado"=>false, "msg"=>"Error al enviar datos!");
                //     }
                //     //close connection
                //     curl_close($handle);
                    
                //     if (strripos($response, "error") === false) {
                //         $salida = array("estado"=>true, "msg"=>"Datos actualizados y enviados!");
                //     }
                //     else {
                //         $salida = array("estado"=>false, "msg"=>"Error al enviar correo!");
                //     }
                // }

                return $result;
                break;
        }
	}
}