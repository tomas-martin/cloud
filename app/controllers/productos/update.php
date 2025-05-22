<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 29/8/2023
 * Time: 19:37
 */

include ('../../../app/config.php');

$nombre_producto = $_POST['nombre_producto'];
$descripcion = $_POST['descripcion'];
$stock = $_POST['stock'];
$stock_minimo = $_POST['stock_minimo'];
$stock_maximo = $_POST['stock_maximo'];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$fecha_de_ingreso = $_POST['fecha_de_ingreso'];
$id_producto = $_POST['id_producto'];
$imagen = $_POST['imagen'];


if($_FILES['file']['name'] != null){
    //echo "hay imagen nueva";
    $nombreDelArchivo = date('Y-m-d-h-i-s').$_FILES['file']['name'];
    $location = "../../../public/images/productos/".$nombreDelArchivo;
    move_uploaded_file($_FILES['file']['tmp_name'],$location);
    $imagen = $nombreDelArchivo;
}else{
    //echo "no hay imagen";
    $imagen = $imagen;
}


$sentencia = $pdo->prepare("UPDATE tb_productos 
SET nombre_producto=:nombre_producto,
    descripcion=:descripcion,
    imagen=:imagen,
    stock=:stock,
    stock_minimo=:stock_minimo,
    stock_maximo=:stock_maximo,
    precio_compra=:precio_compra,
    precio_venta=:precio_venta,
    fecha_de_ingreso=:fecha_de_ingreso,
    fyh_actualizacion=:fyh_actualizacion
    WHERE id_producto = :id_producto  ");

$sentencia->bindParam('nombre_producto',$nombre_producto);
$sentencia->bindParam('descripcion',$descripcion);
$sentencia->bindParam('imagen',$imagen);
$sentencia->bindParam('stock',$stock);
$sentencia->bindParam('stock_minimo',$stock_minimo);
$sentencia->bindParam('stock_maximo',$stock_maximo);
$sentencia->bindParam('precio_compra',$precio_compra);
$sentencia->bindParam('precio_venta',$precio_venta);
$sentencia->bindParam('fecha_de_ingreso',$fecha_de_ingreso);
$sentencia->bindParam('fyh_actualizacion',$fechaHora);
$sentencia->bindParam('id_producto',$id_producto);

if($sentencia->execute()){
    echo "Se actualizó el producto de la manera correcta";
    session_start();
    $_SESSION['mensaje'] = "Se actualizó el producto de la manera correcta";
    $_SESSION['icono'] = 'success';
    header('Location: '.$URL.'/admin/productos/');
}else{
    session_start();
    $_SESSION['mensaje'] = "No se pudo actualizar al producto";
    $_SESSION['icono'] = 'error';
    header('Location: '.$URL.'/admin/productos/update.php?id_producto='.$id_producto);
}

