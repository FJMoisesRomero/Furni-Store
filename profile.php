<?php
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Obtener el user_id desde la sesión
$user_id = $_SESSION['user_id'];

// Inicializar variables
$cliente = [];
$ventas = [];

// Obtener información del cliente desde la base de datos
if ($user_id) {
    $sql = $con->prepare("SELECT id, ClienteDNI, ClienteNombre, ClienteEmail, ClienteTelefono, ClientePassword FROM clientes WHERE id = :user_id");
    $sql->bindParam(':user_id', $user_id);
    $sql->execute();
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);

    // Manejar el envío del formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $apellido = htmlspecialchars(trim($_POST['apellido']));
        $telefono = htmlspecialchars(trim($_POST['numero_telefono']));
        // Otros campos según sea necesario...
    
        $nombreCompleto = "$nombre $apellido";
        $sql_update = $con->prepare("UPDATE clientes SET ClienteNombre = :nombre, ClienteTelefono = :telefono, updated_at = CURRENT_TIMESTAMP WHERE id = :user_id");
    
        $sql_update->bindParam(':nombre', $nombreCompleto);
        $sql_update->bindParam(':telefono', $telefono);
        $sql_update->bindParam(':user_id', $user_id);
    
        if ($sql_update->execute()) {
            echo json_encode(['success' => true, 'message' => 'Los cambios se han guardado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ocurrió un error al guardar los cambios.']);
        }
        exit(); // Asegúrate de salir para no enviar más HTML
    }
    

    // Obtener el historial de ventas del cliente junto con detalles de envío y totales
    $sql_ventas = $con->prepare("
        SELECT v.id, v.codigo, v.fecha, mp.nombre AS metodo_pago_nombre 
        FROM ventas v
        JOIN clientes c ON v.cliente_id = c.id 
        JOIN metodos_de_pago mp ON v.metodo_de_pago_id = mp.id
        WHERE c.id = :cliente_id
        ORDER BY v.fecha DESC
    ");
    $sql_ventas->bindParam(':cliente_id', $cliente['id']);
    $sql_ventas->execute();
    $ventas = $sql_ventas->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Proveer valores predeterminados si no hay ID de usuario en la sesión
    $cliente = [
        'id' => '',
        'ClienteDNI' => '',
        'ClienteNombre' => '',
        'ClienteEmail' => '',
        'ClienteTelefono' => '',
        'ClientePassword' => '',
    ];
    $ventas = [];
}
?>
<!DOCTYPE html>
<html lang="en">

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

<body>
<body class="bg-white text-gray-800 transition duration-500 ease-in-out">
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section id="hero" class=" min-h-[200px] bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center">
        <div class="text-left w-full mx-10">
        <h1 class="text-5xl font-bold" style="color:white">Tu perfil</h1>
        <p class="mt-4 text-xl " style="color:white">Aquí podrás ver tu historial de compras y editar tus datos personales</p>
        </div>
    </section>
    <!-- Profile Section -->
    <section class="bg-gray-100 p-4 mb-0">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-8 max-w-full mx-auto">
            <!-- Historial de compras -->
            <div class="bg-white p-6 rounded-lg shadow-lg md:col-span-4 md:row-span-1">
                <h4 class="text-xl font-semibold pb-4 border-b">Historial de compras</h4>
                <div class="overflow-x-auto mt-4">
                    <div class="min-w-[800px]">
                        <table id="ventasTable" class="min-w-full leading-normal">
                            <thead class="bg-gray-100 sticky top-0 z-10">
                                <tr>
                                    <th class="px-2 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Código</th>
                                    <th class="px-2 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                    <th class="px-2 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Método de Pago</th>
                                    <th class="px-2 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($ventas) > 0): ?>
                                    <?php foreach ($ventas as $venta): ?>
                                        <tr>
                                            <td class="px-2 py-5 border-b border-gray-200 bg-white text-xs" style="font-size: 11px;"><?= htmlspecialchars($venta['codigo']) ?></td>
                                            <td class="px-2 py-5 border-b border-gray-200 bg-white text-xs" style="font-size: 11px;"><?= htmlspecialchars(date('d-m-Y', strtotime($venta['fecha']))) ?></td>
                                            <td class="px-2 py-5 border-b border-gray-200 bg-white text-xs" style="font-size: 11px;"><?= htmlspecialchars($venta['metodo_pago_nombre']) ?></td>
                                            <td class="px-2 py-5 border-b border-gray-200 bg-white text-xs">
                                                <a href="purchase_details.php?id=<?= htmlspecialchars($venta['id']); ?>" class="btn btn-info btn-sm">Ver Detalle</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-2 py-5 text-center">
                                            <i class="fas fa-box-open fa-4x text-gray-300"></i>
                                            <p class="text-gray-600 text-sm">Aún no se realizaron compras.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sección de perfil -->
            <div class="bg-white p-6 rounded-lg shadow-lg md:col-span-2 md:row-span-1">
                <h4 class="text-xl font-semibold pb-4 border-b">Configuración de la cuenta</h4>
                <div class="flex items-center py-4 border-b">
                    <img src="https://images.pexels.com/photos/1037995/pexels-photo-1037995.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500" class="w-16 h-16 rounded-full object-cover" alt="">
                    <div class="pl-4">
                        <b class="text-sm">Foto de perfil</b>
                        <p class="text-xs text-gray-500">Tipo de archivo aceptado: .png. Menos de 1MB</p>
                        <button class="text-sm text-blue-500">Subir</button>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="profileForm" class="mt-4 space-y-4">
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Nombre</label>
                            <input type="text" name="nombre" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" value="<?= htmlspecialchars(explode(' ', $cliente['ClienteNombre'])[0]) ?>">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Apellido</label>
                            <input type="text" name="apellido" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" value="<?= htmlspecialchars(explode(' ', $cliente['ClienteNombre'])[1]) ?>">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Correo electrónico</label>
                            <input type="email" readonly class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" value="<?= htmlspecialchars($cliente['ClienteEmail']) ?>">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Teléfono</label>
                            <input type="tel" name="numero_telefono" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" value="<?= htmlspecialchars($cliente['ClienteTelefono']) ?>">
                        </div>
                    </div>

                    <h4 class="text-xl font-semibold pb-4 border-b mt-12">Información de Envío</h4>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Dirección</label>
                            <input type="text" name="direccion" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" placeholder="Dirección">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Ciudad</label>
                            <input type="text" name="ciudad" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" placeholder="Ciudad">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Código Postal</label>
                            <input type="text" name="codigo_postal" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" placeholder="Código Postal">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-600">Notas adicionales</label>
                            <textarea name="notas" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg" rows="2" placeholder="Notas sobre la entrega..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg flex items-center justify-center" style="margin-top: 60px">
                        <i class="fas fa-save mr-2"></i> Guardar cambios
                    </button>
                </form>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script><!-- Incluir jQuery y DataTables -->

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#ventasTable').DataTable({
                 // Otras configuraciones...
                "bDeferRender": true,
                "bDestroy": true,
                "error": function (e, settings, techNote, message) {
                    // Suprime el mensaje de error en la consola
                    // console.log("DataTables error: " + message); // Puedes comentar esto si no quieres ver el mensaje
                },
                paging: true,
                searching: true,
                ordering: true,
                order: [[1, 'desc'],], // Ordena por la primera columna (índice 0) de forma descendente
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ entradas",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ entradas totales)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    zeroRecords: "No se encontraron coincidencias"
                }
            });
        });
    </script>
    <script>
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío normal del formulario

            const formData = new FormData(this);

            fetch('profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Éxito!", data.message, "success");
                } else {
                    Swal.fire("Error!", data.message, "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire("Error!", "Ocurrió un error inesperado.", "error");
            });
        });
    </script>

</body>

</html>
