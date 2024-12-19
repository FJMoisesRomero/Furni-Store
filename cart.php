<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT p.id, p.imagen_url, p.nombre, p.descripcion, m.nombre AS marca, c.nombre AS categoria, p.stock, p.precio, p.estado_activo, COALESCE(pxp.porcentaje, 0) AS Descuento_Online, ? AS cantidad 
        FROM productos p
        JOIN marcas m ON p.marca_id = m.id
        JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN promocionesxproducto pxp ON p.id = pxp.producto_id
        WHERE p.id=? AND p.estado_activo=1");
        $sql->execute([$cantidad, $clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC); // Usamos fetchAll() en lugar de fetch()
    }
}

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

<section class="bg-black">
	<div class="bg-black text-white py-8 flex flex-row items-center justify-between ml-8 mr-8">
		<div class="flex flex-col w-full lg:w-1/3 justify-center items-start p-8">
			<h1 class="text-3xl md:text-5xl p-2 text-yellow-300 tracking-loose"> Carrito</h1>
			<h2 class="text-3xl md:text-5xl leading-relaxed md:leading-snug mb-2">Revisa tu carrito de compras
			</h2>
			<p class="text-sm md:text-base text-gray-50 mb-4">Verifica que todos los productos esten correctos y
				realiza tu pago.</p>

		</div>
		<div class="hidden lg:block ml-12 mr-12">
			<img src="https://olimpica.vtexassets.com/arquivos/ids/1111949/image-486e019f407a4255a7524ac2243a6986.jpg?v=638222539195330000" class="w-full h-96 rounded-full" style="max-width: 400px; max-height: 400px;" />
		</div>
	</div>
</section>

<main>
    <div class="container">
        <div class="row">
            <!-- Sección de productos -->
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <td>Producto</td>
                                <td>Cantidad</td>
                                <td>Descuento</td>
                                <td>Subtotal</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($lista_carrito == null) {
                                echo '<tr><td colspan="4" class="text-center"><b>Lista vacía</b></td></tr>';
                            } else {
                                $total = 0;
                                $descuento_total = 0;
                                foreach ($lista_carrito as $producto) {
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['Descuento_Online'];
                                    $cantidad = $producto['cantidad'];
                                    $stock = $producto['stock'];
                                    $imagen = $producto['imagen_url'];
                                    
                                    // Precio con descuento
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                                    $descuento_total += ($cantidad * $precio * $descuento / 100);
                            ?>
                            <tr data-id="<?php echo $_id; ?>" data-nombre="<?php echo $nombre; ?>" data-precio="<?php echo $precio; ?>" data-descuento="<?php echo $descuento; ?>" data-cantidad="<?php echo $cantidad; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $imagen ?>" alt="<?php echo $nombre; ?>" class="img-fluid img-thumbnail" style="width: 80px; height: 80px; object-fit: cover; margin-right: 10px;">
                                        <div>
                                            <p><?php echo $nombre; ?></p>
                                            <span class="text-success">En Stock</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" min="1" max="<?php echo $stock; ?>" step="1" value="<?php echo $cantidad ?>" size="5" 
                                    id="cantidad_<?php echo $_id; ?>" class="form-control" 
                                    onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                                    <small>Disponible: <?php echo $stock; ?></small>
                                </td>
                                <td>
                                    <small><?php echo $descuento; ?>% descuento</small>
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                        <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" id="eliminar" class="btn btn-danger btn-sm" 
                                    data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" 
                                    data-bs-target="#eliminaModal">Eliminar</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">
                                    <p class="h3" id="total">Total: <?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                </td>
                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <!-- Sección de resumen -->
            <div class="col-lg-4">
                <div class="card p-3 shadow-sm">
                    <h5 class="card-title">Resumen de compra</h5>
                    <?php if ($lista_carrito == null) { ?>
                        <div class="alert alert-warning" role="alert">
                            No hay productos en el carrito. ¡Agrega algunos productos para continuar!
                        </div>
                    <?php } else { ?>
                        <div class="mb-3">
                            <label for="codigo_promocion" class="form-label">Código de Promoción</label>
                            <input type="text" class="form-control" id="codigo_promocion" placeholder="Ingresa tu código">
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <strong><?php echo MONEDA . number_format($total + $descuento_total, 2, '.', ','); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Descuento</span>
                                <strong>-<?php echo MONEDA . number_format($descuento_total, 2, '.', ','); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Envío</span>
                                <strong>$0.00</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Impuestos</span>
                                <strong>$0.00</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total</span>
                                <strong><?php echo MONEDA . number_format($total, 2, '.', ','); ?></strong>
                            </li>
                        </ul>
                        <button id="btn-realizar-pago" class="btn btn-primary btn-lg btn-block mt-3">Proceder al pago</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</main>
<style>
    img.img-thumbnail {
    border-radius: 8px;
    }

    .card {
        border: 1px solid #f0f0f0;
        border-radius: 10px;
        background-color: #fff;
    }

    .table {
        border-spacing: 0 15px;
    }

    .table tbody tr {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 8px;
    }

    .table td {
        vertical-align: middle;
    }

    h5.card-title {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .list-group-item {
        font-size: 1rem;
        padding: 0.75rem 1.25rem;
    }

</style>

<!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Está seguro que desea eliminar este producto?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal para completar la informacion de envio -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-blue-500 to-purple-500 text-white">
                <h5 class="modal-title" id="paymentModalLabel">Complete su Información de Envío</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-6 grid grid-cols-2 gap-4">
                <div>
                    <h2 class="text-2xl font-bold mb-4">Información de Envío</h2>

                    <form id="payment-form" class="space-y-4">
                        <!-- Nombre del cliente -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                            $stmt = $con->prepare("SELECT ClienteNombre FROM clientes WHERE id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            $cliente = $stmt->fetch();
                        ?>
                        <div>
                            <label for="customer-name" class="block text-sm font-medium text-gray-700">Nombre del cliente</label>
                            <input type="text" id="customer-name" name="customer-name" value="<?= $cliente['ClienteNombre'] ?>" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" readonly>
                        </div>
                        <?php else: ?>
                        <div>
                            <label for="customer-name" class="block text-sm font-medium text-gray-700">Nombre del cliente</label>
                            <input type="text" id="customer-name" name="customer-name" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <?php endif; ?>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" id="address" name="address" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
                            <input type="text" id="city" name="city" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- País -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">País</label>
                            <select id="country" name="country" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Seleccione un país</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Brasil">Brasil</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">Información de Pago</h2>

                    <form id="payment-info-form" class="space-y-4">
                        <!-- Card Number -->
                        <div>
                            <label for="card-number" class="block text-sm font-medium text-gray-700">Número de tarjeta</label>
                            <input type="text" id="card-number" name="card-number" required maxlength="16"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Expiration Date and CVV -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="expiry-date" class="block text-sm font-medium text-gray-700">Fecha de vencimiento (MM/AA)</label>
                                <input type="text" id="expiry-date" name="expiry-date" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="MM/AA">
                            </div>
                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" id="cvv" name="cvv" required maxlength="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="CVV">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btn-confirmar-pago" type="submit" class="btn bg-gradient-to-r from-green-500 to-blue-500 text-white" form="payment-info-form">Pagar Ahora</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
    <!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- Script to trigger modal -->
    <script>
    document.getElementById('btn-realizar-pago').addEventListener('click', function () {
        if (<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
            var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        } else {
            alert("Por favor, inicia sesión para completar tu compra");
        }
    });
</script>

<script>
    let eliminaModal = document.getElementById('eliminaModal');
    eliminaModal.addEventListener('show.bs.modal', function(event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina');
        buttonElimina.value = id;
    });

    function actualizaCantidad(cantidad, id) {
        let url = 'clases/actualizar_carrito.php';
        let formData = new FormData();
        formData.append('action', 'agregar');
        formData.append('id', id);
        formData.append('cantidad', cantidad);

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch(error => console.error(error));
    }

    function eliminar() {
        let url = 'clases/actualizar_carrito.php';
        let id = document.querySelector('#eliminaModal .modal-footer #btn-elimina').value;
        let formData = new FormData();
        formData.append('action', 'eliminar');
        formData.append('id', id);

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch(error => console.error(error));
    }

</script>

<script>
    document.getElementById('btn-confirmar-pago').addEventListener('click', function() {
        // Obtener los datos del formulario de envío
        const customerName = document.getElementById('customer-name').value;
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const country = document.getElementById('country').value;

        // Obtener los datos del carrito directamente de la tabla
        const productos = [];
        const rows = document.querySelectorAll('.table tbody tr'); // Selecciona todas las filas de la tabla

        rows.forEach(row => {
            const id = row.getAttribute('data-id');
            const nombre = row.getAttribute('data-nombre');
            const precio = parseFloat(row.getAttribute('data-precio'));
            const descuento = parseFloat(row.getAttribute('data-descuento'));
            const cantidad = parseInt(row.getAttribute('data-cantidad'));

            // Asegúrate de que la cantidad sea válida
            if (cantidad > 0) {
                productos.push({
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    Descuento_Online: descuento,
                    cantidad: cantidad
                });
            }
        });

        // Preparar datos para enviar
        const formData = new FormData();
        formData.append('customerName', customerName);
        formData.append('address', address);
        formData.append('city', city);
        formData.append('country', country);
        formData.append('clienteId', <?php echo $_SESSION['user_id']; ?>); // Agregar el ID del cliente
        formData.append('productos', JSON.stringify(productos)); // Convertir productos a JSON

        // Enviar datos al servidor
        fetch('registrar_venta.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Venta registrada exitosamente');
                window.location.href = 'success.php'; // Redirige a una página de agradecimiento
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            window.location.href = 'success.php'; // Redirige a una página de agradecimiento
            alert('Venta registrada exitosamente');

        });
    });
</script>

</body>
</html>

