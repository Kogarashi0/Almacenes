<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

// Verificar si el usuario es administrador
$is_admin = ($_SESSION['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Almacenes</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/scripts.js" defer></script>
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <div class="container">

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
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Punto de Reordenamiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
                <?php
                // Conexión a la base de datos y obtención de datos
                require_once '../config.php';

                $query = "SELECT id, nombre, punto_reorden FROM almacenes";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($result)): 
                    foreach ($result as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre']); ?></td>
                            <td><?= $row['punto_reorden'] ? 'Sí' : 'No'; ?></td>
                            <td>
                                <!-- Botón para Ver ubicaciones -->
                                <form action="ver_ubicaciones.view.php" method="get" style="display:inline;">
                                    <input type="hidden" name="almacen_id" value="<?= $row['id']; ?>">
                                    <button type="submit" class="button">Ver ubicaciones</button>
                                </form>

                                <!-- Botón para Borrar -->
                                <?php if ($is_admin): ?>
                                <form id="deleteForm-<?= $row['id']; ?>" action="../controllers/eliminar_almacen.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="almacen_id" value="<?= $row['id']; ?>">
                                        <button type="button" class="button delete-button" onclick="confirmDelete(<?= $row['id']; ?>)">Borrar</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="3">No hay almacenes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    function confirmDelete(almacenId) {
    const confirmation = confirm("¿Estás seguro de que deseas eliminar este almacén?");
        if (confirmation) {
            // Enviar el formulario si el usuario confirma
            document.getElementById(`deleteForm-${almacenId}`).submit();
        }
    }
    </script>

</body>
</html>