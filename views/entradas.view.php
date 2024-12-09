<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

require_once '../controllers/entradas_controller.php'; // Controlador para manejar la lógica

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/movimientos.css">
</head>
<body>
<?php include '../partials/navbar.php'; ?>

<div class="container">
    <h1>Entradas</h1>

    <!-- Botón para exportar a CSV -->
    <div class="export-container">
        <a href="../controllers/exportar_movimientos.php?type=entradas" class="export-button">Exportar a CSV</a>
    </div>

    <!-- Selector de límite -->
    <form method="get" class="form-limit">
        <label for="limit">Mostrar:</label>
        <select name="limit" id="limit" onchange="this.form.submit()">
            <option value="10" <?= $limit === 10 ? 'selected' : '' ?>>10</option>
            <option value="15" <?= $limit === 15 ? 'selected' : '' ?>>15</option>
            <option value="20" <?= $limit === 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $limit === 50 ? 'selected' : '' ?>>50</option>
        </select>
    </form>

    <!-- Tabla de entradas -->
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Ubicación Destino</th>
                <th>Tipo</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entradas as $entrada): ?>
                <tr>
                    <td><?= htmlspecialchars($entrada['fecha']); ?></td>
                    <td><?= htmlspecialchars($entrada['producto']); ?></td>
                    <td><?= htmlspecialchars($entrada['cantidad']); ?></td>
                    <td><?= htmlspecialchars($entrada['ubicacion_destino']); ?></td>
                    <td><?= htmlspecialchars($entrada['tipo']); ?></td>
                    <td><?= htmlspecialchars($entrada['descripcion']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?limit=<?= $limit ?>&page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

</div>
</body>
</html>
