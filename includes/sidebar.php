<style>
    .main-header, .main-sidebar {
        background-color: #1A202C;
    }
</style>

<?php 
include('../config/database.php');

$db = new Database();
$conn = $db->conectar();

// Obtener la imagen del usuario
$id_usuario = $user['id']; 
$stmt = $conn->prepare("SELECT imagen_usuario FROM usuarios WHERE id = :id");
$stmt->bindParam(":id", $id_usuario);
$stmt->execute();
$datos_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$imagen_usuario = $datos_usuario['imagen_usuario']; 
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark sticky-top">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Inicio</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contacto</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="cambiarModoOscuro()">
                <i class="fas fa-sun" id="modo-oscuro-icono"></i>
            </a>
        </li>
    </ul>
</nav>

<script>
    function cambiarModoOscuro(){
        var contentWrapper = document.querySelector('.content-wrapper');
        var content = document.querySelector('.content');
        var container = document.querySelector('.container-fluid');
        var cards = document.querySelectorAll('.card');
        var icono = document.querySelector('#modo-oscuro-icono');

        if (contentWrapper.classList.contains('oscuro')) {
            contentWrapper.classList.remove('oscuro');
            content.classList.remove('oscuro');
            container.classList.remove('oscuro');
            cards.forEach(card => {
                card.classList.remove('oscuro');
            });
            icono.classList.remove('fa-moon');
            icono.classList.add('fa-sun');
            $('.dataTables_wrapper').css('background-color', '#f4f6f9');
            localStorage.setItem('modoOscuro', '0');
        } else {
            contentWrapper.classList.add('oscuro');
            content.classList.add('oscuro');
            container.classList.add('oscuro');
            cards.forEach(card => {
                card.classList.add('oscuro');
            });
            icono.classList.remove('fa-sun');
            icono.classList.add('fa-moon');
            $('.dataTables_wrapper').css('background-color', '#1A202C');
            localStorage.setItem('modoOscuro', '1');
        }
    }

    function verificarModoOscuro(){
        if (localStorage.getItem('modoOscuro') == '1') {
            cambiarModoOscuro();
        }
    }

    window.addEventListener("load", verificarModoOscuro);
</script>

<style>
    .oscuro {
        background-color: #1A202C; /* Dark gray, almost black */
        color: white;
    }
</style>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="height:100vh;">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link" style="background-color: #1A202C;">
    <img src="https://i.ibb.co/bbcDcNJ/logo.png" alt="SMI Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light" style="font-size: 15px">Sistema de Gestión</span>
    </a>
    <!-- Sidebar user panel-->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="justify-content:center">
        <div class="image">
        <?php if (!empty($imagen_usuario)) { ?>
            <img style="width:60px; height:60px;border-radius:50%;" src="data:image/jpeg;base64,<?= base64_encode($imagen_usuario) ?>" alt="Imagen de Usuario"/>
        <?php } else { ?>
            <img src="images/userImage1.png" style="width:60px; height:60px;border-radius:50%;" alt="Imagen de Usuario por defecto"/>
        <?php } ?>
        </div>
        <div class="info">
        <a class="d-block" style="color:white"><?= htmlspecialchars($user['nombre']) ?><br> <?= htmlspecialchars($user['apellido']) ?></a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline mb-4">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item mb-2 rounded-lg" style="margin-top:-10px;background-color: #C63637;">
                    <a class="nav-link" onclick="mostrarMensajeSalida()" href="#" style="color: white;">
                        <i class="fa fa-power-off" style="margin: 5px;"></i>
                        <p style="margin: 0; flex-grow: 1; text-align: center;">Salir</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link" data-page="index.php">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Estadísticas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="products.php" class="nav-link" data-page="products.php">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Productos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="sales.php" class="nav-link" data-page="sales.php">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Ventas</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<script>
  function mostrarMensajeSalida() {
        let timerInterval;
        Swal.fire({
            title: "Cerrando Sesión",
            html: "",
            timer: 1000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                timerInterval = setInterval(() => {
                    const timer = Swal.getHtmlContainer().querySelector("b");
                    if (timer) {
                        timer.textContent = Swal.getTimerLeft();
                    }
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location.href = "logout.php"; // Redirige al usuario a la página de cierre de sesión
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            if (link.getAttribute('data-page') === currentPage) {
                link.classList.add('active');
            }
        });
    });
</script>

