<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];

// Verificar si mostrar el mensaje de bienvenida
$mostrar_bienvenida = isset($_SESSION['mostrar_bienvenida']) && $_SESSION['mostrar_bienvenida'];

// Incluir el archivo de configuración de la base de datos
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
    $con = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Inicializar variables de rango de fechas
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

// Crear un filtro de fecha para las consultas SQL
$dateFilter = '';
if ($startDate && $endDate) {
    $dateFilter = " AND ventas.fecha BETWEEN :startDate AND :endDate";
}

// Obtener datos de ventas por mes
$sqlMes = "SELECT DATE_FORMAT(fecha, '%M-%Y') as mes, SUM(ventas_detalles.producto_precio * ventas_detalles.producto_cantidad) as total
           FROM ventas 
           JOIN ventas_detalles ON ventas.id = ventas_detalles.venta_id
           WHERE 1=1 $dateFilter
           GROUP BY mes
           ORDER BY mes";
$stmtMes = $con->prepare($sqlMes);
if ($startDate && $endDate) {
    $stmtMes->bindParam(':startDate', $startDate);
    $stmtMes->bindParam(':endDate', $endDate);
}
$stmtMes->execute();
$meses = [];
$ventasMes = [];
while ($fila = $stmtMes->fetch(PDO::FETCH_ASSOC)) {
    $meses[] = $fila['mes'];
    $ventasMes[] = $fila['total'];
}

// Obtener datos de los clientes que más compran
$sqlClientes = "SELECT clientes.ClienteNombre, SUM(ventas_detalles.producto_precio * ventas_detalles.producto_cantidad) as total
                FROM ventas 
                JOIN ventas_detalles ON ventas.id = ventas_detalles.venta_id
                JOIN clientes ON ventas.cliente_id = clientes.id
                WHERE 1=1 $dateFilter
                GROUP BY clientes.id
                ORDER BY total DESC
                LIMIT 5";
$stmtClientes = $con->prepare($sqlClientes);
if ($startDate && $endDate) {
    $stmtClientes->bindParam(':startDate', $startDate);
    $stmtClientes->bindParam(':endDate', $endDate);
}
$stmtClientes->execute();
$clientes = [];
$ventasClientes = [];
while ($fila = $stmtClientes->fetch(PDO::FETCH_ASSOC)) {
    $clientes[] = $fila['ClienteNombre'];
    $ventasClientes[] = $fila['total'];
}

// Obtener datos de los productos más vendidos
$sqlProductos = "SELECT productos.nombre, SUM(ventas_detalles.producto_precio * ventas_detalles.producto_cantidad) as total
                 FROM ventas 
                 JOIN ventas_detalles ON ventas.id = ventas_detalles.venta_id
                 JOIN productos ON ventas_detalles.producto_id = productos.id
                 WHERE 1=1 $dateFilter
                 GROUP BY productos.id
                 ORDER BY total DESC
                 LIMIT 5";
$stmtProductos = $con->prepare($sqlProductos);
if ($startDate && $endDate) {
    $stmtProductos->bindParam(':startDate', $startDate);
    $stmtProductos->bindParam(':endDate', $endDate);
}
$stmtProductos->execute();
$productos = [];
$ventasProductos = [];
while ($fila = $stmtProductos->fetch(PDO::FETCH_ASSOC)) {
    $productos[] = $fila['nombre'];
    $ventasProductos[] = $fila['total'];
}

// Obtener datos de las cantidades vendidas por producto
$sqlCantidades = "SELECT productos.nombre, SUM(ventas_detalles.producto_cantidad) as cantidad
                  FROM ventas 
                  JOIN ventas_detalles ON ventas.id = ventas_detalles.venta_id
                  JOIN productos ON ventas_detalles.producto_id = productos.id
                  WHERE 1=1 $dateFilter
                  GROUP BY productos.id
                  ORDER BY cantidad DESC
                  LIMIT 5";
$stmtCantidades = $con->prepare($sqlCantidades);
if ($startDate && $endDate) {
    $stmtCantidades->bindParam(':startDate', $startDate);
    $stmtCantidades->bindParam(':endDate', $endDate);
}
$stmtCantidades->execute();
$productosCantidades = [];
$cantidadesVendidas = [];
while ($fila = $stmtCantidades->fetch(PDO::FETCH_ASSOC)) {
    $productosCantidades[] = $fila['nombre'];
    $cantidadesVendidas[] = $fila['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gestión</title>

    <!-- DataTables CSS -->
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
            background-color: #808191;
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
    </style>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php if ($mostrar_bienvenida): ?>
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
            title: "Bienvenido al Sistema<br><?= htmlspecialchars($user['nombre']) ?><br><?= htmlspecialchars($user['apellido']) ?>"
        });
    </script>
<?php unset($_SESSION['mostrar_bienvenida']); endif; ?>

<?php require '../includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <i class="fas fa-tachometer-alt fa-2x mr-2" style="color: #f093fb;"></i>
                    <h1 style="font-weight: bolder; font-size: 2.5rem; background: linear-gradient(to right, #f093fb, #f5576c); -webkit-background-clip: text; color: transparent;">Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Date filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="startDate">Desde:</label>
                    <input type="date" id="startDate" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label for="endDate">Hasta:</label>
                    <input type="date" id="endDate" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button id="filterButton" class="btn btn-primary btn-block">Filtrar</button>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <button class="btn btn-secondary" onclick="setPeriod('today')">Hoy</button>
                    <button class="btn btn-secondary" onclick="setPeriod('last7days')">Últimos 7 Días</button>
                    <button class="btn btn-secondary" onclick="setPeriod('last15days')">Últimos 15 Días</button>
                    <button class="btn btn-secondary" onclick="setPeriod('lastmonth')">Último Mes</button>
                    <button class="btn btn-secondary" onclick="setPeriod('last3months')">Últimos 3 Meses</button>
                </div>
            </div>

            <!-- Chart placeholders -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card card-primary h-100">
                        <div class="card-header" style="background: linear-gradient(45deg, #f093fb, #f5576c); color: white; width: 100%;">
                            Ventas por Mes
                        </div>
                        <div class="card-body h-100 d-flex align-items-center justify-content-center">
                            <canvas id="ventasPorMes" style="height: 100%; width: 100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card card-primary h-100">
                        <div class="card-header" style="background: linear-gradient(45deg, #f093fb, #f5576c); color: white; width: 100%;">
                            Clientes que Más Compran
                        </div>
                        <div class="card-body h-100 d-flex align-items-center justify-content-center">
                            <canvas id="clientesMasCompran" style="height: 100%; width: 100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card card-primary h-100">
                        <div class="card-header" style="background: linear-gradient(45deg, #f093fb, #f5576c); color: white; width: 100%;">
                            Productos Más Vendidos
                        </div>
                        <div class="card-body h-100 d-flex align-items-center justify-content-center">
                            <canvas id="productosMasVendidos" style="height: 100%; width: 100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card card-primary h-100">
                        <div class="card-header" style="background: linear-gradient(45deg, #f093fb, #f5576c); color: white; width: 100%;">
                            Cantidades Vendidas
                        </div>
                        <div class="card-body h-100 d-flex align-items-center justify-content-center">
                            <canvas id="cantidadesVendidas" style="height: 100%; width: 100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const meses = <?php echo json_encode($meses); ?>;
    const ventasMes = <?php echo json_encode($ventasMes); ?>;
    const clientes = <?php echo json_encode($clientes); ?>;
    const ventasClientes = <?php echo json_encode($ventasClientes); ?>;
    const productos = <?php echo json_encode($productos); ?>;
    const ventasProductos = <?php echo json_encode($ventasProductos); ?>;
    const productosCantidades = <?php echo json_encode($productosCantidades); ?>;
    const cantidadesVendidas = <?php echo json_encode($cantidadesVendidas); ?>;

    // Gráfica de Ventas por Mes
    const ctx1 = document.getElementById('ventasPorMes').getContext('2d');
    const ventasChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Ventas Totales',
                data: ventasMes,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfica de Clientes que Más Compran
    const ctx2 = document.getElementById('clientesMasCompran').getContext('2d');
    const clientesChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: clientes,
            datasets: [{
                label: 'Clientes',
                data: ventasClientes,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                ],
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Gráfica de Productos Más Vendidos
    const ctx3 = document.getElementById('productosMasVendidos').getContext('2d');
    const productosChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: productos,
            datasets: [{
                label: 'Ventas por Producto',
                data: ventasProductos,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfica de Cantidades Vendidas
    const ctx4 = document.getElementById('cantidadesVendidas').getContext('2d');
    const cantidadesChart = new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: productosCantidades,
            datasets: [{
                label: 'Cantidad Vendida',
                data: cantidadesVendidas,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                ],
            }]
        },
        options: {
            responsive: true,
            cutoutPercentage: 50,
        }
    });
    document.getElementById('filterButton').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Actualizar la URL para incluir parámetros de rango de fechas
        const url = new URL(window.location.href);
        url.searchParams.set('startDate', startDate);
        url.searchParams.set('endDate', endDate);
        window.location.href = url; // Recargar la página con los nuevos parámetros
    });

    function setPeriod(period) {
        const today = new Date();
        let startDate, endDate;

        switch (period) {
            case 'today':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - 1);
                endDate = today;
                break;
            case 'last7days':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - 7);
                endDate = today;
                break;
            case 'last15days':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - 15);
                endDate = today;
                break;
            case 'lastmonth':
                startDate = new Date(today);
                startDate.setMonth(today.getMonth() - 1);
                endDate = today;
                break;
            case 'last3months':
                startDate = new Date(today);
                startDate.setMonth(today.getMonth() - 3);
                endDate = today;
                break;
        }

        document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
        document.getElementById('endDate').value = endDate.toISOString().split('T')[0];

        // Activar el filtrado
        document.getElementById('filterButton').click();
    }
</script>
</body>
</html>
