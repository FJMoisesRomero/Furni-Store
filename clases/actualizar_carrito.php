<?php

require '../config/config.php';
require '../config/database.php';

// session_start(); // Asegúrate de iniciar la sesión

$datos = [];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0; // Cambio del nombre de la variable

    if ($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if ($respuesta > 0) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
        $datos['sub'] = MONEDA . number_format($respuesta, 2, '.', ',');
    } else if ($action == 'eliminar') {
        $datos['ok'] = eliminar($id);
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);


function agregar($id, $cantidad) {
    $res = 0;
    if ($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;

            $db = new Database();
            $con = $db->conectar();
            $sql = $con->prepare("SELECT precio, descuento_online FROM productos WHERE id = ? AND estado_activo = 1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $precio = $row['precio'];  // Corregir typo
                $descuento = $row['descuento_online'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
                $res = $cantidad * $precio_desc;
            }

            return $res;
        }
    }

    return $res;
}

function eliminar($id) {
    if ($id > 0) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            unset($_SESSION['carrito']['productos'][$id]);
            return true;
        }
    }

    return false;
}

?>
