<?php
session_start();
$error_message = "";

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


// Incluir configuración de la base de datos
require_once 'config/database.php';

// Crear instancia de la base de datos
$database = new Database();
$conn = $database->conectar();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        $error_message = "Por favor, complete todos los campos.";
    } else {
        // Preparar y ejecutar la consulta
        try {
            $stmt = $conn->prepare("SELECT id, ClienteEmail, ClientePassword FROM clientes WHERE ClienteEmail = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            // Verificar la contraseña
            if ($user && password_verify($password, $user['ClientePassword'])) {
                // Guardar el ID del usuario en la sesión
                $_SESSION['user_id'] = $user['id'];

                // Redirigir al usuario a la página principal
                $_SESSION['mostrar_bienvenida'] = true;
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Email o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error_message = "Error en la consulta: " . $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
</head>

<body>
 <?php require 'includes/header.php'; ?>
 <script>
        <?php if (!empty($error_message)): ?>
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
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
    <section id="login-section" class="bg-no-repeat bg-center bg-cover flex justify-center items-center" style="background-image: url('https://st4.depositphotos.com/39002138/40800/i/450/depositphotos_408003704-stock-photo-abstract-blurred-bed-sale-show.jpg')">
        <!-- Left Section with Text -->
        <div class="min-h-screen w-full md:w-1/2 gradient-bg flex items-center justify-center text-white py-12 px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold">Furni Store</h1>
                <p class="mt-4 text-2xl font-light">Encuentra el mejor estilo para tu hogar!</p>
                <p class="mt-2 text-lg">Descubre la mejor calidad y variedad de muebles en Furni Store.</p>
            </div>
        </div>

        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-lg p-8 shadow-lg">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900">¡Hola, bienvenido a Furni Store! 👋</h2>
                    <p class="mt-2 text-sm text-gray-600">Inicia sesión para acceder a tu cuenta</p>
                </div>
                <form action="login.php" method="POST" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900"> Recuérdame </label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> ¿Olvidaste tu contraseña? </a>
                        </div>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white gradient-btn hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Iniciar sesión
                        </button>
                    </div>
                    <div class="flex items-center justify-center mt-6">
                        <div class="border-b w-1/3"></div>
                        <span class="px-4 text-sm text-gray-600">o</span>
                        <div class="border-b w-1/3"></div>
                    </div>

                </form>
                <p class="mt-4 text-center text-sm text-gray-600">
                    ¿No tienes una cuenta? <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500"> Regístrate </a>
                </p>
            </div>
        </div>
    </section>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, rgba(58,123,213,0.9), rgba(58,213,151,0.9));
        }
        .gradient-btn {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
        }
    </style>
    <?php require 'includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessageElement = document.getElementById('error-message');
            if (errorMessageElement) {
                var errorMessage = errorMessageElement.value;
                if (errorMessage) {
                    alert(errorMessage);
                }
            }
        });

        document.getElementById('login-form').addEventListener('submit', function(event) {
            var dni = document.getElementById('dni').value; // Cambiado de 'email' a 'dni'
            var password = document.getElementById('password').value;

            if (!dni || !password) {
                alert('Por favor, complete todos los campos.');
                event.preventDefault(); // Previene el envío del formulario
            }
        });
    </script>

</body>

</html>
