<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 4/7/2023
 * Time: 19:54
 */

include ('../../config.php');

session_start();

if(isset($_SESSION['sesion_email'])){
    session_destroy();
    header('Location: '.$URL.'/login');
}