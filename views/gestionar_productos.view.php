    <?php
    require_once '../config.php';

    // Obtener ubicaciones, productos y proveedores existentes
    $query_ubicaciones = "SELECT u.id, u.nombre AS ubicacion, a.nombre AS almacen FROM ubicaciones u JOIN almacenes a ON u.almacen_id = a.id";
    $ubicaciones = $conn->query($query_ubicaciones)->fetchAll(PDO::FETCH_ASSOC);

    $query_productos = "SELECT id, nombre FROM productos";
    $productos = $conn->query($query_productos)->fetchAll(PDO::FETCH_ASSOC);

    $query_proveedores = "SELECT id, nombre FROM proveedores";
    $proveedores = $conn->query($query_proveedores)->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestionar Productos</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/gestionar_productos.css">
        
    </head>
    <body>
        <div class="container">
            <h1>Gestionar Movimiento de Productos</h1>
            <form action="../controllers/gestionar_productos_controller.php" method="POST">
                <!-- Tipo de Movimiento -->
                <label for="tipo">Tipo de Movimiento:</label>
                <select name="tipo" id="tipo" required>
                    <option value="">Seleccione...</option>
                    <option value="compra">Compra</option>
                    <option value="venta">Venta</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="desecho">Desecho</option>
                </select>

                <!-- Campos dinámicos -->
                <div id="dynamic-fields"></div>

                <!-- Campos comunes -->
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" min="1" required>

                <label for="descripcion">Descripción (opcional):</label>
                <textarea name="descripcion" id="descripcion"></textarea>

                <button type="submit" class="button">Registrar Movimiento</button>
            </form>
            <div class="options">
                <a href="ver_ubicaciones.view.php" class="button">Volver</a>
            </div>
        </div>
        <script>
            const ubicacionesData = <?php echo json_encode($ubicaciones); ?>;
            const productosData = <?php echo json_encode($productos); ?>;
            const proveedoresData = <?php echo json_encode($proveedores); ?>;
        </script>
        <script src="../js/gestionar_productos.js"></script>
    </body>
    </html>
