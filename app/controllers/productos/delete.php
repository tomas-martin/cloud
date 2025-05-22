<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 29/8/2023
 * Time: 21:57
 */

include ('../../../app/config.php');

$id_producto = $_POST['id_producto'];


$sentencia = $pdo->prepare("DELETE FROM tb_productos WHERE id_producto = '$id_producto' ");

if($sentencia->execute()){
    session_start();
    $_SESSION['mensaje'] = "Se elimino de la manera correcta en la base de datos";
    $_SESSION['icono'] = 'success';
    header('Location: '.$URL.'/admin/productos');
}else{
    session_start();
    $_SESSION['mensaje'] = "error no se pudo eliminar en la base de datos";
    $_SESSION['icono'] = 'error';
    header('Location: '.$URL.'/admin/productos');
}


