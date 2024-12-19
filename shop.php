<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

// Obtén las marcas y categorías seleccionadas y el término de búsqueda
$selected_brands = isset($_GET['brand']) ? $_GET['brand'] : [];
$selected_categories = isset($_GET['category']) ? $_GET['category'] : [];
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Construye la consulta SQL con filtros
$sql = "SELECT p.id, p.imagen_url, p.nombre, p.descripcion, p.stock, p.precio, p.estado_activo, m.nombre as marca, c.nombre as categoria, pxp.porcentaje as descuento 
        FROM productos p
        JOIN marcas m ON p.marca_id = m.id
        JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN promocionesxproducto pxp ON p.id = pxp.producto_id
        WHERE p.estado_activo=1";
$conditions = [];
$params = [];

if (!empty($selected_brands)) {
    $placeholders = rtrim(str_repeat('?, ', count($selected_brands)), ', ');
    $conditions[] = "m.id IN ($placeholders)";
    $params = array_merge($params, $selected_brands);
}

if (!empty($selected_categories)) {
    $placeholders = rtrim(str_repeat('?, ', count($selected_categories)), ', ');
    $conditions[] = "c.id IN ($placeholders)";
    $params = array_merge($params, $selected_categories);
}

if ($search_term) {
    $conditions[] = "p.nombre LIKE ?";
    $params[] = "%$search_term%";
}

if ($conditions) {
    $sql .= " AND " . implode(' AND ', $conditions);
}

$stmt = $con->prepare($sql);
$stmt->execute($params);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtén las marcas únicas para el filtro
$brands_query = $con->query("SELECT DISTINCT m.id, m.nombre FROM marcas m JOIN productos p ON m.id = p.marca_id WHERE p.estado_activo = true");
$brands = $brands_query->fetchAll(PDO::FETCH_ASSOC);

// Obtén las categorías únicas para el filtro
$categories_query = $con->query("SELECT DISTINCT c.id, c.nombre FROM categorias c JOIN productos p ON c.id = p.categoria_id WHERE p.estado_activo = true");
$categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);
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

<!--Contenido-->
<!-- Sección de búsqueda y filtro -->
<section id="search-filter" class="section-p1 py-8">
    <div class="container">
        <div class="row">
            <!-- Sección de productos -->
            <div class="col-lg-9">
                <section id="products1" class="section-p1">
                    <div class="container d-flex justify-content-center">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-1">
                            <?php foreach($resultado as $row) { ?>
                            <div class="col d-flex justify-content-center">
                                <div class="pro">
                                    <?php 
                                    $id = $row['id'];
                                    $imagen = $row['imagen_url'];
                                    $precio = $row['precio'];
                                    $descuento = $row['descuento'] ?? 0;
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    ?> 
                                    <img src="<?php echo $imagen; ?>" class="w-48 h-48 object-cover rounded-xl shadow-lg hover:shadow-2xl transition duration-300">
                                    <div class="des">
                                        <span class="text-gray-500"><?php echo $row['marca']; ?></span>
                                        <h5 class="card-title font-bold"><strong><?php echo $row['nombre']; ?></strong></h5>
                                        <?php if ($descuento > 0) { ?>
                                            <p class="text-sm line-through text-gray-500"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></p>
                                            <h2 class="text-lg font-bold text-green-600">
                                                <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                                                <small class="text-xs"><?php echo $descuento; ?>% descuento</small>
                                            </h2>
                                        <?php } else { ?>
                                            <h2 class="text-lg font-bold text-gray-900">
                                                <?php echo MONEDA . number_format($precio, 2, '.', ','); ?>
                                            </h2>
                                        <?php } ?>
                                        <div class="flex flex-col justify-center">
                                            <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary hover:bg-blue-700 transition duration-300 w-full mb-2">
                                                <i class="fas fa-info-circle mr-2"></i> Detalles
                                            </a>
                                            <button class="btn btn-outline-success hover:bg-green-600 transition duration-300 w-full" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">
                                                <i class="fas fa-cart-plus mr-2"></i> Agregar al carrito
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Búsqueda y filtro -->
            <div class="col-lg-3 mb-4">
                <div class="search-filter-wrapper">
                    <!-- Búsqueda -->
                    <form method="GET" action="shop.php" class="d-flex flex-column mb-4">
                        <input type="text" name="search" class="form-control mb-3" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>

                    <!-- Filtro de marcas -->
                    <h5>Filtrar por marca:</h5>
                    <form method="GET" action="shop.php">
                        <?php foreach ($brands as $brand): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="brand[]" value="<?php echo htmlspecialchars($brand['id']); ?>" id="brand_<?php echo htmlspecialchars($brand['id']); ?>" <?php if (in_array($brand['id'], $selected_brands)) echo 'checked'; ?>>
                                <label class="form-check-label" for="brand_<?php echo htmlspecialchars($brand['id']); ?>">
                                    <?php echo htmlspecialchars($brand['nombre']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Filtro de categorías -->
                        <h5 class="mt-4">Filtrar por categoría:</h5>
                        <?php foreach ($categories as $category): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="category[]" value="<?php echo htmlspecialchars($category['id']); ?>" id="category_<?php echo htmlspecialchars($category['id']); ?>" <?php if (in_array($category['id'], $selected_categories)) echo 'checked'; ?>>
                                <label class="form-check-label" for="category_<?php echo htmlspecialchars($category['id']); ?>">
                                    <?php echo htmlspecialchars($category['nombre']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                        <button type="submit" class="btn btn-primary mt-2">Aplicar Filtros</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php require 'includes/footer.php'; ?>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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