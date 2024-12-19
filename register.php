<?php
// register.php
require 'config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'] ?? '';  // Teléfono opcional
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validar los datos de entrada
    if (!is_numeric($dni) || strlen($dni) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'DNI inválido']);
        exit;
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo json_encode(['status' => 'error', 'message' => 'Email inválido']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden']);
        exit;
    }

    // Hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insertar en la base de datos
    $database = new Database();
    $pdo = $database->pdo;

    try {
        // Insertar en la tabla clientes
        $sqlClientes = "INSERT INTO clientes (ClienteDNI, ClienteNombre, ClienteEmail, ClienteTelefono, ClientePassword) 
                        VALUES (:dni, :nombre, :email, :telefono, :password)";
        $stmtClientes = $pdo->prepare($sqlClientes);
        $stmtClientes->execute([
            'dni' => $dni,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'password' => $hashedPassword
        ]);

        // Redirigir a la página de inicio de sesión después de un registro exitoso
        header('Location: login.php');
        exit;

    } catch (Exception $e) {
        // Manejo de errores; aquí puedes redirigir o manejar como desees
        echo json_encode(['status' => 'error', 'message' => 'Error en el registro: ' . $e->getMessage()]);
        exit;
    }
}



?>
<!DOCTYPE html>
<html lang="en">



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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

</head>

<body>
<?php include 'includes/header.php'; ?>

<section id="reg-section" class="bg-no-repeat bg-center bg-cover flex justify-center items-center" style="background-image: url('https://st4.depositphotos.com/39002138/40800/i/450/depositphotos_408003704-stock-photo-abstract-blurred-bed-sale-show.jpg')">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg p-8 shadow-lg">
            <p class="text-2xl font-bold text-center mb-6">Registro</p>
            <form class="space-y-4" id="registration-form">
                <input type="text" class="w-full p-3 border border-gray-300 rounded" id="dni" placeholder="DNI" required>
                <input type="text" class="w-full p-3 border border-gray-300 rounded" id="nombre" placeholder="Nombre y Apellido" required>
                <input type="email" class="w-full p-3 border border-gray-300 rounded" id="email" placeholder="Email" required>
                <input type="text" class="w-full p-3 border border-gray-300 rounded" id="telefono" placeholder="Teléfono (Opcional)">
                <input type="password" class="w-full p-3 border border-gray-300 rounded" id="password" placeholder="Contraseña" required>
                <input type="password" class="w-full p-3 border border-gray-300 rounded" id="confirm-password" placeholder="Repetir Contraseña" required>
                <div class="flex items-center">
                    <input type="checkbox" id="cbx-46" class="mr-2" required>
                    <label for="cbx-46" class="text-sm">
                        Acepto <a class="text-indigo-600 hover:underline" href="terminos-condiciones.php">Términos y Condiciones</a>
                    </label>
                </div>
                <button type="button" class="w-full bg-indigo-600 text-white py-2 rounded" id="register-btn">Registrarse</button>
            </form>
            <p class="text-sm text-center mt-4">
                Ya tienes cuenta? <a class="text-indigo-600 hover:underline" href="login.php">Iniciar Sesión</a>
            </p>
            <div id="message" class="mt-4"></div>
        </div>
    </div>
    <div class="min-h-screen w-full md:w-1/2 gradient-bg flex items-center justify-center text-white py-12 px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold">Regístrate para obtener beneficios exclusivos!</h1>
            <p class="mt-4 text-2xl font-light">Accede a promociones, descuentos y ofertas especiales.</p>
            <p class="mt-2 text-lg">Regístrate ahora y comienza a disfrutar de los beneficios de ser parte de nuestra comunidad.</p>
        </div>
    </div>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, rgba(224,97,176,0.9), rgba(141,76,175,0.9));
        }
    </style>
</section>

<?php include 'includes/footer.php'; ?>
    
<script>
    document.getElementById('register-btn').addEventListener('click', function() {
        var dni = document.getElementById('dni').value;
        var nombre = document.getElementById('nombre').value;
        var email = document.getElementById('email').value;
        var telefono = document.getElementById('telefono').value;
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm-password').value;

        // Preparar los datos para enviar
        var formData = new FormData();
        formData.append('dni', dni);
        formData.append('nombre', nombre);
        formData.append('email', email);
        formData.append('telefono', telefono);
        formData.append('password', password);
        formData.append('confirmPassword', confirmPassword);

        // Enviar datos al servidor
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verificar si es una redirección
            if (response.redirected) {
                window.location.href = response.url; // Redirigir a login.php
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data) {
                var messageDiv = document.getElementById('message');
                messageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('message').innerHTML = '<p style="color: red;">Error al registrar</p>';
        });
    });

</script>

</body>

</html>


