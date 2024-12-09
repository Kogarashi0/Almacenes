<?php
require_once '../config.php';

// Parámetros de paginación
$registros_por_pagina = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$pagina_actual = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Total de registros
$total_query = "SELECT COUNT(*) as total FROM proveedores";
$stmt = $conn->prepare($total_query);
$stmt->execute();
$total_result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_registros = $total_result['total'];

// Obtener proveedores con límite y offset
$query = "SELECT * FROM proveedores LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($query);
$stmt->bindValue(':limit', $registros_por_pagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);
