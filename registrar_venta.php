<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $_POST['customerName'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $productos = json_decode($_POST['productos'], true);
    $clienteId = $_POST['clienteId'];
    // Insertar la venta
    $database = new Database();
    $pdo = $database->pdo;
    try {
        $pdo->beginTransaction();

        // Insertar en la tabla ventas
        $stmt = $pdo->prepare("INSERT INTO ventas (codigo, cliente_id, fecha, metodo_de_pago_id) VALUES (?, ?, NOW(), ?)");
        $codigo = 'ORD-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $metodoPagoId = 1; // ID del método de pago (reemplaza según tu lógica)
        
        $stmt->execute([$codigo, $clienteId, $metodoPagoId]);
        $ventaId = $pdo->lastInsertId();

        // Insertar detalles de venta
        foreach ($productos as $producto) {
            $productoId = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];
            $stmtDetalle = $pdo->prepare("INSERT INTO ventas_detalles (venta_id, producto_id, producto_cantidad, producto_precio) VALUES (?, ?, ?, ?)");
            $stmtDetalle->execute([$ventaId, $productoId, $cantidad, $precio]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Venta registrada exitosamente']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar la venta: ' . $e->getMessage()]);
    }
}
