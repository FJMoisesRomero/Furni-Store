<?php
// Incluir el archivo de configuración de la base de datos
require '../config/database.php';

// Crear una instancia de la clase Database
$database = new Database();
$conn = $database->conectar();

// Obtener datos JSON
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$estado = $data['estado'];
$tabla = $data['tabla']; // Obtener el nombre de la tabla

// Verificar si la tabla es válida
$tablasValidas = ['productos', 'marcas', 'categorias', 'promocionesxproducto'];
if (!in_array($tabla, $tablasValidas)) {
    echo json_encode(['success' => false, 'message' => 'Tabla inválida.']);
    exit;
}

// Actualizar estado_activo en la base de datos
$sql = "UPDATE $tabla SET estado_activo = :estado WHERE id = :id"; // Usar la tabla proporcionada
$stmt = $conn->prepare($sql);
$stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

$success = $stmt->execute();

// Retornar respuesta en formato JSON
echo json_encode(['success' => $success]);
?>
