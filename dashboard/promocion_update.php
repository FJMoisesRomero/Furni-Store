<?php
require '../config/database.php';

$database = new Database();
$conn = $database->conectar();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->porcentaje) && isset($data->valido_hasta)) {
    $stmt = $conn->prepare("UPDATE promocionesxproducto SET porcentaje = :porcentaje, valido_hasta = :valido_hasta, updated_at = NOW() WHERE id = :id");
    $stmt->bindParam(':id', $data->id);
    $stmt->bindParam(':porcentaje', $data->porcentaje);
    $stmt->bindParam(':valido_hasta', $data->valido_hasta);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

