<?php
    require 'config/config.php';
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furni Store</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

</head>

<body>
<?php require 'includes/header.php'; ?>

<section id="page-header" class="bg-black text-white py-12 md:py-24">
    <h2 class="text-5xl font-bold text-center"><strong>Contáctanos</strong></h2>
    <p class="text-center text-lg max-w-md mx-auto">¿Cómo podemos ayudarte?</p>
</section>

<section id="contact-details" class="section-p1 bg-gray-100 py-12 md:py-24">
    <div class="container mx-auto p-6 md:p-12">
        <div class="details grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-3xl font-bold">¡Visitanos en nuestra tienda!</h2>
                <h3 class="text-2xl font-bold">Sucursal Principal</h3>
                <ul class="list-disc pl-6">
                    <li class="flex items-center">
                        <i class="fal fa-map mr-2 text-2xl"></i>
                        <p>Los Guayacanes 108, Salta </p>
                    </li>
                    <li class="flex items-center">
                        <i class="fal fa-envelope mr-2 text-2xl"></i>
                        <p>clientes@vinosdelvalle.com</p>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-2 text-2xl"></i>
                        <p>+5493875278489</p>
                    </li>
                    <li class="flex items-center">
                        <i class="far fa-clock mr-2 text-2xl"></i>
                        <p>9:00 - 21:00, Lun - Vie</p>
                    </li>
                </ul>
            </div>

            <div class="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6033.34427832715!2d-65.40226442289082!3d-24.766780406984847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x941bc3e8becf7bef%3A0xc760c7d2483ac546!2sLos%20Guayacanes%20108%2C%20A4400%20Salta!5e1!3m2!1ses-419!2sar!4v1728314133383!5m2!1ses-419!2sar"
                    width="600" height="450" class="rounded-lg shadow-lg" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    </div>

</section>

<section id="form-details" class="bg-white py-12 md:py-24">
    <div class="container mx-auto p-6 md:p-12">
        <form action="">
            <span class="text-3xl font-bold">Déjenos un mensaje</span>
            <h2 class="text-2xl font-bold">Estamos encantados de escucharlo</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="text" placeholder="Su nombre" class="p-2 rounded-lg border-2 border-solid border-gray-300">
                <input type="text" placeholder="Correo" class="p-2 rounded-lg border-2 border-solid border-gray-300">
                <input type="text" placeholder="Asunto" class="p-2 rounded-lg border-2 border-solid border-gray-300">
                <textarea name="" id="" cols="30" rows="10" placeholder="Su mensaje" class="p-2 rounded-lg border-2 border-solid border-gray-300"></textarea>
            </div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Enviar</button>
        </form>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
    <script src="script.js"></script>
</body>

</html>