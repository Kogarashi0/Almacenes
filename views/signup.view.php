<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../css/style.css">
    <script defer src="../js/signupValidation.js"></script>
</head>
<body>
    <div class="container">
        <h1>Crear una Cuenta</h1>
        <form id="signupForm" action="../controllers/signup.php" method="POST">
            <input type="text" name="username" id="username" placeholder="Usuario" required>
            <input type="text" name="firstname" id="firstname" placeholder="Nombre(s)" required>
            <input type="text" name="lastname" id="lastname" placeholder="Apellido(s)" required>
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirmar Contraseña" required>
            <br>
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.view.php">Inicia Sesión aquí</a></p>
    </div>
</body>
</html>
