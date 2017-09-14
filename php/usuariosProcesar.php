<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $operacion = $_POST["operacion"];

    $salida = [];

    switch ($operacion) {
        case "1": //Login
            $user = strtoupper(str_replace("'", "", $_POST["usuario"]));
            $pass = md5(str_replace("'", "", $_POST["password"]));
            
            $usuario = buscarDato("SELECT NumeUser, NombPers FROM usuarios WHERE NumeEsta = 1 AND UPPER(NombUser) = '{$user}' AND NombPass = '{$pass}'");
            
            $salida = "";
            
            if ($usuario != "")
            {
                unset($_SESSION["NumeInvi"]);

                $_SESSION['NumeUser'] = $usuario["NumeUser"];
                $_SESSION['NombPers'] = $usuario['NombPers'];
                
                $numeCarr = buscarDato("SELECT NumeCarr FROM carritos WHERE NumeEstaCarr = 1 AND NumeUser = ". $usuario["NumeUser"]);
                if ($numeCarr != "") {
                    $_SESSION["NumeCarr"] = $numeCarr;
                }

                $params = session_get_cookie_params();
                if ($_POST["remember"] == "1") {
                    setcookie("v-commerce_numeUser", $_SESSION["NumeUser"], time() + (60*60*24*365), $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }
                else {
                    setcookie("v-commerce_numeUser", "", time() - 4200, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }
                setcookie("v-commerce_numeInvi", "", time() - 4200, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

                $salida = array(
                    "estado"=>true,
                    "msg"=>"Login exitoso!",
                    "nombPers"=>$usuario['NombPers']
                );
            }
            else {
                //Error
                $salida = array("estado"=>false, "msg"=>"Usuario o contraseña incorrectos!");
            }
            break;

        case "2": //Registrar
            $NombPers = $_POST["NombPers"];
            $TeleUser = $_POST["TeleUser"];
            $MailUser = $_POST["MailUser"];
            $DireUser = $_POST["DireUser"];
            $CodiPost = $_POST["CodiPost"];
            $NombLoca = $_POST["NombLoca"];
            $NumeProv = $_POST["NumeProv"];
            $NombUser = $_POST["NombUser"];
            $NombPass = md5($_POST["NombPass"]);

            $result = buscarDato("SELECT COUNT(*) FROM usuarios WHERE UPPER(NombUser) = '{$NombUser}'");
            if ($result != "0") {
                $salida = array("estado"=>false, "msg"=>"El nombre de usuario ya existe!");
                break;
            }

            $result = buscarDato("SELECT COUNT(*) FROM usuarios WHERE UPPER(MailUser) = '{$MailUser}'");
            if ($result != "0") {
                $salida = array("estado"=>false, "msg"=>"El correo electrónico ya se encuentra registrado!");
                break;
            }

            $NumeUser = buscarDato("SELECT COALESCE(MAX(NumeUser), 0) + 1 FROM usuarios");

            $strSQL = "INSERT INTO usuarios(NumeUser, NombPers, NombUser, NombPass, NumeCarg, MailUser, TeleUser, DireUser, CodiPost, NombLoca, NumeProv, NumeEsta)";
            $strSQL.= $crlf."VALUES ({$NumeUser}, '{$NombPers}', '{$NombUser}', '{$NombPass}', 10, '{$MailUser}', '{$TeleUser}', '{$DireUser}', '{$CodiPost}', '{$NombLoca}', {$NumeProv}, 2);";

            $result = ejecutarCMD($strSQL);
            
            if ($result["estado"]) {
                $titulo = "Editorial Advocatus - Activar Cuenta";
				$mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
				$mensajeHtml.= "<br>Usted se registró como usuario en eadvocatus.com.ar";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= 'Haga click <a href="http://'. $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'/php/activarUsuario.php?id='. md5($NumeUser) .'">aquí</a> para activar su cuenta y empezar a usarla.';
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= $crlf."Muchas gracias.";
				
				$mensaje = "Este es un mensaje automatico. Por favor no lo responda";
				$mensaje.= "\nUsted se registró como usuario en eadvocatus.com.ar";
				$mensaje.= "\n\n";
				$mensaje.= '\nDirijase a http://'. $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'/php/activarUsuario.php?id='. md5($NumeUser) .' para activar su cuenta y empezar a usarla.';
				$mensaje.= "\n\n";
				$mensaje.= "\nMuchas gracias.";
				
				$url = "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'admin/php/enviarMail.php';
				$fields = array(
						'Para' => $MailUser,
						'Titulo' => $titulo,
						'Mensaje' => $mensajeHtml,
						'MensajeAlt' => $mensaje
				);
				$datos = http_build_query($fields);
					
				//open connection
				$handle = curl_init();
				curl_setopt($handle, CURLOPT_URL, $url);
				curl_setopt($handle, CURLOPT_POST, true);
				curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
				
				//execute post
				$response = curl_exec($handle);
				if (!$response) {
					$salida = array("estado"=>false, "msg"=>"Error al enviar mail de activación!");
				}
				//close connection
				curl_close($handle);
				
				if (strripos($response, "error") === false) {
                    $salida = array("estado"=>true, "msg"=>"Registro exitoso!<br>Revise su correo electrónico para verificar la cuenta!");
                }
                else {
                    $salida = array("estado"=>false, "msg"=>"Error al enviar mail de activación!");
				}
                
            }
            else {
                $salida = array("estado"=>false, "msg"=>"Error al registrar usuario!");
            }
            break;

        case "3": //Actualizar datos
            $NombPers = $_POST["NombPers"];
            $TeleUser = $_POST["TeleUser"];
            $MailUser = $_POST["MailUser"];
            $DireUser = $_POST["DireUser"];
            $CodiPost = $_POST["CodiPost"];
            $NombLoca = $_POST["NombLoca"];
            $NumeProv = $_POST["NumeProv"];

            $numeUser = isset($_SESSION["NumeUser"])? $_SESSION["NumeUser"]: '';
            $numeInvi = isset($_SESSION["NumeInvi"])? $_SESSION["NumeInvi"]: '';
        
            if ($numeUser != '') {
                if (isset($_SESSION["NumeCarr"])) {
                    $strSQL = "UPDATE carritos SET";
                    $strSQL.= $crlf."NombPers = '{$NombPers}'";
                    $strSQL.= $crlf.", TeleUser = '{$TeleUser}'";
                    $strSQL.= $crlf.", MailUser = '{$MailUser}'";
                    $strSQL.= $crlf.", DireUser = '{$DireUser}'";
                    $strSQL.= $crlf.", CodiPost = '{$CodiPost}'";
                    $strSQL.= $crlf.", NombLoca = '{$NombLoca}'";
                    $strSQL.= $crlf.", NumeProv = ". $NumeProv;
                    $strSQL.= $crlf." WHERE NumeCarr = ". $_SESSION["NumeCarr"];
                }
                else {
                    $strSQL = "UPDATE usuarios SET";
                    $strSQL.= $crlf."NombPers = '{$NombPers}'";
                    $strSQL.= $crlf.", TeleUser = '{$TeleUser}'";
                    $strSQL.= $crlf.", MailUser = '{$MailUser}'";
                    $strSQL.= $crlf.", DireUser = '{$DireUser}'";
                    $strSQL.= $crlf.", CodiPost = '{$CodiPost}'";
                    $strSQL.= $crlf.", NombLoca = '{$NombLoca}'";
                    $strSQL.= $crlf.", NumeProv = ". $NumeProv;
                    $strSQL.= $crlf." WHERE NumeUser = ". $numeUser;
                }
            }
            else {
                $strSQL = "UPDATE invitados SET";
                $strSQL.= $crlf."NombPers = '{$NombPers}'";
                $strSQL.= $crlf.", TeleUser = '{$TeleUser}'";
                $strSQL.= $crlf.", MailUser = '{$MailUser}'";
                $strSQL.= $crlf.", DireUser = '{$DireUser}'";
                $strSQL.= $crlf.", CodiPost = '{$CodiPost}'";
                $strSQL.= $crlf.", NombLoca = '{$NombLoca}'";
                $strSQL.= $crlf.", NumeProv = ". $NumeProv;
                $strSQL.= $crlf." WHERE NumeInvi = ". $numeInvi;
            }
            $result = ejecutarCMD($strSQL);
            
            if ($result["estado"]) {
                $salida = array("estado"=>true, "msg"=>"Registro exitoso!<br>Revise su correo electrónico para verificar la cuenta!");
            }
            else {
                $salida = array("estado"=>false, "msg"=>"Error al modificar datos!".$result["msg"]);
            }
            break;

        case "4": //Recuperar contraseña
            $MailUser = $_POST["MailUser"];
            $numeUser = buscarDato("SELECT NumeUser FROM usuarios WHERE NumeEsta = 1 AND UPPER(MailUser) = '{$MailUser}'");

            if ($numeUser != "") {
                $nombPass = get_random_string("abcdefghijklmnopqrstuvwxyz", 5);
                ejecutarCMD("UPDATE usuarios SET NombPass = '". md5($nombPass) ."' WHERE NumeUser = ". $numeUser);

                $titulo = "Editorial Advocatus - Recuperar Contraseña";
				$mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
				$mensajeHtml.= "<br>Usted solicit&oacute; recuperar la contrase&ntilde;a en eadvocatus.com.ar";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= "Su contrase&ntilde;a es <strong>{$nombPass}</strong>";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= $crlf."Muchas gracias.";
				
				$mensaje = "Este es un mensaje automatico. Por favor no lo responda";
				$mensaje.= "\nUsted solicito recuperar la contrasena en eadvocatus.com.ar";
				$mensaje.= "\n\n";
				$mensaje.= "\nSu contraseña es " . $nombPass;
				$mensaje.= "\n\n";
				$mensaje.= "\nMuchas gracias.";
				
				$url = "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz .'admin/php/enviarMail.php';
				$fields = array(
						'Para' => $MailUser,
						'Titulo' => $titulo,
						'Mensaje' => $mensajeHtml,
						'MensajeAlt' => $mensaje
				);
				$datos = http_build_query($fields);
					
				//open connection
				$handle = curl_init();
				curl_setopt($handle, CURLOPT_URL, $url);
				curl_setopt($handle, CURLOPT_POST, true);
				curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
				
				//execute post
				$response = curl_exec($handle);
				if (!$response) {
					$salida = array("estado"=>false, "msg"=>"Error al recuperar contraseña!");
				}
				//close connection
				curl_close($handle);
				
				if (strripos($response, "error") === false) {
                    $salida = array("estado"=>true, "msg"=>"Enviamos un correo electrónico con su contraseña!");
                }
                else {
                    $salida = array("estado"=>false, "msg"=>"Su correo no está registrado!");
				}
                
                break;
            }
            else {
                $salida = array("estado"=>false, "msg"=>"Su correo no está registrado!");
            }

            $salida = array("estado"=>false, "msg"=>"Error al recuperar contraseña! 1");
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($salida);
}