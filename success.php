<?php
    require 'config/config.php';
    require 'config/database.php';


    $db = new Database();
    $con = $db->conectar();

    // Obtener el user_id desde la sesión
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


    // Limpiar el carrito después de completar el pago
    unset($_SESSION['carrito']['productos']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furni Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://kit.fontawesome.com/54b6794846.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="index_style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
    <style>
        /* Import Google font - Poppins */
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

        .button {
        font-family: "Poppins", sans-serif;
        position: relative;
        padding: 10px 22px;
        border-radius: 6px;
        border: none;
        color: #fff;
        cursor: pointer;
        background-color: #7d2ae8;
        transition: all 0.2s ease;
        margin-top: 5px;
        }
        
    </style>
</head>

<body>
<body class="bg-white text-gray-800 transition duration-500 ease-in-out">
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>
    
<!-- Hero Section - Agradecimiento por la compra -->
<section id="page-header" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white py-12 md:py-24">
    <div class="container mx-auto text-center">
        <h2 class="text-5xl font-bold mb-4"><strong>¡Gracias por tu compra en Furni Store!</strong></h2>
        <p class="text-lg max-w-lg mx-auto mb-8">
            Agradecemos tu preferencia por nuestros productos. Cada pieza de mobiliario vendida nos llena de satisfacción y nos impulsa a seguir brindando calidad y diseño. Esperamos que disfrutes tu nuevo producto de Furni Store.
        </p>
        <div class="bg-white text-black py-8 px-6 rounded-lg shadow-lg inline-block max-w-lg">
            <h3 class="text-3xl font-bold mb-2">10% de Descuento en tu Próxima Compra</h3>
            <p class="text-lg mb-6">Usa el código <strong>GRACIAS10</strong> al finalizar tu compra para recibir un descuento del 10% en cualquiera de nuestros productos destacados.</p>
            <p class="text-sm text-gray-600">*El descuento es válido hasta el 30 de junio de 2024</p>
        </div>
    </div>
</section>



    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
           
            // Obtén el contador de ejecuciones del almacenamiento local
            let executionCount = parseInt(localStorage.getItem('scriptExecutionCount')) || 0;

            // Si el contador es 0, se ejecuta el script y se incrementa el contador
            if (executionCount === 0) {
                // Marca el script como ejecutado
                localStorage.setItem('scriptExecutionCount', '1');

                
                    setTimeout(function() {
                        location.href = 'profile.php'; // Redirige a la página de perfil
                    }, 5000); // Ajusta el tiempo de espera si es necesario

            } else {
                // Incrementa el contador de ejecuciones
                localStorage.setItem('scriptExecutionCount', '0');
            }
        });
    </script>

</body>

</html>
