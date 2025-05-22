<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 17/7/2023
 * Time: 18:40
 */
include ('../../../app/config.php');

$nombre_completo = $_POST['nombre_completo'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_verify = $_POST['password_verify'];
$cargo = $_POST['cargo'];


$contador = 0;
$sql = "SELECT * FROM tb_usuarios WHERE email = '$email' ";
$query = $pdo->prepare($sql);
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($usuarios as $usuario){
    $contador = $contador + 1;
}
if($contador>0){
    //echo "este usuario ya esta registrado en la base de datos";
    session_start();
    $_SESSION['mensaje'] = "Este correo electronico ".$email." ya esta registrado en la base de datos";
    $_SESSION['icono'] = 'error';
    header('Location: '.$URL.'/admin/usuarios/create.php');
}else{
   // echo "este usuario es nuevo";
    if($password == $password_verify) {
        //echo "si son iguales";

        $password = password_hash($password, PASSWORD_DEFAULT);
        $sentencia = $pdo->prepare("INSERT INTO tb_usuarios
                               ( nombre_completo, email, password, cargo, fyh_creacion)
                         VALUES(:nombre_completo,:email,:password,:cargo,:fyh_creacion) ");
        $sentencia->bindParam('nombre_completo',$nombre_completo);
        $sentencia->bindParam('email',$email);
        $sentencia->bindParam('password',$password);
        $sentencia->bindParam('cargo',$cargo);
        $sentencia->bindParam('fyh_creacion',$fechaHora);

        if($sentencia->execute()){
            session_start();
            $_SESSION['mensaje'] = "Se registro de la manera correcta en la base de datos";
            $_SESSION['icono'] = 'success';
            header('Location: '.$URL.'/admin/usuarios');
        }else{
            session_start();
            $_SESSION['mensaje'] = "error no se pudo registrar en la base de datos";
            $_SESSION['icono'] = 'error';
            header('Location: '.$URL.'/admin/usuarios/create.php');
        }

    }else{
        //echo "las contraseñas no son iguales";
        session_start();
        $_SESSION['mensaje'] = "Las contraseñas no son iguales";
        $_SESSION['icono'] = 'error';
        header('Location: '.$URL.'/admin/usuarios/create.php');
    }
}



