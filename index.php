<?php
require 'config/config.php';
require 'config/database.php';

// Obtener datos de productos
$db = new Database();
$con = $db->conectar();
$sql = $con->prepare("SELECT productos.id, productos.imagen_url, productos.nombre, productos.descripcion, marcas.nombre AS marca, categorias.nombre AS categoria, productos.stock, productos.precio, productos.estado_activo, productos.created_at, productos.updated_at, promocionesxproducto.porcentaje FROM productos JOIN marcas ON productos.marca_id = marcas.id JOIN categorias ON productos.categoria_id = categorias.id LEFT JOIN promocionesxproducto ON productos.id = promocionesxproducto.producto_id WHERE productos.estado_activo=1");
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);




$mostrar_bienvenida = isset($_SESSION['mostrar_bienvenida']) && $_SESSION['mostrar_bienvenida'];

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
</head>

<body>
<?php
$sql = $con->prepare("SELECT ClienteNombre FROM clientes WHERE id = :user_id");
$sql->bindParam(':user_id', $_SESSION['user_id']);
$sql->execute();
$cliente = $sql->fetch(PDO::FETCH_ASSOC);

if ($mostrar_bienvenida):
?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            background: '#34C759',
            color: '#fff'
        });

        Toast.fire({
            icon: 'success',
            title: "Bienvenido <?= htmlspecialchars($cliente['ClienteNombre']) ?>"
        });
    </script>
<?php unset($_SESSION['mostrar_bienvenida']); endif; ?>
<?php require 'includes/header.php'; ?>



<section id="hero" class="mb-8 bg-black bg-opacity-50 py-2 flex flex-col md:flex-row items-center justify-between" style="background-image: url('https://www.mueblesmarfil.es/media/images/gallery/9/big/11.jpg'); background-position: center center;">
    <div class=" p-4 flex items-center justify-center flex-col ml-12">
        <h1 style="color: #3498db; font-family: 'Brush Script MT', cursive; text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;" class="text-6xl font-extrabold tracking-tightest">Furni Store</h1>
        <p style="color: white; text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black, -1px -1px black, -1px 1px black, 1px -1px black, 1px 1px black;" class="text-lg font-bold tracking-wide uppercase"><strong>Tus muebles preferidos</strong></p>
    </div>
    <div class="bg-black bg-opacity-50 rounded-full p-4 flex items-center justify-center flex-col ml-12">
        <h1 style="color: #34C759; text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black, -1px -1px black, -1px 1px black, 1px -1px black, 1px 1px black;" class="text-lg font-bold tracking-wide uppercase"><strong>Super Liquidación!</strong></h1>
        <h2 class="text-2xl font-bold tracking-tighter text-white"><strong>Hasta 12 cuotas</strong></h2>
        <h3 class="text-4xl font-extrabold tracking-tightest text-white"><strong>En todos nuestros productos</strong></h3>
        <button
            class="mt-4 text-white bg-gradient-to-r from-blue-400 to-purple-600 hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-500 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl"
            type="button"
            onclick="window.location.href='shop.php'">Comprar
        </button>
    </div>
</section>
<!-- Cambiar Imagenes -->
<div class="cardslider my-4">
    <div class="logos-slider">
        <div class="logos-slider-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSq284jNmlZDpiat0TD0hbi6SwLI7lyKHfIVw&s"/>
            <img src="https://batavia.es/img/m/18-marcas.jpg"/>
            <img src="https://batavia.es/img/m/107-marcas.jpg"/>
            <img src="https://batavia.es/img/m/32-marcas.jpg"/>
            <img src="https://www.venace.com/wp-content/uploads/2021/09/IKEA.png"/>
            <img src="https://dcdn.mitiendanube.com/stores/001/673/506/themes/common/logo-720815261-1620139332-af690af172883147f7cb4f8690aec5191620139332-480-0.webp"/>
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSB08MXmxX2DrWTlb5FPLGcKUqTZnUn7AEd8w&s"/>
            <img src="https://www.sears.com.mx/c/muebles/img/carrusel-marcas/boal_muebles_1000X1000_JPG_60Q.jpg"/>
            <img src="https://batavia.es/img/m/32-marcas.jpg"/>
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVg66FGR9q07WGes7c5KuE2CTz2Ana8wCCmg&s"/>
        </div>

        <div class="logos-slider-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSq284jNmlZDpiat0TD0hbi6SwLI7lyKHfIVw&s"/>
            <img src="https://batavia.es/img/m/18-marcas.jpg"/>
            <img src="https://batavia.es/img/m/107-marcas.jpg"/>
            <img src="https://batavia.es/img/m/32-marcas.jpg"/>
            <img src="https://www.venace.com/wp-content/uploads/2021/09/IKEA.png"/>
            <img src="https://dcdn.mitiendanube.com/stores/001/673/506/themes/common/logo-720815261-1620139332-af690af172883147f7cb4f8690aec5191620139332-480-0.webp"/>
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSB08MXmxX2DrWTlb5FPLGcKUqTZnUn7AEd8w&s"/>
            <img src="https://www.sears.com.mx/c/muebles/img/carrusel-marcas/boal_muebles_1000X1000_JPG_60Q.jpg"/>
            <img src="https://batavia.es/img/m/32-marcas.jpg"/>
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVg66FGR9q07WGes7c5KuE2CTz2Ana8wCCmg&s"/>
        </div>

    </div>
</div>  
<style>
    .logos-slider {
    display: flex;
    flex-wrap: nowrap;
    overflow: hidden;
    position: relative;
    -webkit-mask-image: linear-gradient(90deg,rgba(0,0,0,0) 0,#000 15%,#000 85%,rgba(0,0,0,0) 100%);
    mask-image: linear-gradient(90deg,rgba(0,0,0,0) 0,#000 15%,#000 85%,rgba(0,0,0,0) 100%);
    }

    .logos-slider-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5rem;
        animation: slide 30s linear infinite;
    }

    .logos-slider-container img {
    width:150px;
    max-width: 150px;
    }

    @keyframes slide {
    0% {
        transform: translate3d(0,0,0)
    }
    100% {
        transform: translate3d(-100%,0,0)
    }
    }

    .cardslider {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    max-width: 90vw;
    margin: 0 auto;
    box-shadow: 0px 3px 8px rgb(61 74 84 / 10%), 0px 3px 12px rgb(61 74 84 / 6%)
    }

</style>

<section id="products1" class="section-p1">
    <h2 class="text-3xl font-bold text-center">Productos Destacados</h2>
    <p class="text-xl text-center">Nuestra selección personal</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mt-8">
        <?php for ($i = 0; $i < count($productos) && $i < 8; $i++): ?>
            <?php $producto = $productos[$i]; ?>
            <?php if ($producto['id'] >= 1 && $producto['id'] <= 8): ?>
                <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-xl transition duration-300 cursor-pointer" onclick="location.href='details.php?id=<?php echo $producto['id']; ?>&token=<?php echo hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>'">
                    <?php
                    $imagen = $producto['imagen_url'];
                    ?>
                    <img class="w-full h-48 object-cover rounded-t-lg" src="<?php echo htmlspecialchars($imagen); ?>" alt=""/>
                    <div class="px-4 py-2">
                        <span class="text-sm font-bold text-gray-700"><?php echo htmlspecialchars($producto['marca']); ?></span>
                        <h5 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                        </div>
                        <?php
                        $precioOriginal = $producto['precio'];
                        $descuento = $producto['porcentaje'] ?? 0;
                        $precioConDescuento = $precioOriginal - ($precioOriginal * ($descuento / 100));
                        ?>
                        <h4 class="text-lg font-bold text-gray-900">$<?php echo htmlspecialchars(number_format($precioConDescuento, 2)); ?></h4>
                    </div>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</section>

<section id="feature" class="section-p1 bg-gray-100 py-16">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12">
            <div class="feature-card">
                <i class="fas fa-truck"></i>
                <h6>Envio Gratis</h6>
                <p>Recibe tu pedido en la comodidad de tu hogar.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shopping-cart"></i>
                <h6>Compra Online</h6>
                <p>Compre ahora y obtenga 20% de descuento en su primer pedido.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-star"></i>
                <h6>Ahorre con nosotros</h6>
                <p>Nuestros precios son los más competitivos del mercado.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-gift"></i>
                <h6>Promociones</h6>
                <p>Sigue nuestras redes sociales para obtener promociones exclusivas.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-lock"></i>
                <h6>Seguridad</h6>
                <p>Tu privacidad es nuestra prioridad.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-phone"></i>
                <h6>Soporte 24/7</h6>
                <p>Nuestro equipo de soporte está a tu disposición.</p>
            </div>
        </div>
    </div>
</section>
<style>
    .feature-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 16px;
        text-align: center;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .feature-card i {
        font-size: 2rem;
        color: #4A4A4A;
        margin-bottom: 16px;
        transition: color 0.3s ease;
    }

    .feature-card h6 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333;
    }

    .feature-card p {
        font-size: 0.875rem;
        color: #666;
    }

    /* Hover effect */
    .feature-card:hover {
        transform: scale(1.05); /* Zoom in effect */
        color: #D5006D; /* Change text color on hover */
    }

    .feature-card:hover i {
        color: #D5006D; /* Change icon color on hover */
    }

</style>

<section id="products-carousel" class="section-p1 flex flex-col items-center justify-center">
    <h2 class="text-3xl font-bold text-center">Productos Mas Vendidos</h2>
    <p class="text-xl text-center">Los favoritos de nuestros clientes</p>
    <div class="products-carousel mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" style="max-width: 95%;">
        <?php for ($i = 0; $i < count($productos) && $i < 8; $i++): ?>
            <?php $producto = $productos[$i]; ?>
            <?php if ($producto['id'] >= 1 && $producto['id'] <= 8): ?>
                <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-xl transition duration-300 cursor-pointer" onclick="location.href='details.php?id=<?php echo $producto['id']; ?>&token=<?php echo hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>'">
                    <?php
                    $imagen = $producto['imagen_url'];
                    ?>
                    <img class="w-full h-48 object-cover rounded-t-lg" src="<?php echo htmlspecialchars($imagen); ?>" alt=""/>
                    <div class="px-4 py-2">
                        <span class="text-sm font-bold text-gray-700"><?php echo htmlspecialchars($producto['marca']); ?></span>
                        <h5 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                            <i class="fas fa-star text-yellow-500"></i>
                        </div>
                        <?php
                        $precioOriginal = $producto['precio'];
                        $descuento = $producto['porcentaje'] ?? 0;
                        $precioConDescuento = $precioOriginal - ($precioOriginal * ($descuento / 100));
                        ?>
                        <h4 class="text-lg font-bold text-gray-900">$<?php echo htmlspecialchars(number_format($precioConDescuento, 2)); ?></h4>  </div>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.products-carousel').slick({
            slidesToShow: 3,  // Cambia esto para mostrar más o menos productos
            slidesToScroll: 1,
            dots: true,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>


<?php require 'includes/footer.php'; ?>
    
<script src="script.js"></script>
<script>
    function addProducto(id, token) {
        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('token', token)

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok) {
                let elemento = document.getElementById("num_cart")
                elemento.innerHTML = data.numero
            }
        })
    }
</script>



</body>

</html>
