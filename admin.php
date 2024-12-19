<?php
session_start();
$error_message = "";

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['user'])) {
    header("Location: ./dashboard/index.php");
    exit();
}

if ($_POST) {
    include('config/database.php');
    $db = new Database();
    $conn = $db->conectar();
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificación de la contraseña como strings
    if ($user && $password === $user['password']) {
        $_SESSION['user'] = $user;
        $_SESSION['mostrar_bienvenida'] = true;
        header("Location: ./dashboard/index.php");
        exit();
    } else {
        $error_message = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Gestión</title>
    <script src="https://kit.fontawesome.com/54b6794846.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="flex m-0 h-screen">
    <div class="flex flex-col-reverse md:flex-row w-full h-screen">
        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 h-full flex flex-col justify-center items-center p-10 bg-white md:order-first">
            <h2 class="text-3xl text-gray-800 text-center">Acceder</h2>
            <p class="text-gray-600 text-center">Inicia sesión para acceder a tu cuenta.</p>
            <form action="./admin.php" method="POST" class="flex flex-col items-center w-full max-w-sm">
                <div class="w-full mb-4">
                    <label for="usuario" class="block mb-1 font-bold text-gray-700">Usuario</label>
                    <input type="text" name="usuario" id="usuario" required class="w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="w-full mb-4 relative">
                    <label for="password" class="block mb-1 font-bold text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password" required class="w-full p-2 border border-gray-300 rounded-md">
                    <i class="fas fa-eye-slash absolute right-3 top-10 cursor-pointer" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                </div>
                <button type="submit" class="w-full px-4 py-2 rounded-md bg-gradient-to-br from-blue-500 to-blue-300 text-white hover:from-blue-600 hover:to-blue-400 transition">Loguearse</button>
                <p class="mt-4 text-gray-600 text-center">¿Olvidaste tu Contraseña? <a href="https://github.com/FJMoisesRomero" target="_blank" class="text-blue-500 hover:underline">Ponte en contacto con un Administrador</a></p>
            </form>
        </div>

        <!-- Left Side: Image -->
        <div class="w-full md:w-1/2 h-full bg-cover bg-center" style="background-image: url('https://www.mueblesmarfil.es/media/images/gallery/9/big/11.jpg');">
            <div class="bg-black bg-opacity-40 h-full flex justify-center items-center">
                <h1 class="text-white text-4xl font-bold">Sistema de Gestión</h1>
            </div>
        </div>
    </div>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('togglePassword');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        }
    }
</script>
<script>
    <?php if ($error_message): ?>
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            background: '#f44336',
            color: '#fff'
        }).fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $error_message; ?>'
        });
    <?php endif; ?>
</script>
</body>

</html>

