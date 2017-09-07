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

                $_SESSION['is_logged_in'] = 1;
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
            $NumeProv = $_POST["NumeProv"];
            $NombUser = $_POST["NombUser"];
            $NombPass = $_POST["NombPass"];

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

            $strSQL = "INSERT INTO usuarios(NumeUser, NombPers, NombUser, NombPass, NumeCarg, MailUser, TeleUser, DireUser, CodiPost, NumeProv, NumeEsta)";
            $strSQL.= $crlf."VALUES ({$NumeUser}, '{$NombPers}', '{$NombUser}', '{$NombPass}', 10, '{$MailUser}', '{$TeleUser}', '{$DireUser}', '{$CodiPost}', {$NumeProv}, 0);";

            $result = ejecutarCMD($strSQL);
            
            if ($result["estado"]) {
                $salida = array("estado"=>true, "msg"=>"Registro exitoso!<br>Revise su correo electrónico para verificar la cuenta!");
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
                    $strSQL.= $crlf.", NumeProv = ". $NumeProv;
                    $strSQL.= $crlf." WHERE NumeCarr = ". $_SESSION["NumeCarr"];
                }
            }
            else {
                $strSQL = "UPDATE invitados SET";
                $strSQL.= $crlf."NombPers = '{$NombPers}'";
                $strSQL.= $crlf.", TeleUser = '{$TeleUser}'";
                $strSQL.= $crlf.", MailUser = '{$MailUser}'";
                $strSQL.= $crlf.", DireUser = '{$DireUser}'";
                $strSQL.= $crlf.", CodiPost = '{$CodiPost}'";
                $strSQL.= $crlf.", NumeProv = ". $NumeProv;
                $strSQL.= $crlf." WHERE NumeInvi = ". $numeInvi;
            }
            $result = ejecutarCMD($strSQL);
            
            if ($result["estado"]) {
                $salida = array("estado"=>true, "msg"=>"Registro exitoso!<br>Revise su correo electrónico para verificar la cuenta!");
            }
            else {
                $salida = array("estado"=>false, "msg"=>"Error al registrar usuario!");
            }
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($salida);
}