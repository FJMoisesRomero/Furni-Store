<?php

define("KEY_TOKEN", "ABC.tr-79");
define("MONEDA", "$");

// Comprobar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$num_cart = 0;
if(isset($_SESSION['carrito']['articulos'])){
    $num_cart = count($_SESSION['carrito']['articulos']);
}



?>