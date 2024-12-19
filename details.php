<?php
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT count(p.id) FROM productos p WHERE p.id=? AND p.estado_activo=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT p.nombre, p.descripcion, p.imagen_url, p.precio, COALESCE(pxp.porcentaje, 0) AS descuento FROM productos p LEFT JOIN promocionesxproducto pxp ON p.id = pxp.producto_id WHERE p.id=? AND p.estado_activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $imagenes = $row['imagen_url'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
        }
    } else {
        echo 'Error al procesar la petición';
        exit;
    }
}

$sql = $con->prepare("SELECT p.id, p.imagen_url, p.nombre, p.descripcion, p.stock, p.precio, p.estado_activo, m.nombre as marca, c.nombre as categoria_nombre FROM productos p
    INNER JOIN marcas m ON p.marca_id = m.id
    INNER JOIN categorias c ON p.categoria_id = c.id
    WHERE p.estado_activo=1");
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxiqueen</title>
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

<!-- Contenido principal -->
<section id="products1" class="section-p1 py-12">
        <div class="container mx-auto">
            <!-- Producto y Carousel -->
            <div class="flex flex-col md:flex-row items-center bg-white shadow-lg rounded-lg p-6">
                <!-- Carousel de imágenes -->
                <div class="md:w-1/2">
                    <div id="carouselImages" class="carousel slide relative">
                        <div class="carousel-inner relative w-full overflow-hidden">
                            <div class="carousel-item active relative float-left w-full">
                                <!-- Placeholder for product image -->
                                <img src="<?php echo $imagenes ?>" class="block w-full" alt="Mueble">
                            </div>
                            <?php foreach ($imagenes as $img) { ?>
                                <div class="carousel-item relative float-left w-full">
                                    <img src="<?php echo base64_encode($img) ?>" class="block w-full" alt="Mueble">
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev absolute top-0 bottom-0 left-0 flex items-center justify-center p-0" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon inline-block" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next absolute top-0 bottom-0 right-0 flex items-center justify-center p-0" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon inline-block" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>

                <!-- Información del producto -->
                <div class="md:w-1/2 mt-4 md:mt-0 text-center md:text-left">
                    <h2 class="text-4xl font-bold mb-2"><?php echo $nombre; ?></h2>

                    <?php if ($descuento > 0) { ?>
                        <p class="text-lg line-through text-gray-500"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></p>
                        <h2 class="text-3xl font-bold text-green-600">
                            <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                            <small class="text-lg"><?php echo $descuento; ?>% descuento</small>
                        </h2>
                    <?php } else { ?>
                        <h2 class="text-3xl font-bold"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
                    <?php } ?>
                    
                    <!-- Opciones de compra -->
                    <div class="flex items-center justify-center space-x-3 mt-4">
                        <button class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 w-full md:w-auto" type="button" onclick="buyNow(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Comprar ahora</button>
                        <button class="border border-blue-500 text-blue-500 font-semibold py-2 px-4 rounded-lg hover:bg-blue-500 hover:text-white w-full md:w-auto" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                    </div>
                    
                    <p class="mt-3 text-gray-600 text-sm">Quedan pocas unidades, ¡hazlo tuyo ahora!</p>
                </div>
            </div>

            <!-- Sección de reseñas -->
            <div class="mt-8 bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-3xl font-bold mb-4">Reseñas de clientes</h3>
                <div class="space-y-4">
                    <div class="p-4 border-b">
                        <div class="flex items-center">
                            <span class="text-xl font-bold">Juan Pérez</span>
                            <span class="ml-auto text-sm text-gray-500">10/10</span>
                        </div>
                        <p class="text-gray-700">Excelente.</p>
                    </div>
                    <div class="p-4 border-b">
                        <div class="flex items-center">
                            <span class="text-xl font-bold">María Gómez</span>
                            <span class="ml-auto text-sm text-gray-500">9/10</span>
                        </div>
                        <p class="text-gray-700">Me encantó el diseño y el material es suave. Definitivamente lo recomendaría.</p>
                    </div>
                    <div class="p-4 border-b">
                        <div class="flex items-center">
                            <span class="text-xl font-bold">Pedro Sánchez</span>
                            <span class="ml-auto text-sm text-gray-500">8/10</span>
                        </div>
                        <p class="text-gray-700">Es un buen producto, aunque esperaba un poco más de suavidad.</p>
                    </div>
                </div>
            </div>
        </div>  
</section>

<section id="products-carousel" class="section-p1 flex flex-col items-center justify-center py-8">
    <h2 class="text-3xl font-bold text-center">Productos Similares</h2>
    <p class="text-xl text-center">Pensamos que podrían interesarte</p>
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
                        $sql = $con->prepare("SELECT COALESCE(pxp.porcentaje, 0) AS descuento FROM promocionesxproducto pxp WHERE pxp.producto_id = ? AND pxp.estado_activo=1 LIMIT 1");
                        $sql->execute([$producto['id']]);
                        $descuento = $sql->fetchColumn();
                        $precio_desc = $producto['precio'] - (($producto['precio'] * $descuento) / 100);
                        ?>
                        <h4 class="text-lg font-bold text-gray-900">$<?php echo htmlspecialchars(number_format($precio_desc, 2)); ?></h4>
                    </div>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</section>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
       function addProducto(id, token) {
        let url = 'clases/carrito.php';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('token', token);

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                // Actualiza el número de productos en el carrito
                let elemento = document.getElementById("num_cart");
                if (elemento) {
                    elemento.innerHTML = data.numero;
                }
            } else {
                console.error('Error al agregar el producto al carrito:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
        });
    }

        function buyNow(id, token) {
            addProducto(id, token);
            // Redirige al carrito después de agregar el producto
            setTimeout(() => {
                window.location.href = 'cart.php';
            }, 500); // Tiempo para que el producto se agregue al carrito antes de redirigir
        }
    </script>



    <?php require 'includes/footer.php'; ?>
    <script src="script.js"></script>
</body>

</html>