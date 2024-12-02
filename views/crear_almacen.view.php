<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/login.view.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Almacén</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/almacen.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <div class="main">
        <div class="almacen-container">
            <h1>Crear Nuevo Almacén</h1>
            <form action="../controllers/almacenes_controller.php" method="POST" class="almacen-form">
                <label for="nombre">Nombre del almacén:</label>
                <input type="text" name="nombre" id="nombre" required>

                <label for="ubicaciones">Ubicaciones y Capacidades:</label>
                <div id="ubicaciones-container" class="ubicaciones-container">
                    <div class="ubicacion-field">
                        <input type="text" name="ubicaciones[]" placeholder="Ubicación 1" required>
                        <input type="number" name="capacidades_min[]" placeholder="Capacidad mínima" required>
                        <input type="number" name="capacidades_max[]" placeholder="Capacidad máxima" required>
                    </div>
                </div>
                <button type="button" onclick="agregarCampo()" class="add-button">+</button>

                <label for="punto_reorden">
                    Designar como punto de reordenamiento
                    <input type="checkbox" name="punto_reorden" id="punto_reorden">
                </label>

                <div class="actions">
                    <button type="submit" name="accion" value="crear_almacen">Guardar</button>
                    <a href="almacenes.view.php" class="button cancel-button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/crear_almacen.js"></script> <!-- Enlace al archivo JS -->
</body>
</html>
