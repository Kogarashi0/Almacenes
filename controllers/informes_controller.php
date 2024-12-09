<?php
require_once '../config.php';

try {
    // Consulta principal para obtener movimientos con detalles
    $query = "
        SELECT 
            m.tipo, m.fecha, m.cantidad, m.descripcion,
            p.nombre AS producto,
            prov.nombre AS proveedor,
            uo.nombre AS ubicacion_origen, ud.nombre AS ubicacion_destino,
            ao.nombre AS almacen_origen, ad.nombre AS almacen_destino,
            (SELECT username FROM usuarios WHERE id = 1) AS usuario -- Ajustar según la lógica de usuario actual
        FROM movimientos m
        LEFT JOIN productos p ON p.id = m.producto_id
        LEFT JOIN proveedores prov ON prov.id = m.proveedor_id
        LEFT JOIN ubicaciones uo ON uo.id = m.ubicacion_origen_id
        LEFT JOIN ubicaciones ud ON ud.id = m.ubicacion_destino_id
        LEFT JOIN almacenes ao ON ao.id = uo.almacen_id
        LEFT JOIN almacenes ad ON ad.id = ud.almacen_id
        ORDER BY m.fecha DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $informes = [];

    foreach ($movimientos as $mov) {
        $usuario = $mov['usuario']; // Usuario responsable del movimiento
        $tipo = $mov['tipo'];
        $producto = $mov['producto'];
        $cantidad = $mov['cantidad'];
        $fecha = $mov['fecha'];

        switch ($tipo) {
            case 'compra':
                $ubicacion = $mov['ubicacion_destino'];
                $almacen = $mov['almacen_destino'];
                $proveedor = $mov['proveedor'];
                $informes[] = "$usuario ha realizado una compra de $cantidad $producto para la ubicación $ubicacion del almacén $almacen.";
                if ($proveedor) {
                    $informes[] = "$usuario ha añadido al proveedor $proveedor al sistema.";
                }
                break;

            case 'venta':
                $ubicacion = $mov['ubicacion_origen'];
                $almacen = $mov['almacen_origen'];
                $informes[] = "$usuario ha realizado una venta de $cantidad $producto desde la ubicación $ubicacion del almacén $almacen.";
                break;

            case 'transferencia':
                $ubicacion_origen = $mov['ubicacion_origen'];
                $almacen_origen = $mov['almacen_origen'];
                $ubicacion_destino = $mov['ubicacion_destino'];
                $almacen_destino = $mov['almacen_destino'];
                $informes[] = "$usuario ha realizado una transferencia de $cantidad $producto del almacén $almacen_origen al almacén $almacen_destino.";
                break;

            case 'desecho':
                $ubicacion = $mov['ubicacion_origen'];
                $almacen = $mov['almacen_origen'];
                $razon = $mov['descripcion'];
                $informes[] = "$usuario ha registrado un desecho de $cantidad $producto en la ubicación $ubicacion del almacén $almacen. Razón: $razon.";
                break;

            default:
                $informes[] = "$usuario ha realizado un movimiento no identificado.";
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
