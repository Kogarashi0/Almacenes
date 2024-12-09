<?php
// Iniciar sesión
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

// Verificar si el usuario es administrador
$is_admin = ($_SESSION['role'] === 'admin');

// Incluir la conexión a la base de datos
require_once '../config.php';
$query = "SELECT id, nombre, punto_reorden FROM almacenes";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Configuración de paginación
$filas_por_pagina = 5; // Número máximo de filas por página
$paginacion = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($paginacion < 1) $paginacion = 1;

// Calcular índice inicial para la consulta
$inicio = ($paginacion - 1) * $filas_por_pagina;

// Consulta para obtener el total de filas
$total_query = "SELECT COUNT(*) as total FROM almacenes";
$total_stmt = $conn->prepare($total_query);
$total_stmt->execute();
$total_result = $total_stmt->fetch(PDO::FETCH_ASSOC);
$total_filas = $total_result['total'];

// Calcular el total de páginas
$total_paginas = ceil($total_filas / $filas_por_pagina);

// Consulta para obtener los almacenes de la página actual
$query = "SELECT id, nombre, punto_reorden FROM almacenes LIMIT :inicio, :filas_por_pagina";
$stmt = $conn->prepare($query);
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':filas_por_pagina', $filas_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$almacenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Almacenes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/gestion_almacen.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <div class="container-almacenes">
        <div class="header-container">
            <h1>Gestión de Almacenes</h1>
            <?php if ($is_admin): ?>
                <form action="crear_almacen.view.php" method="get" style="display:inline;">
                    <button type="submit" class="button create-button">Crear Nuevo Almacén</button>
                </form>
            <?php endif; ?>
        </div>

        <hr>

        <h2>Lista de Almacenes</h2>
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Punto de Reordenamiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($almacenes)): ?>
                    <?php foreach ($almacenes as $almacen): ?>
                        <tr>
                            <td><?= htmlspecialchars($almacen['nombre']); ?></td>
                            <td><?= $almacen['punto_reorden'] ? 'Sí' : 'No'; ?></td>
                            <td>
                                <form action="ver_ubicaciones.view.php" method="get" style="display:inline;">
                                    <input type="hidden" name="almacen_id" value="<?= $almacen['id']; ?>">
                                    <button type="submit" class="button">Ver ubicaciones</button>
                                </form>
                                <?php if ($is_admin): ?>
                                    <form id="deleteForm-<?= $almacen['id']; ?>" action="../controllers/eliminar_almacen.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="almacen_id" value="<?= $almacen['id']; ?>">
                                        <button type="button" class="button delete-button" onclick="confirmDelete(<?= $almacen['id']; ?>)">Borrar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay almacenes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($paginacion > 1): ?>
                <a href="?pagina=<?= $paginacion - 1 ?>" class="button">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" class="button <?= $i === $paginacion ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($paginacion < $total_paginas): ?>
                <a href="?pagina=<?= $paginacion + 1 ?>" class="button">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function confirmDelete(almacenId) {
            const confirmation = confirm("¿Estás seguro de que deseas eliminar este almacén?");
            if (confirmation) {
                document.getElementById(`deleteForm-${almacenId}`).submit();
            }
        }
    </script>
</body>
</html>