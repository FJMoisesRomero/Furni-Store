<?php
require '../config/database.php';

$database = new Database();
$conn = $database->conectar();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->porcentaje) && isset($data->valido_hasta) && isset($data->producto_id)) {
    $stmt = $conn->prepare("INSERT INTO promocionesxproducto (porcentaje, valido_hasta, producto_id, estado_activo, created_at, updated_at) VALUES (:porcentaje, :valido_hasta, :producto_id, 1, NOW(), NOW())");
    $stmt->bindParam(':porcentaje', $data->porcentaje);
    $stmt->bindParam(':valido_hasta', $data->valido_hasta);
    $stmt->bindParam(':producto_id', $data->producto_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
