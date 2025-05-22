<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 3/7/2023
 * Time: 23:06
 */

echo $password = "12345678";

echo md5($password)."<br>";
echo sha1($password)."<br>";
echo password_hash($password, PASSWORD_DEFAULT);

$hash = '$2y$10$dLUyAyZYUuGqA1hmoojVzOYNRh4DonHSQpkJzG7GP1VXOHwv2qzK6';

if (password_verify($password, $hash)) {
    echo 'password correcto';
} else {
    echo 'password incorrecto';
}