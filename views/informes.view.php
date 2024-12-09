<?php require_once('../controllers/informes_controller.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/movimientos.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <main>
        <h1>Registro de Informes</h1>
        <div class="controls">
            <label for="logs-limit">Mostrar</label>
            <select id="logs-limit">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
            </select>
            <span>logs por página</span>
        </div>
        <div class="logs-container">
            <ul id="logs-list">
                <?php foreach ($informes as $informe): ?>
                    <li><?php echo htmlspecialchars($informe); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="pagination">
            <button id="prev-page" disabled>&laquo; Anterior</button>
            <span id="page-info">Página 1 de 1</span>
            <button id="next-page" disabled>Siguiente &raquo;</button>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const logs = <?php echo json_encode($informes); ?>;
            const logsList = document.getElementById('logs-list');
            const logsLimit = document.getElementById('logs-limit');
            const prevPage = document.getElementById('prev-page');
            const nextPage = document.getElementById('next-page');
            const pageInfo = document.getElementById('page-info');

            let currentPage = 1;
            let logsPerPage = parseInt(logsLimit.value);

            const renderLogs = () => {
                const startIndex = (currentPage - 1) * logsPerPage;
                const endIndex = startIndex + logsPerPage;
                const currentLogs = logs.slice(startIndex, endIndex);

                logsList.innerHTML = currentLogs.map(log => `<li>${log}</li>`).join('');

                pageInfo.textContent = `Página ${currentPage} de ${Math.ceil(logs.length / logsPerPage)}`;
                prevPage.disabled = currentPage === 1;
                nextPage.disabled = currentPage === Math.ceil(logs.length / logsPerPage);
            };

            logsLimit.addEventListener('change', () => {
                logsPerPage = parseInt(logsLimit.value);
                currentPage = 1;
                renderLogs();
            });

            prevPage.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderLogs();
                }
            });

            nextPage.addEventListener('click', () => {
                if (currentPage < Math.ceil(logs.length / logsPerPage)) {
                    currentPage++;
                    renderLogs();
                }
            });

            renderLogs();
        });
    </script>
</body>
</html>
