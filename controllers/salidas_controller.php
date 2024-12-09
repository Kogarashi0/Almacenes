<?php
require_once '../config.php';

// Valores predeterminados para limit y page
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Cantidad de salidas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit; // Calcular desplazamiento

// Obtener el número total de salidas
$countQuery = "
    SELECT COUNT(*) AS total
    FROM movimientos
    WHERE tipo IN ('venta', 'desecho', 'transferencia') AND ubicacion_origen_id IS NOT NULL";
$countStmt = $conn->prepare($countQuery);
$countStmt->execute();
$totalEntries = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalEntries / $limit); // Total de páginas

// Obtener movimientos de tipo salida con paginación
$query = "
    SELECT m.*, p.nombre AS producto, u.nombre AS ubicacion_origen
    FROM movimientos m
    LEFT JOIN productos p ON m.producto_id = p.id
    LEFT JOIN ubicaciones u ON m.ubicacion_origen_id = u.id
    WHERE m.tipo IN ('venta', 'desecho', 'transferencia') AND m.ubicacion_origen_id IS NOT NULL
    ORDER BY m.fecha DESC
    LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($query);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
