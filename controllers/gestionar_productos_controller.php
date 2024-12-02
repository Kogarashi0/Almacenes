<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recopilar datos comunes
    $tipo = $_POST['tipo'];
    $cantidad = (int)$_POST['cantidad'];
    $descripcion = $_POST['descripcion'] ?? null;
    $producto_id = $_POST['producto_id'] ?? null;

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        switch ($tipo) {
            case 'compra':
                $nuevo_producto = $_POST['nuevo_producto'] ?? null;
                $nuevo_proveedor = $_POST['nuevo_proveedor'] ?? null;
                $ubicacion_destino_id = (int)$_POST['ubicacion_destino_id'];

                // Verificar si el producto ya existe
                if ($nuevo_producto) {
                    $stmt = $conn->prepare("SELECT id FROM productos WHERE nombre = ?");
                    $stmt->execute([$nuevo_producto]);
                    $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($existingProduct) {
                        // Si el producto existe, usar su ID
                        $producto_id = $existingProduct['id'];
                    } else {
                        // Si no existe, insertar el nuevo producto
                        $stmt = $conn->prepare("INSERT INTO productos (nombre) VALUES (?)");
                        $stmt->execute([$nuevo_producto]);
                        $producto_id = $conn->lastInsertId();
                    }
                }

                // Insertar nuevo proveedor si se especifica
                if ($nuevo_proveedor) {
                    $stmt = $conn->prepare("INSERT INTO proveedores (nombre) VALUES (?)");
                    $stmt->execute([$nuevo_proveedor]);
                    $proveedor_id = $conn->lastInsertId();
                } else {
                    $proveedor_id = $_POST['proveedor_id'] ?? null;
                }

                // Registrar el movimiento de compra
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, proveedor_id, cantidad, ubicacion_destino_id, descripcion) 
                    VALUES ('compra', ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $proveedor_id, $cantidad, $ubicacion_destino_id, $descripcion]);

                // Actualizar el stock actual en la ubicación destino
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual + ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_destino_id]);

                break;

            case 'venta':
                $ubicacion_origen_id = (int)$_POST['ubicacion_origen_id'];

                // Registrar el movimiento de venta
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, cantidad, ubicacion_origen_id, descripcion) 
                    VALUES ('venta', ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $cantidad, $ubicacion_origen_id, $descripcion]);

                // Reducir el stock actual en la ubicación origen
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual - ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_origen_id]);

                break;

            case 'transferencia':
                $ubicacion_origen_id = (int)$_POST['ubicacion_origen_id'];
                $ubicacion_destino_id = (int)$_POST['ubicacion_destino_id'];

                // Registrar el movimiento de transferencia
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, cantidad, ubicacion_origen_id, ubicacion_destino_id, descripcion) 
                    VALUES ('transferencia', ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $cantidad, $ubicacion_origen_id, $ubicacion_destino_id, $descripcion]);

                // Reducir el stock en la ubicación origen
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual - ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_origen_id]);

                // Incrementar el stock en la ubicación destino
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual + ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_destino_id]);

                break;

            case 'desecho':
                $ubicacion_origen_id = (int)$_POST['ubicacion_origen_id'];

                // Registrar el movimiento de desecho
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, cantidad, ubicacion_origen_id, descripcion) 
                    VALUES ('desecho', ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $cantidad, $ubicacion_origen_id, $descripcion]);

                // Reducir el stock en la ubicación origen
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual - ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_origen_id]);

                break;

            default:
                throw new Exception("Tipo de movimiento no válido.");
        }

        // Confirmar la transacción
        $conn->commit();
        header("Location: ../views/gestionar_productos.view.php?success=1");
        exit;

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        error_log("Error en el movimiento: " . $e->getMessage());
        header("Location: ../views/gestionar_productos.view.php?error=1");
        exit;
    }
}
