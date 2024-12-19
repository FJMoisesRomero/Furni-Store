<?php
require '../config/database.php';

$database = new Database();
$conn = $database->conectar();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->nombre)) {
    $stmt = $conn->prepare("INSERT INTO categorias (nombre, estado_activo) VALUES (:nombre, :estado_activo)");
    
    $estadoActivo = 1; // Ajusta este valor según tu lógica

    $stmt->bindParam(':nombre', $data->nombre);
    $stmt->bindParam(':estado_activo', $estadoActivo);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
