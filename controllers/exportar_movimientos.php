<?php
require_once '../config.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'entradas'; // Entradas o salidas

if (!in_array($type, ['entradas', 'salidas'])) {
    die('Tipo no válido');
}

// Configurar encabezado del archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $type . '.csv"');

// Abrir salida de memoria
$output = fopen('php://output', 'w');

// Escribir encabezado del CSV
fputcsv($output, ['Fecha', 'Producto', 'Cantidad', 'Ubicación', 'Tipo', 'Descripción']);

// Construir consulta según el tipo
if ($type === 'entradas') {
    $query = "
        SELECT m.fecha, p.nombre AS producto, m.cantidad, u.nombre AS ubicacion_destino, m.tipo, m.descripcion
        FROM movimientos m
        LEFT JOIN productos p ON m.producto_id = p.id
        LEFT JOIN ubicaciones u ON m.ubicacion_destino_id = u.id
        WHERE m.tipo IN ('compra', 'transferencia') AND m.ubicacion_destino_id IS NOT NULL
        ORDER BY m.fecha DESC";
} else {
    $query = "
        SELECT m.fecha, p.nombre AS producto, m.cantidad, u.nombre AS ubicacion_origen, m.tipo, m.descripcion
        FROM movimientos m
        LEFT JOIN productos p ON m.producto_id = p.id
        LEFT JOIN ubicaciones u ON m.ubicacion_origen_id = u.id
        WHERE m.tipo IN ('venta', 'desecho', 'transferencia') AND m.ubicacion_origen_id IS NOT NULL
        ORDER BY m.fecha DESC";
}

// Ejecutar consulta y escribir filas en el CSV
$stmt = $conn->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

// Cerrar salida
fclose($output);
exit();
