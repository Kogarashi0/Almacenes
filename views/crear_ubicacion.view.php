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

$almacen_id = intval($_GET['almacen_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Ubicación</title>
    <link rel="stylesheet" href="../css/formulario_ubicacion.css">
        <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Agregar Nueva Ubicación</h1>
        <form action="../controllers/guardar_ubicacion.php" method="post">
            <input type="hidden" name="almacen_id" value="<?= $almacen_id; ?>">

            <div class="form-group">
                <label for="nombre">Nombre de la Ubicación:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="capacidad_min">Capacidad Mínima:</label>
                <input type="number" id="capacidad_min" name="capacidad_min" required>
            </div>

            <div class="form-group">
                <label for="capacidad_max">Capacidad Máxima:</label>
                <input type="number" id="capacidad_max" name="capacidad_max" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn guardar">Guardar</button>
                <button type="button" class="btn cancelar" onclick="window.location.href='ver_ubicaciones.view.php?almacen_id=<?= $almacen_id; ?>'">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>