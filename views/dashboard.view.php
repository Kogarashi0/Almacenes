<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

// Simular datos para el ejemplo (sustituir con consultas reales)
$ultimas_entradas = [
    "Entrada de 10 productos a Almacén A",
    "Entrada de 15 productos a Almacén B",
    "Entrada de 8 productos a Almacén C",
    "Entrada de 20 productos a Almacén D",
    "Entrada de 12 productos a Almacén E"
];

$ultimas_salidas = [
    "Salida de 5 productos de Almacén A",
    "Salida de 7 productos de Almacén B",
    "Salida de 10 productos de Almacén C",
    "Salida de 6 productos de Almacén D",
    "Salida de 4 productos de Almacén E"
];

$ultimos_logs = [
    "Usuario1 ha realizado una entrada de 10 productos a Almacén A",
    "Usuario2 ha realizado una salida de 5 productos de Almacén B",
    "Usuario3 ha añadido al proveedor ProveedorX al sistema",
    "Usuario4 ha transferido 20 productos de Almacén C a Almacén D",
    "Usuario5 ha realizado una entrada de 15 productos a Almacén A"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <main class="dashboard-container">
        <h1>Panel de Control</h1>
        <div class="dashboard-grid">
            <!-- Columna izquierda: Entradas y Salidas -->
            <div class="column">
                <div class="card">
                    <h2>Últimas Entradas</h2>
                    <ul>
                        <?php foreach ($ultimas_entradas as $entrada): ?>
                            <li><?php echo htmlspecialchars($entrada); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card">
                    <h2>Últimas Salidas</h2>
                    <ul>
                        <?php foreach ($ultimas_salidas as $salida): ?>
                            <li><?php echo htmlspecialchars($salida); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <!-- Columna derecha: Logs -->
            <div class="column">
                <div class="card logs-card">
                    <h2>Últimos Logs del Sistema</h2>
                    <ul>
                        <?php foreach ($ultimos_logs as $log): ?>
                            <li><?php echo htmlspecialchars($log); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
