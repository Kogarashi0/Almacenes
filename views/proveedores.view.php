<?php
require_once '../controllers/proveedores_controller.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/movimientos.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <div class="container">
        <h1>Proveedores</h1>

        <!-- Botón para exportar a CSV -->
        <div class="export-container">
            <a href="../controllers/exportar_proveedores.php" class="export-button">Exportar a CSV</a>
        </div>

        <!-- Selector de registros por página -->
        <form method="get" class="pagination-form">
            <label for="limit">Registros por página:</label>
            <select name="limit" id="limit" onchange="this.form.submit()">
                <option value="10" <?= $registros_por_pagina === 10 ? 'selected' : ''; ?>>10</option>
                <option value="15" <?= $registros_por_pagina === 15 ? 'selected' : ''; ?>>15</option>
                <option value="20" <?= $registros_por_pagina === 20 ? 'selected' : ''; ?>>20</option>
                <option value="50" <?= $registros_por_pagina === 50 ? 'selected' : ''; ?>>50</option>
            </select>
        </form>

        <!-- Tabla de proveedores -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proveedores)): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($proveedor['id']); ?></td>
                            <td><?= htmlspecialchars($proveedor['nombre']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay proveedores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?page=<?= $i; ?>&limit=<?= $registros_por_pagina; ?>" 
                   class="<?= $i == $pagina_actual ? 'active' : ''; ?>">
                    <?= $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
