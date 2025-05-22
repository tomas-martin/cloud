<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 5/6/2023
 * Time: 12:34
 */

define('APP_NAME','SISTEMA DE VETERINARIO - HILARIWEB');
define('SERVIDOR','localhost');
define('USUARIO','root');
define('PASSWORD','');
define('BD','sistemaveterinario');

$servidor = "mysql:dbname=".BD.";host=".SERVIDOR;

try{
    $pdo = new PDO($servidor,USUARIO,PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
    //echo "conexión exitosa con la base de datos";
}catch (PDOException $e){
   // print_r($e);
    echo "error no se pudo conectar a la base de datos";
}


$URL = "http://localhost/www.sistemadeveterinario.com";

date_default_timezone_set("America/Caracas");
$fechaHora = date('Y-m-d H:i:s');