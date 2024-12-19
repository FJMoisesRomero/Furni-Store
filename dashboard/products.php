<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
$current_page = 'products.php';
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
                        <i class="fas fa-box fa-2x mr-2" style="color: #4F46E5;"></i>
                        <h1 style="font-weight: bolder; font-size: 2.5rem; background: linear-gradient(to right, #4F46E5, #662D91); -webkit-background-clip: text; color: transparent; width: 100%;">Gestión de Productos</h1>
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
                                <li class="nav-item"><a class="active nav-link bg-white-200 hover:bg-white-300" href="#productos" data-toggle="tab">Productos</a></li>
                                <li class="nav-item"><a class="nav-link bg-white-200 hover:bg-white-300" href="#promocionesxproducto" data-toggle="tab">Promociones</a></li>
                                <li class="nav-item"><a class="nav-link bg-white-200 hover:bg-white-300" href="#marcas" data-toggle="tab">Marcas</a></li>
                                <li class="nav-item"><a class="nav-link bg-white-200 hover:bg-white-300" href="#categorias" data-toggle="tab">Categorías</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="productos">
                                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#modalAgregarProducto">
                                        <i class="fas fa-plus"></i> Nuevo Producto
                                    </button>

                                    <div id="table-wrapper">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Imagen</th>
                                                    <th>Nombre</th>
                                                    <th>Descripción</th>
                                                    <th>Marca</th>
                                                    <th>Categoría</th>
                                                    <th>Precio</th>
                                                    <th>Stock</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT productos.*, marcas.nombre as marca_nombre, categorias.nombre as categoria_nombre FROM productos JOIN marcas ON productos.marca_id = marcas.id JOIN categorias ON productos.categoria_id = categorias.id";
                                                $resultado = $conn->query($sql);
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                                    <td><img src="<?php echo htmlspecialchars($fila['imagen_url']); ?>" width="50" loading="lazy"></td>
                                                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['marca_nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['categoria_nombre']); ?></td>
                                                    <td>$<?php echo number_format($fila['precio'], 2, ',', '.'); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['stock']); ?></td>
                                                    <td>
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input" id="productos-estado-<?php echo htmlspecialchars($fila['id']); ?>" onchange="cambiarEstado(<?php echo htmlspecialchars($fila['id']); ?>, this.checked, 'productos')" <?php echo ($fila['estado_activo'] == 1) ? 'checked' : ''; ?>>
                                                            <label class="custom-control-label" for="productos-estado-<?php echo htmlspecialchars($fila['id']); ?>">
                                                                <?php echo ($fila['estado_activo'] == 1) ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>'; ?>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning" onclick="abrirModalEditar(<?php echo htmlspecialchars($fila['id']); ?>, '<?php echo addslashes(htmlspecialchars($fila['nombre'])); ?>', '<?php echo addslashes(htmlspecialchars($fila['descripcion'])); ?>', <?php echo htmlspecialchars($fila['stock']); ?>, <?php echo htmlspecialchars($fila['precio']); ?>, '<?php echo addslashes(htmlspecialchars($fila['imagen_url'])); ?>')">Editar</button>
                                                    </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Imagen</th>
                                                    <th>Nombre</th>
                                                    <th>Descripción</th>
                                                    <th>Marca</th>
                                                    <th>Categoría</th>
                                                    <th>Precio</th>
                                                    <th>Stock</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="promocionesxproducto">
                                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#modalAgregarPromocion">
                                        <i class="fas fa-plus"></i> Nueva Promoción
                                    </button>

                                    <div id="table-wrapper">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Producto</th>
                                                    <th>Porcentaje</th>
                                                    <th>Válida hasta</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT pxp.id, pxp.porcentaje, pxp.valido_hasta, p.nombre as producto_nombre, pxp.estado_activo FROM promocionesxproducto pxp JOIN productos p ON pxp.producto_id = p.id";
                                                $resultado = $conn->query($sql);
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['producto_nombre']); ?></td>
                                                    <td style="background-color: #dff0d8;"><?php echo htmlspecialchars($fila['porcentaje']); ?>%</td>
                                                    <td style="color:<?php
                                                        $validoHasta = new DateTime($fila['valido_hasta']);
                                                        $hoy = new DateTime('now');
                                                        if ($hoy > $validoHasta) {
                                                            echo 'red';
                                                        } else if ($hoy->diff($validoHasta)->days < 7) {
                                                            echo 'orange';
                                                        } else {
                                                            echo 'black';
                                                        }
                                                    ?>; font-weight: bold">
                                                        <?php
                                                        if ($hoy > $validoHasta) {
                                                            echo htmlspecialchars(date('d-m-Y', strtotime($fila['valido_hasta']))) . '<br><span style="color:red; font-weight: bold">Vencida</span>';
                                                        } else if ($hoy->diff($validoHasta)->days < 7) {
                                                            echo htmlspecialchars(date('d-m-Y', strtotime($fila['valido_hasta']))) . '<br><span style="color:orange; font-weight: bold">¡Próximo a Vencer!</span>';
                                                        } else {
                                                            echo htmlspecialchars(date('d-m-Y', strtotime($fila['valido_hasta'])));
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input" id="promocionesxproducto-estado-<?php echo htmlspecialchars($fila['id']); ?>" onchange="cambiarEstado(<?php echo htmlspecialchars($fila['id']); ?>, this.checked, 'promocionesxproducto')" <?php echo ($fila['estado_activo'] == 1) ? 'checked' : ''; ?>>
                                                            <label class="custom-control-label" for="promocionesxproducto-estado-<?php echo htmlspecialchars($fila['id']); ?>">
                                                                <?php echo ($fila['estado_activo'] == 1) ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>'; ?>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-warning" onclick="abrirModalEditarPromocion(<?php echo htmlspecialchars($fila['id']); ?>, '<?php echo htmlspecialchars($fila['porcentaje']); ?>', '<?php echo htmlspecialchars(date('d-m-Y', strtotime($fila['valido_hasta']))); ?>', '<?php echo htmlspecialchars($fila['producto_nombre']); ?>')">Editar</a>
                                                    </td>

                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Producto</th>
                                                    <th>Porcentaje</th>
                                                    <th>Válida hasta</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="marcas">
                                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#modalAgregarMarca">
                                        <i class="fas fa-plus"></i> Nueva Marca
                                    </button>
                                    <div id="table-wrapper">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM marcas";
                                                $resultado = $conn->query($sql);
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                                    <td>
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input" id="marcas-estado-<?php echo htmlspecialchars($fila['id']); ?>" onchange="cambiarEstado(<?php echo htmlspecialchars($fila['id']); ?>, this.checked, 'marcas')" <?php echo ($fila['estado_activo'] == 1) ? 'checked' : ''; ?>>
                                                            <label class="custom-control-label" for="marcas-estado-<?php echo htmlspecialchars($fila['id']); ?>">
                                                                <?php echo ($fila['estado_activo'] == 1) ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>'; ?>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-warning" onclick="abrirModalEditarMarca(<?php echo htmlspecialchars($fila['id']); ?>, '<?php echo htmlspecialchars($fila['nombre']); ?>')">Editar</a>
                                                    </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="categorias">
                                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#modalAgregarCategoria">
                                        <i class="fas fa-plus"></i> Nueva Categoría
                                    </button>

                                    <div id="table-wrapper">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM categorias";
                                                $resultado = $conn->query($sql);
                                                while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($fila['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                                                    <td>
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input" id="categorias-estado-<?php echo htmlspecialchars($fila['id']); ?>" onchange="cambiarEstado(<?php echo htmlspecialchars($fila['id']); ?>, this.checked, 'categorias')" <?php echo ($fila['estado_activo'] == 1) ? 'checked' : ''; ?>>
                                                            <label class="custom-control-label" for="categorias-estado-<?php echo htmlspecialchars($fila['id']); ?>">
                                                                <?php echo ($fila['estado_activo'] == 1) ? '<span class="text-success">Activado</span>' : '<span class="text-danger">Desactivado</span>'; ?>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-warning" onclick="abrirModalEditarCategoria(<?php echo htmlspecialchars($fila['id']); ?>, '<?php echo htmlspecialchars($fila['nombre']); ?>')">Editar</a>
                                                    </td>

                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Estado</th>
                                                    <th>Editar</th>
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

<!-- Modal para editar productos -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarProducto">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editarProductoId">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editarNombre">Nombre</label>
                                <input type="text" class="form-control" id="editarNombre" required>
                            </div>
                            <div class="form-group">
                                <label for="editarDescripcion">Descripción</label>
                                <textarea class="form-control" id="editarDescripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editarMarca">Marca</label>
                                <select class="form-control" id="editarMarca" required>
                                    <?php
                                    $sqlMarcas = "SELECT * FROM marcas";
                                    $resultadoMarcas = $conn->query($sqlMarcas);
                                    while ($marca = $resultadoMarcas->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . htmlspecialchars($marca['id']) . '">' . htmlspecialchars($marca['nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editarCategoria">Categoría</label>
                                <select class="form-control" id="editarCategoria" required>
                                    <?php
                                    $sqlCategorias = "SELECT * FROM categorias";
                                    $resultadoCategorias = $conn->query($sqlCategorias);
                                    while ($categoria = $resultadoCategorias->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . htmlspecialchars($categoria['id']) . '">' . htmlspecialchars($categoria['nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editarStock">Stock</label>
                                <input type="number" class="form-control" id="editarStock" required>
                            </div>
                            <div class="form-group">
                                <label for="editarPrecio">Precio</label>
                                <input type="number" class="form-control" id="editarPrecio" required>
                            </div>
                            <div class="form-group">
                                <label for="editarImagen">URL de Imagen</label>
                                <input type="text" class="form-control" id="editarImagen" required>
                                <img id="imagenPreview" src="" alt="Previsualización" style="max-width: 100%; display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal para agregar productos -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAgregarProducto">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarProductoLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agregarNombre">Nombre</label>
                                <input type="text" class="form-control" id="agregarNombre" required>
                            </div>
                            <div class="form-group">
                                <label for="agregarDescripcion">Descripción</label>
                                <textarea class="form-control" id="agregarDescripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="agregarMarca">Marca</label>
                                <select class="form-control" id="agregarMarca" required>
                                    <?php
                                    // Obtener marcas desde la base de datos
                                    $sqlMarcas = "SELECT * FROM marcas WHERE estado_activo = 1";
                                    $resultadoMarcas = $conn->query($sqlMarcas);
                                    while ($marca = $resultadoMarcas->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . htmlspecialchars($marca['id']) . '">' . htmlspecialchars($marca['nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="agregarCategoria">Categoría</label>
                                <select class="form-control" id="agregarCategoria" required>
                                    <?php
                                    // Obtener categorías desde la base de datos
                                    $sqlCategorias = "SELECT * FROM categorias WHERE estado_activo = 1";
                                    $resultadoCategorias = $conn->query($sqlCategorias);
                                    while ($categoria = $resultadoCategorias->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . htmlspecialchars($categoria['id']) . '">' . htmlspecialchars($categoria['nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agregarStock">Stock</label>
                                <input type="number" class="form-control" id="agregarStock" required>
                            </div>
                            <div class="form-group">
                                <label for="agregarPrecio">Precio</label>
                                <input type="number" class="form-control" id="agregarPrecio" required>
                            </div>
                            <div class="form-group">
                                <label for="agregarImagen">URL de Imagen</label>
                                <input type="text" class="form-control" id="agregarImagen" required oninput="previsualizarImagenAgregar()">
                                <img id="imagenPreviewAgregar" src="" alt="Previsualización" style="max-width: 100%; display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnAgregarProducto">
                        Agregar Producto <span id="cargando" style="display: none;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal para editar marcas -->
<div class="modal fade" id="modalEditarMarca" tabindex="-1" aria-labelledby="modalEditarMarcaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarMarca">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarMarcaLabel">Editar Marca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editarMarcaId">
                    <div class="form-group">
                        <label for="editarMarcaNombre">Nombre</label>
                        <input type="text" class="form-control" id="editarMarcaNombre" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal para agregar marcas -->
<div class="modal fade" id="modalAgregarMarca" tabindex="-1" aria-labelledby="modalAgregarMarcaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAgregarMarca">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarMarcaLabel">Agregar Marca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="agregarNombreMarca">Nombre</label>
                        <input type="text" class="form-control" id="agregarNombreMarca" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnAgregarMarca">
                        Agregar Marca <span id="cargandoMarca" style="display: none;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar categorías -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarCategoria">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarCategoriaLabel">Editar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editarCategoriaId">
                    <div class="form-group">
                        <label for="editarCategoriaNombre">Nombre</label>
                        <input type="text" class="form-control" id="editarCategoriaNombre" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal para agregar categorías -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1" aria-labelledby="modalAgregarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAgregarCategoria">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarCategoriaLabel">Agregar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="agregarNombreCategoria">Nombre</label>
                        <input type="text" class="form-control" id="agregarNombreCategoria" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnAgregarCategoria">
                        Agregar Categoría <span id="cargandoCategoria" style="display: none;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar promociones -->
<div class="modal fade" id="modalEditarPromocion" tabindex="-1" aria-labelledby="modalEditarPromocionLabel" aria-hidden="true">
    <div class="modal-dialog">
    <form id="formEditarPromocion">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarPromocionLabel">Editar Promoción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editarPromocionId">
                <div class="form-group">
                    <label for="editarPorcentajePromocion">Porcentaje</label>
                    <input type="number" class="form-control" id="editarPorcentajePromocion" required min="1" max="100">
                </div>
                <div class="form-group">
                    <label for="editarValidoHastaPromocion">Válido hasta</label>
                    <input type="date" class="form-control" id="editarValidoHastaPromocion" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="editarProductoPromocion">Producto</label>
                    <input type="text" class="form-control" id="editarProductoPromocion" readonly>
                    <input type="hidden" id="editarProductoId">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </form>
    </div>
</div>
<!-- Modal para agregar promociones -->
<div class="modal fade" id="modalAgregarPromocion" tabindex="-1" aria-labelledby="modalAgregarPromocionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAgregarPromocion">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarPromocionLabel">Agregar Promoción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="agregarPorcentajePromocion">Porcentaje</label>
                        <input type="number" class="form-control" id="agregarPorcentajePromocion" required min="1" max="100">
                    </div>
                    <div class="form-group">
                        <label for="agregarValidoHastaPromocion">Válido hasta</label>
                        <input type="date" class="form-control" id="agregarValidoHastaPromocion" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="agregarProductoPromocion">Producto</label>
                        <select class="form-control" id="agregarProductoPromocion" required>
                            <?php
                            $sqlProductos = "SELECT p.id, p.nombre FROM productos p LEFT JOIN promocionesxproducto pxp ON p.id = pxp.producto_id WHERE pxp.producto_id IS NULL AND p.estado_activo = 1";
                            $resultadoProductos = $conn->query($sqlProductos);
                            while ($producto = $resultadoProductos->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . htmlspecialchars($producto['id']) . '">' . htmlspecialchars($producto['nombre']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnAgregarPromocion">
                        Agregar Promoción <span id="cargandoPromocion" style="display: none;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
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

<script async defer>
    $(function () {
        function initializeDataTable(selector) {
            $(selector).DataTable({
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
        }

        initializeDataTable('#productos .table');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            initializeDataTable(target + ' .table');
        });
    });

    function cambiarEstado(id, estado, tabla) {
        const nuevoEstado = estado ? 1 : 0;
        const label = document.querySelector(`label[for="${tabla}-estado-${id}"]`);
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `Vas a ${nuevoEstado === 1 ? 'activar' : 'desactivar'} este elemento.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('products_change_state.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id, estado: nuevoEstado, tabla: tabla })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        label.innerHTML = nuevoEstado === 1 
                            ? '<span class="text-success">Activado</span>' 
                            : '<span class="text-danger">Desactivado</span>';
                        
                        Swal.fire('Actualizado', 'El estado se ha actualizado correctamente.', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'No se pudo realizar la solicitud.', 'error');
                });
            } else {
                document.getElementById(`${tabla}-estado-${id}`).checked = !estado;
            }
        });
    }
</script>
<script async defer>
    // Productos
    function abrirModalEditar(productoId, nombre, descripcion, stock, precio, imagenUrl) {
        // Asignar los valores directamente
        document.getElementById('editarProductoId').value = productoId;
        document.getElementById('editarNombre').value = nombre;
        document.getElementById('editarDescripcion').value = descripcion;
        document.getElementById('editarStock').value = stock;
        document.getElementById('editarPrecio').value = precio;
        document.getElementById('editarImagen').value = imagenUrl;
        
        // Mostrar previsualización de imagen
        const imagenPreview = document.getElementById('imagenPreview');
        imagenPreview.src = imagenUrl;
        imagenPreview.style.display = 'block';
        
        // Abrir modal
        $('#modalEditar').modal('show');
    }
    document.getElementById('formEditarProducto').addEventListener('submit', function(e) {
        e.preventDefault();
        const productoId = document.getElementById('editarProductoId').value;
        const nombre = document.getElementById('editarNombre').value;
        const descripcion = document.getElementById('editarDescripcion').value;
        const stock = document.getElementById('editarStock').value;
        const precio = document.getElementById('editarPrecio').value;
        const imagenUrl = document.getElementById('editarImagen').value;
        const marcaId = document.getElementById('editarMarca').value;
        const categoriaId = document.getElementById('editarCategoria').value;

        // Realizar la actualización
        fetch('products_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: productoId,
                nombre: nombre,
                descripcion: descripcion,
                stock: stock,
                precio: precio,
                imagen_url: imagenUrl,
                marca_id: marcaId,
                categoria_id: categoriaId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'El producto ha sido actualizado correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalEditar').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo actualizar el producto.', 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });
    document.getElementById('formAgregarProducto').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Obtener el botón y el ícono de carga
        const btnAgregar = document.getElementById('btnAgregarProducto');
        const cargando = document.getElementById('cargando');

        // Desactivar el botón y mostrar el ícono de carga
        btnAgregar.disabled = true;
        cargando.style.display = 'inline-block';

        const nombre = document.getElementById('agregarNombre').value;
        const descripcion = document.getElementById('agregarDescripcion').value;
        const stock = document.getElementById('agregarStock').value;
        const precio = document.getElementById('agregarPrecio').value;
        const imagenUrl = document.getElementById('agregarImagen').value;
        const marcaId = document.getElementById('agregarMarca').value;
        const categoriaId = document.getElementById('agregarCategoria').value;

        // Realizar la adición
        fetch('products_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombre,
                descripcion: descripcion,
                stock: stock,
                precio: precio,
                imagen_url: imagenUrl,
                marca_id: marcaId,
                categoria_id: categoriaId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Reactivar el botón y ocultar el ícono de carga
            btnAgregar.disabled = false;
            cargando.style.display = 'none';

            if (data.success) {
                Swal.fire('Agregado', 'El producto ha sido agregado correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalAgregarProducto').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo agregar el producto.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Reactivar el botón y ocultar el ícono de carga en caso de error
            btnAgregar.disabled = false;
            cargando.style.display = 'none';
        });
    });
    // Función para previsualizar la imagen al agregar
    function previsualizarImagenAgregar() {
        const imagenUrl = document.getElementById('agregarImagen').value;
        const imagenPreview = document.getElementById('imagenPreviewAgregar');
        if (imagenUrl) {
            imagenPreview.src = imagenUrl;
            imagenPreview.style.display = 'block';
        } else {
            imagenPreview.style.display = 'none';
        }
    }

    // Marcas
    function abrirModalEditarMarca(marcaId, nombre) {
        document.getElementById('editarMarcaId').value = marcaId;
        document.getElementById('editarMarcaNombre').value = nombre;
        $('#modalEditarMarca').modal('show');
    }
    document.getElementById('formEditarMarca').addEventListener('submit', function(e) {
        e.preventDefault();
        const marcaId = document.getElementById('editarMarcaId').value;
        const nombre = document.getElementById('editarMarcaNombre').value;

        fetch('marca_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: marcaId,
                nombre: nombre
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'La marca ha sido actualizada correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalEditarMarca').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo actualizar la marca.', 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });
    document.getElementById('formAgregarMarca').addEventListener('submit', function(e) {
        e.preventDefault();

        const btnAgregarMarca = document.getElementById('btnAgregarMarca');
        const cargandoMarca = document.getElementById('cargandoMarca');

        btnAgregarMarca.disabled = true;
        cargandoMarca.style.display = 'inline-block';

        const nombreMarca = document.getElementById('agregarNombreMarca').value;

        // Realizar la adición
        fetch('marca_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombreMarca
            })
        })
        .then(response => response.json())
        .then(data => {
            btnAgregarMarca.disabled = false;
            cargandoMarca.style.display = 'none';

            if (data.success) {
                Swal.fire('Agregado', 'La marca ha sido agregada correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalAgregarMarca').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo agregar la marca.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btnAgregarMarca.disabled = false;
            cargandoMarca.style.display = 'none';
        });
    });

    // Categorías
    function abrirModalEditarCategoria(categoriaId, nombre) {
        document.getElementById('editarCategoriaId').value = categoriaId;
        document.getElementById('editarCategoriaNombre').value = nombre;
        $('#modalEditarCategoria').modal('show');
    }
    document.getElementById('formEditarCategoria').addEventListener('submit', function(e) {
        e.preventDefault();
        const categoriaId = document.getElementById('editarCategoriaId').value;
        const nombre = document.getElementById('editarCategoriaNombre').value;

        fetch('categoria_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: categoriaId,
                nombre: nombre
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'La categoría ha sido actualizada correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalEditarCategoria').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo actualizar la categoría.', 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });
    document.getElementById('formAgregarCategoria').addEventListener('submit', function(e) {
        e.preventDefault();

        const btnAgregarCategoria = document.getElementById('btnAgregarCategoria');
        const cargandoCategoria = document.getElementById('cargandoCategoria');

        btnAgregarCategoria.disabled = true;
        cargandoCategoria.style.display = 'inline-block';

        const nombreCategoria = document.getElementById('agregarNombreCategoria').value;

        // Realizar la adición
        fetch('categoria_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombre: nombreCategoria
            })
        })
        .then(response => response.json())
        .then(data => {
            btnAgregarCategoria.disabled = false;
            cargandoCategoria.style.display = 'none';

            if (data.success) {
                Swal.fire('Agregado', 'La categoría ha sido agregada correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalAgregarCategoria').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo agregar la categoría.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btnAgregarCategoria.disabled = false;
            cargandoCategoria.style.display = 'none';
        });
    });

    // Promociones
    function abrirModalEditarPromocion(promocionId, porcentaje, validoHasta, productoId) {
        document.getElementById('editarPromocionId').value = promocionId;
        document.getElementById('editarPorcentajePromocion').value = porcentaje;
        document.getElementById('editarValidoHastaPromocion').value = validoHasta;
        document.getElementById('editarProductoPromocion').value = productoId;
        $('#modalEditarPromocion').modal('show');
    }
    document.getElementById('formEditarPromocion').addEventListener('submit', function(e) {
        e.preventDefault();

        const promocionId = document.getElementById('editarPromocionId').value;
        const porcentaje = document.getElementById('editarPorcentajePromocion').value;
        const validoHasta = document.getElementById('editarValidoHastaPromocion').value;

        fetch('promocion_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: promocionId,
                porcentaje: porcentaje,
                valido_hasta: validoHasta
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'La promoción ha sido actualizada correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalEditarPromocion').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo actualizar la promoción.', 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    document.getElementById('formAgregarPromocion').addEventListener('submit', function(e) {
        e.preventDefault();

        const btnAgregarPromocion = document.getElementById('btnAgregarPromocion');
        const cargandoPromocion = document.getElementById('cargandoPromocion');

        btnAgregarPromocion.disabled = true;
        cargandoPromocion.style.display = 'inline-block';

        const porcentaje = document.getElementById('agregarPorcentajePromocion').value;
        const validoHasta = document.getElementById('agregarValidoHastaPromocion').value;
        const productoId = document.getElementById('agregarProductoPromocion').value;

        // Realizar la adición
        fetch('promocion_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                porcentaje: porcentaje,
                valido_hasta: validoHasta,
                producto_id: productoId
            })
        })
        .then(response => response.json())
        .then(data => {
            btnAgregarPromocion.disabled = false;
            cargandoPromocion.style.display = 'none';

            if (data.success) {
                Swal.fire('Agregado', 'La promoción ha sido agregada correctamente.', 'success')
                .then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
                $('#modalAgregarPromocion').modal('hide');
            } else {
                Swal.fire('Error', 'No se pudo agregar la promoción.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btnAgregarPromocion.disabled = false;
            cargandoPromocion.style.display = 'none';
        });
    });

</script>
</body>
</html>
