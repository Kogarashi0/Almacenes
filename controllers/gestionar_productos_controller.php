<?php
require_once '../config.php';

/**
 * Valida que los parámetros requeridos estén presentes en los datos enviados.
 */
function validarParametros($params, $requeridos) {
    foreach ($requeridos as $campo) {
        if (empty($params[$campo])) {
            throw new Exception("El campo '$campo' es obligatorio.");
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $cantidad = (int)($_POST['cantidad'] ?? 0);
    $descripcion = $_POST['descripcion'] ?? null;

    try {
        // Validar campos según tipo de movimiento
        $requeridos = ['tipo', 'cantidad'];
        switch ($tipo) {
            case 'compra':
                $requeridos[] = 'ubicacion_destino_id';
                break;
            case 'venta':
                $requeridos = array_merge($requeridos, ['producto_id', 'ubicacion_origen_id']);
                break;
            case 'transferencia':
                $requeridos = array_merge($requeridos, ['producto_id', 'ubicacion_origen_id', 'ubicacion_destino_id']);
                break;
            case 'desecho':
                $requeridos = array_merge($requeridos, ['producto_id', 'ubicacion_origen_id', 'razon_desecho']);
                break;
            default:
                throw new Exception("Tipo de movimiento no válido.");
        }

        validarParametros($_POST, $requeridos);

        // Iniciar transacción
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

            case 'venta':
                $producto_id = $_POST['producto_id'];
                $ubicacion_origen_id = (int)$_POST['ubicacion_origen_id'];

                // Validar stock disponible
                $stmt = $conn->prepare("SELECT stock_actual FROM ubicaciones WHERE id = ?");
                $stmt->execute([$ubicacion_origen_id]);
                $stock = $stmt->fetchColumn();

                if ($stock === false || $stock < $cantidad) {
                    throw new Exception("Stock insuficiente en la ubicación de origen.");
                }

                // Registrar movimiento de venta
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, cantidad, ubicacion_origen_id, descripcion) 
                    VALUES ('venta', ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $cantidad, $ubicacion_origen_id, $descripcion]);

                // Actualizar stock
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual - ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_origen_id]);
                break;

            case 'transferencia':
                $producto_id = $_POST['producto_id'];
                $ubicacion_origen_id = (int)$_POST['ubicacion_origen_id'];
                $ubicacion_destino_id = (int)$_POST['ubicacion_destino_id'];

                // Validar stock disponible en origen
                $stmt = $conn->prepare("SELECT stock_actual FROM ubicaciones WHERE id = ?");
                $stmt->execute([$ubicacion_origen_id]);
                $stock = $stmt->fetchColumn();

                if ($stock === false || $stock < $cantidad) {
                    throw new Exception("Stock insuficiente en la ubicación de origen.");
                }

                // Registrar movimiento de transferencia
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, cantidad, ubicacion_origen_id, ubicacion_destino_id, descripcion) 
                    VALUES ('transferencia', ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $cantidad, $ubicacion_origen_id, $ubicacion_destino_id, $descripcion]);

                // Actualizar stock en origen y destino
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual - ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_origen_id]);

                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual + ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_destino_id]);
                break;

            case 'desecho':
                $producto_id = $_POST['producto_id'];
                $ubicacion_origen_id = (int)$_POST['ubicacion_origen_id'];
                $razon_desecho = $_POST['razon_desecho'] ?? 'Sin razón especificada';

                // Validar stock disponible
                $stmt = $conn->prepare("SELECT stock_actual FROM ubicaciones WHERE id = ?");
                $stmt->execute([$ubicacion_origen_id]);
                $stock = $stmt->fetchColumn();

                if ($stock === false || $stock < $cantidad) {
                    throw new Exception("Stock insuficiente en la ubicación de origen.");
                }

                // Registrar movimiento de desecho
                $stmt = $conn->prepare("
                    INSERT INTO movimientos (tipo, producto_id, cantidad, ubicacion_origen_id, descripcion) 
                    VALUES ('desecho', ?, ?, ?, ?)
                ");
                $stmt->execute([$producto_id, $cantidad, $ubicacion_origen_id, $razon_desecho]);

                // Actualizar stock
                $stmt = $conn->prepare("UPDATE ubicaciones SET stock_actual = stock_actual - ? WHERE id = ?");
                $stmt->execute([$cantidad, $ubicacion_origen_id]);
                break;

            default:
                throw new Exception("Tipo de movimiento no válido.");
        }

        $conn->commit();
        header("Location: ../views/gestionar_productos.view.php?success=1");
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error: " . $e->getMessage());
        header("Location: ../views/gestionar_productos.view.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
