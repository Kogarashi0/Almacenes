<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $cantidad = (int)$_POST['cantidad'];
    $descripcion = $_POST['descripcion'] ?? null;

    try {
        $conn->beginTransaction();

        switch ($tipo) {
            case 'compra':
                $nuevo_producto = $_POST['nuevo_producto'] ?? null;
                $nuevo_proveedor = $_POST['nuevo_proveedor'] ?? null;
                $producto_id = $_POST['producto_id'] ?? null;
                $proveedor_id = $_POST['proveedor_id'] ?? null;
                $ubicacion_destino_id = (int)$_POST['ubicacion_destino_id'];

                // Verificar o insertar producto
                if ($nuevo_producto) {
                    $stmt = $conn->prepare("SELECT id FROM productos WHERE nombre = ?");
                    $stmt->execute([$nuevo_producto]);
                    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($producto) {
                        $producto_id = $producto['id'];
                    } else {
                        $stmt = $conn->prepare("INSERT INTO productos (nombre) VALUES (?)");
                        $stmt->execute([$nuevo_producto]);
                        $producto_id = $conn->lastInsertId();
                    }
                }

                // Verificar o insertar proveedor
                if ($nuevo_proveedor) {
                    $stmt = $conn->prepare("SELECT id FROM proveedores WHERE nombre = ?");
                    $stmt->execute([$nuevo_proveedor]);
                    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($proveedor) {
                        $proveedor_id = $proveedor['id'];
                    } else {
                        $stmt = $conn->prepare("INSERT INTO proveedores (nombre) VALUES (?)");
                        $stmt->execute([$nuevo_proveedor]);
                        $proveedor_id = $conn->lastInsertId();
                    }
                }

                // Registrar movimiento de compra
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, proveedor_id, cantidad, ubicacion_destino_id, descripcion) 
                    VALUES ('compra', ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $proveedor_id, $cantidad, $ubicacion_destino_id, $descripcion]);

                // Actualizar stock
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual + ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_destino_id]);
                break;

            // Lógica para otros tipos (venta, transferencia, desecho)
        }

        $conn->commit();
        header("Location: ../views/gestionar_productos.view.php?success=1");
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error: " . $e->getMessage());
        header("Location: ../views/gestionar_productos.view.php?error=1");
        exit;
    }
}
