<?php
require '../config/database.php';

$database = new Database();
$conn = $database->conectar();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->nombre)) {
    $stmt = $conn->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");
    $stmt->bindParam(':nombre', $data->nombre);
    $stmt->bindParam(':id', $data->id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

