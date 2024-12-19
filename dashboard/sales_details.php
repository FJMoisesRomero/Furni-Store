<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$current_page = 'sales.php';
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gestión</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.0/css/buttons.bootstrap4.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f4f6f9;
        }
        .main-header {
            background-color: #343a40;
        }
        .brand-link {
            background-color: #343a40;
            color: #ffffff !important;
            font-weight: bold;
        }
        .nav-sidebar .nav-link {
            color: #ffffff;
        }
        .nav-sidebar .nav-link.active {
            background-color: #007bff;
            color: #ffffff;
        }
        .nav-sidebar .nav-link:hover {
            background-color: #6c757d;
            color: #ffffff;
        }
        .custom-select.custom-select-sm.form-control.form-control-sm {
            width: 50px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php require '../includes/sidebar.php'; ?>
<div class="wrapper">
    <div class="content-wrapper mb-4">                    
        <div class="col-sm-12 text-left my-4">
            <a href="sales.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i>Volver a Ventas</a>
        </div>    

        <div class="content ml-4 mr-4">
            <div class="container-fluid">
                <div class="col-md-8">
                    <h1 class="my-2">Detalles de la Venta</h1>
                </div>
                <?php
                if (count($result) > 0) {
                    $venta = $result[0];
                    $total = 0;
                ?>
                    <div class=" rounded-lg shadow-lg p-4 mb-4">
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
                    <div class=" rounded-lg shadow-lg p-4">
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
