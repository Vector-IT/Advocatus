<?php
session_start();
require_once 'conexion.php';

$numeUser = $_GET["id"];

$numeUser = buscarDato("SELECT NumeUser FROM usuarios WHERE NumeEsta = 2 AND md5(NumeUser) = '{$numeUser}'");

if ($numeUser != '') {
    $result = ejecutarCMD("UPDATE usuarios SET NumeEsta = 1 WHERE NumeUser = ". $numeUser);
}

header("Location:". "http://". $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] != "80"? ":".$_SERVER['SERVER_PORT']: "") . $raiz);