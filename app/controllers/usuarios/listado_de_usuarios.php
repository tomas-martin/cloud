<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 6/7/2023
 * Time: 09:46
 */

$sql = "SELECT * FROM tb_usuarios ";
$query = $pdo->prepare($sql);
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);


