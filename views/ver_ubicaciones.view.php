<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

// Verificar si se recibió el ID del almacén
if (!isset($_GET['almacen_id'])) {
    header('Location: almacenes.view.php');
    exit();
}

require_once '../config.php';

// Obtener el ID del almacén
$almacen_id = intval($_GET['almacen_id']);

// Obtener datos del almacén
$query_almacen = "SELECT nombre FROM almacenes WHERE id = :almacen_id";
$stmt_almacen = $conn->prepare($query_almacen);
$stmt_almacen->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
$stmt_almacen->execute();
$almacen = $stmt_almacen->fetch(PDO::FETCH_ASSOC);

if (!$almacen) {
    header('Location: almacenes.view.php');
    exit();
}

// Obtener ubicaciones del almacén, incluyendo el stock actual
$query_ubicaciones = "SELECT id, nombre, capacidad_min, capacidad_max, stock_actual 
                      FROM ubicaciones 
                      WHERE almacen_id = :almacen_id";
$stmt_ubicaciones = $conn->prepare($query_ubicaciones);
$stmt_ubicaciones->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
$stmt_ubicaciones->execute();
$ubicaciones = $stmt_ubicaciones->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubicaciones de <?= htmlspecialchars($almacen['nombre']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <div class="container">
        <div class="dashboard-container">
            <h1>Ubicaciones en <?= htmlspecialchars($almacen['nombre']); ?></h1>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Capacidad Mínima</th>
                        <th>Capacidad Máxima</th>
                        <th>Stock Actual</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ubicaciones)): 
                        foreach ($ubicaciones as $ubicacion): ?>
                            <tr>
                                <td><?= htmlspecialchars($ubicacion['nombre']); ?></td>
                                <td><?= htmlspecialchars($ubicacion['capacidad_min']); ?></td>
                                <td><?= htmlspecialchars($ubicacion['capacidad_max']); ?></td>
                                <td><?= htmlspecialchars($ubicacion['stock_actual']); ?></td>
                                <td>
                                    <!-- Botón para gestionar productos -->
                                    <form action="gestionar_productos.view.php" method="get" style="display:inline;">
                                        <input type="hidden" name="ubicacion_id" value="<?= $ubicacion['id']; ?>">
                                        <button type="submit" class="button">Gestionar productos</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="5">No hay ubicaciones registradas para este almacén.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="options">
                <!-- Botón para regresar -->
                <form action="almacenes.view.php" method="get" style="display:inline;">
                    <button type="submit" class="button">Volver</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
