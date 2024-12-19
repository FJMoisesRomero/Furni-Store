<?php

session_start(); // Asegúrate de iniciar la sesión

require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Verifica si hay un usuario autenticado
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id']; // Asumiendo que el ID del usuario se guarda en la sesión

    // Consulta para obtener los datos del cliente
    $sql = "SELECT nombre, apellido, email, numero_telefono, direccion FROM clientes WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$user_id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        // Devolver los datos del cliente como JSON
        echo json_encode($cliente);
    } else {
        echo json_encode(['error' => 'Cliente no encontrado.']);
    }
} else {
    echo json_encode(['error' => 'Usuario no autenticado.']);
}

?>
