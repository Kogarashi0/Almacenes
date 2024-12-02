<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <div class="dashboard-container">
        <h1>Bienvenido al sistema</h1>
        <p>Selecciona una opción del menú para empezar.</p>
    </div>
</body>
</html>
