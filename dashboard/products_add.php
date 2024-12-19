<?php
require '../config/database.php';

$database = new Database();
$conn = $database->conectar();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->nombre) && isset($data->descripcion) && isset($data->stock) && isset($data->precio) && isset($data->imagen_url) && isset($data->marca_id) && isset($data->categoria_id)) {
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, stock, precio, imagen_url, marca_id, categoria_id) VALUES (:nombre, :descripcion, :stock, :precio, :imagen_url, :marca_id, :categoria_id)");
    $stmt->bindParam(':nombre', $data->nombre);
    $stmt->bindParam(':descripcion', $data->descripcion);
    $stmt->bindParam(':stock', $data->stock);
    $stmt->bindParam(':precio', $data->precio);
    $stmt->bindParam(':imagen_url', $data->imagen_url);
    $stmt->bindParam(':marca_id', $data->marca_id);
    $stmt->bindParam(':categoria_id', $data->categoria_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
