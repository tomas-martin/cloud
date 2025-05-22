<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 17/7/2023
 * Time: 18:40
 */
include ('../../../app/config.php');

$id_usuario = $_POST['id_usuario'];


$sentencia = $pdo->prepare("DELETE FROM tb_usuarios WHERE id_usuario = '$id_usuario' ");

if($sentencia->execute()){
    session_start();
    $_SESSION['mensaje'] = "Se elimino de la manera correcta en la base de datos";
    $_SESSION['icono'] = 'success';
    header('Location: '.$URL.'/admin/usuarios');
}else{
    session_start();
    $_SESSION['mensaje'] = "error no se pudo eliminar en la base de datos";
    $_SESSION['icono'] = 'error';
    header('Location: '.$URL.'/admin/usuarios');
}


