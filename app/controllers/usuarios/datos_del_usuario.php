<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 27/7/2023
 * Time: 22:11
 */

$sql = "SELECT * FROM tb_usuarios WHERE id_usuario = '$id_usuario' ";
$query = $pdo->prepare($sql);
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario){
    $nombre_completo = $usuario['nombre_completo'];
    $email = $usuario['email'];
    $cargo = $usuario['cargo'];
}