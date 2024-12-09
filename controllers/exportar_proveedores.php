<?php
require_once '../config.php';

// Configurar encabezados para descarga del archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="proveedores.csv"');

// Abrir salida de memoria
$output = fopen('php://output', 'w');

// Escribir encabezados
fputcsv($output, ['ID', 'Nombre', 'Contacto', 'Teléfono']);

// Obtener todos los proveedores
$query = "SELECT * FROM proveedores";
$stmt = $conn->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

// Cerrar salida
fclose($output);
exit();
