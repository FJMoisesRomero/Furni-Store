<?php
// Incluir el archivo de configuración de la base de datos
require '../config/database.php';

// Crear una instancia de la clase Database
$database = new Database();
$conn = $database->conectar();

// Obtener los datos JSON enviados desde el cliente
$data = json_decode(file_get_contents("php://input"));

// Verificar que los datos necesarios están presentes
if (isset($data->id) && isset($data->nombre) && isset($data->descripcion) &&
    isset($data->stock) && isset($data->precio) && isset($data->imagen_url) &&
    isset($data->marca_id) && isset($data->categoria_id)) {

    // Preparar la consulta de actualización
    $sql = "UPDATE productos SET 
                nombre = :nombre,
                descripcion = :descripcion,
                stock = :stock,
                precio = :precio,
                imagen_url = :imagen_url,
                marca_id = :marca_id,
                categoria_id = :categoria_id
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    // Asignar valores a los parámetros
    $stmt->bindParam(':nombre', $data->nombre);
    $stmt->bindParam(':descripcion', $data->descripcion);
    $stmt->bindParam(':stock', $data->stock);
    $stmt->bindParam(':precio', $data->precio);
    $stmt->bindParam(':imagen_url', $data->imagen_url);
    $stmt->bindParam(':marca_id', $data->marca_id);
    $stmt->bindParam(':categoria_id', $data->categoria_id);
    $stmt->bindParam(':id', $data->id);

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el producto.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios.']);
}
?>
