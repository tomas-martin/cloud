<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 19/8/2023
 * Time: 12:07
 */

$sql = "SELECT * FROM tb_productos ";
$query = $pdo->prepare($sql);
$query->execute();
$productos = $query->fetchAll(PDO::FETCH_ASSOC);