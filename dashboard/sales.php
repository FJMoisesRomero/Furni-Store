<?php 
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$current_page = 'sales.php';
?>

<!DOCTYPE html>
<html lang="en">
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="row mb-2">
                    <div class="col-sm-12 d-flex align-items-center ml-2">
                        <i class="fas fa-chart-line fa-2x mr-2" style="color: #2ECC71;"></i>
                        <h1 style="font-weight: bolder; font-size: 2.5rem; background: linear-gradient(to right, #2ECC71, #1ABC9C); -webkit-background-clip: text; color: transparent; width: 100%;">Gestión de Ventas</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header p-0">
                            <ul class="nav nav-pills" style="background: linear-gradient(to right, #4F46E5, #662D91); color: white;">
                                <li class="nav-item"><a class="nav-link active" href="#ventas" data-toggle="tab">Ventas</a></li>
                                <li class="nav-item"><a class="nav-link" href="#clientes" data-toggle="tab">Clientes</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="ventas">
                                    <div id="table-wrapper">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha</th>
                                                    <th>Método de Pago</th>
                                                    <th>Detalles</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT ventas.*, clientes.ClienteNombre AS cliente_nombre, metodos_de_pago.nombre AS metodo_pago_nombre 
                                                        FROM ventas 
                                                        JOIN clientes ON ventas.cliente_id = clientes.id 
                                                        JOIN metodos_de_pago ON ventas.metodo_de_pago_id = metodos_de_pago.id";
                                                $resultado = $conn->query($sql);
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['codigo']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['cliente_nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['metodo_pago_nombre']); ?></td>
                                                    <td>
                                                        <button class="ver-detalles btn btn-primary" data-id="<?php echo htmlspecialchars($fila['id']); ?>">Ver Detalles</button>
                                                    </td>

                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha</th>
                                                    <th>Método de Pago</th>
                                                    <th>Detalles</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="clientes">
                                    <div id="table-wrapper">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>DNI</th>
                                                    <th>Nombre</th>
                                                    <th>Email</th>
                                                    <th>Teléfono</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT id, ClienteDNI, ClienteNombre, ClienteEmail, ClienteTelefono, ClientePassword FROM clientes";
                                                $resultado = $conn->query($sql);
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['ClienteDNI']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['ClienteNombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['ClienteEmail']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['ClienteTelefono']); ?></td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>DNI</th>
                                                    <th>Nombre</th>
                                                    <th>Email</th>
                                                    <th>Teléfono</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesModalLabel">Detalles de la Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalles-wrapper">
                <!-- Aquí se cargarán los detalles -->
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

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "bDeferRender": true,
            "bDestroy": true,
            "order": [[0, "desc"]],
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "search": "Buscar:",
                "lengthMenu": "Mostrar <span>_MENU_</span> entradas",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                "loadingRecords": "Cargando...",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "No hay datos disponibles en la tabla",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // Manejo del evento para abrir el modal
        $(document).on('click', '.ver-detalles', function() {
            const ventaId = $(this).data('id'); // Obtener el id de venta desde el atributo data-id
            verDetalles(ventaId);
        });
    });

    function verDetalles(ventaId) {
        // Redirigir a la página de detalles de la venta
        window.location.href = 'sales_details.php?id=' + ventaId;
    }



</script>
</body>
</html>
