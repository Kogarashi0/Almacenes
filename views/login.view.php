<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/style.css">
    <script defer src="../js/loginValidation.js"></script>
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form id="loginForm" action="../controllers/login.php" method="POST">
            <input type="text" name="username" id="username" placeholder="Usuario" required>
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <p>¿No tienes una cuenta? <a href="signup.view.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
