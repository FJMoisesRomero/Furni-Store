<?php
require 'config/config.php';
require 'config/database.php';

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 1;


$hostname = "sql113.infinityfree.com";
$database = "if0_37585040_furni_store";
$username = "if0_37585040";
$password = "FurniStore44";
$charset = "utf8";

try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$ventaId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener la venta y sus detalles
$query = "
    SELECT v.*, c.ClienteNombre, c.ClienteEmail, c.ClienteTelefono, d.producto_id, d.producto_cantidad, d.producto_precio, p.id, p.nombre AS producto_nombre, m.nombre AS marca, cat.nombre AS categoria
    FROM ventas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN ventas_detalles d ON v.id = d.venta_id
    JOIN productos p ON d.producto_id = p.id
    JOIN marcas m ON p.marca_id = m.id
    JOIN categorias cat ON p.categoria_id = cat.id
    WHERE v.id = :ventaId
";
$stmt = $conn->prepare($query);
$stmt->bindValue(':ventaId', $ventaId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Iniciar el documento HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furni Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/54b6794846.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        /* Import Google font - Poppins */
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
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
        .overflow-x-auto {
            max-width: 100%; /* Asegúrate de que el contenedor tenga un ancho máximo */
        }
        tbody {
        display: block;
        max-height: 800px; /* Ajusta la altura según tus necesidades */
        overflow-y: auto;
        }
        tr {
            display: table;
            table-layout: fixed; /* Esto es importante para mantener el diseño de la tabla */
            width: 100%;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<?php require 'includes/header.php'; ?>
<div class="wrapper">
    <div class="content-wrapper mb-4">                    
        <div class=" text-left ml-8 my-4">
            <a href="profile.php" class="btn btn-sm" style="background: linear-gradient(to right, #7d2ae8, #0097fa); color: white;"><i class="fas fa-arrow-left mr-2"></i>Volver a Mi Perfil</a>
        </div>    

        <div class="content ml-4 mr-4">
            <div class="container-fluid">
                <div class="my-6 w-full text-center">
                    <h1 class="display-4 mx-auto my-2" style="background: linear-gradient(to right, #7d2ae8, #0097fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Detalles de la Compra</h1>
                </div>
                <?php
                if (count($result) > 0) {
                    $venta = $result[0];
                    $total = 0;
                ?>
                    <div class="bg-white rounded-lg shadow-lg p-4 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-lg font-bold">Código de Venta: <span class="text-gray-600"><?php echo htmlspecialchars($venta['codigo']); ?></span></p>
                                <p class="text-lg font-bold">Cliente: <span class="text-gray-600"><?php echo htmlspecialchars($venta['ClienteNombre']); ?></span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-lg font-bold">Email: <span class="text-gray-600"><?php echo htmlspecialchars($venta['ClienteEmail']); ?></span></p>
                                <p class="text-lg font-bold">Teléfono: <span class="text-gray-600"><?php echo htmlspecialchars($venta['ClienteTelefono']); ?></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg p-4">
                        <h2 class="text-2xl font-bold mb-4">Productos</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3">Producto</th>
                                    <th class="px-6 py-3">Marca</th>
                                    <th class="px-6 py-3">Categoría</th>
                                    <th class="px-6 py-3 text-center">Cantidad</th>
                                    <th class="px-6 py-3 text-right">Precio</th>
                                    <th class="px-6 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($result as $row) {
                                $subtotal = $row['producto_cantidad'] * $row['producto_precio'];
                                $total += $subtotal;
                                echo '<tr>';
                                echo '<td class="px-6 py-4">' . htmlspecialchars($row['producto_nombre']) . '</td>';
                                echo '<td class="px-6 py-4">' . htmlspecialchars($row['marca']) . '</td>';
                                echo '<td class="px-6 py-4">' . htmlspecialchars($row['categoria']) . '</td>';
                                echo '<td class="px-6 py-4 text-center">' . number_format($row['producto_cantidad'], 0, ',', '.') . '</td>';
                                echo '<td class="px-6 py-4 text-right">$ ' . number_format($row['producto_precio'], 2, ',', '.') . '</td>';
                                echo '<td class="px-6 py-4 text-right">$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right font-bold"></td>
                                    <td class="font-bold h4 text-right">Total: $ <?php echo number_format($total, 2, ',', '.'); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php
                } else {
                    echo '<p class="text-lg font-bold text-center">No se encontraron detalles para esta venta.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js" defer></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" defer></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/2.4.0/js/dataTables.buttons.min.js" defer></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn = null;
?>
