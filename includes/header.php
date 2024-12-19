<?php $num_cart = isset($_SESSION['carrito']['productos']) ? count($_SESSION['carrito']['productos']) : 0; $is_logged_in = isset($_SESSION['user_id']);?>
<section id="Header" class="bg-gray-800 shadow-md sticky top-0 z-50">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lemonada&display=swap');
    </style>
    <div class="container mx-auto flex justify-center items-center px-4 py-3">
        <div class="hidden md:flex space-x-6 items-center">
            <ul id="navbar" class="flex space-x-4 text-white">
                <?php if ($is_logged_in): ?>
                    <li id="lg-user"><a class="hover:text-white transition duration-300" onclick="mostrarMensajeSalida()" href="#">Salir</a></li>
                <?php else: ?>
                    <li id="lg-user"><a class="hover:text-white transition duration-300" href="login.php"><i class="fa fa-user" aria-hidden="true"></i> Iniciar sesión</a></li>
                <?php endif; ?>
                <?php if ($is_logged_in): ?>
                    <a href="profile.php" class="nav-item">
                        <i class="fa-solid fa-user mr-2"></i>Mi perfil
                    </a>
                <?php endif; ?>
                <li><a class="hover:text-white transition duration-300" href="index.php">Inicio</a></li>
                <li><a class="hover:text-white transition duration-300" href="shop.php">Tienda</a></li>
                <!-- <li><a class="hover:text-white transition duration-300" href="blog.php">Blog</a></li> -->
                <li><a class="hover:text-white transition duration-300" href="about.php">Sobre Nosotros</a></li>
                <li><a class="hover:text-white transition duration-300" href="contact.php">Contacto</a></li>
                <li id="lg-bag" class="relative">
                    <a href="cart.php" class="flex items-center">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span id="num_cart" class="badge bg-secondary ml-1"><?php echo $num_cart; ?></span>
                    </a>
                </li>

            </ul>
        </div>
        <div id="mobile" class="md:hidden flex items-center">
            <button id="bar" class="text-white focus:outline-none">
                <i class="fas fa-outdent"></i>
            </button>
        </div>
        <a href="#" class="ml-8 flex items-center">
            <i class="fas fa-chair text-3xl mr-2 inline-block font-lemonada text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600"></i>
            <span class="text-3xl font-bold font-lemonada text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">Furni Store</span>
        </a>
    </div>
    <div class="md:hidden">
        <ul id="navbar" class="hidden flex flex-col items-center space-y-4 text-white">
            <?php if ($is_logged_in): ?>
                <li id="lg-user"><a class="hover:text-white transition duration-300" onclick="mostrarMensajeSalida()" href="#">Salir</a></li>
            <?php else: ?>
                <li id="lg-user"><a class="hover:text-white transition duration-300" href="login.php"><i class="fa fa-user" aria-hidden="true"></i> Iniciar sesión</a></li>
            <?php endif; ?>
            <?php if ($is_logged_in): ?>
                <a href="profile.php" class="nav-item">
                    <i class="fa-solid fa-user mr-2"></i>Mi perfil
                </a>
            <?php endif; ?>
            <li><a class="hover:text-white transition duration-300" href="index.php">Inicio</a></li>
            <li><a class="hover:text-white transition duration-300" href="shop.php">Tienda</a></li>
            <li><a class="hover:text-white transition duration-300" href="about.php">Sobre Nosotros</a></li>
            <li><a class="hover:text-white transition duration-300" href="contact.php">Contacto</a></li>
            <li id="lg-bag" class="relative">
                <a href="cart.php" class="flex items-center">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <span id="num_cart" class="badge bg-secondary ml-1"><?php echo $num_cart; ?></span>
                </a>
            </li>

        </ul>
    </div>
    <div class="w-full h-1 bg-gradient-to-r from-blue-400 to-purple-600"></div>
</section>
<script>
    const bar = document.querySelector('#bar');
    const navbar = document.querySelector('#navbar');

    bar.addEventListener('click', () => {
        navbar.classList.toggle('hidden');
    });
</script>

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