<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validar que las contraseñas coincidan
    if ($password !== $confirmPassword) {
        echo "<p>Las contraseñas no coinciden. <a href='../views/signup.view.php'>Intenta de nuevo</a></p>";
        exit();
    }

    // Validar la longitud de la contraseña
    if (strlen($password) < 6) {
        echo "<p>La contraseña debe tener al menos 6 caracteres. <a href='../views/signup.view.php'>Intenta de nuevo</a></p>";
        exit();
    }

    // Validar si el nombre de usuario ya existe
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetch()) {
        echo "<p>El nombre de usuario ya está en uso. <a href='../views/signup.view.php'>Intenta de nuevo</a></p>";
        exit();
    }

    // Insertar usuario en la base de datos
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO usuarios (username, firstname, lastname, password) VALUES (:username, :firstname, :lastname, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        // Redirigir al login después de un registro exitoso
        header('Location: ../views/login.view.php');
        exit();
    } else {
        echo "<p>Error al registrar el usuario. <a href='../views/signup.view.php'>Intenta de nuevo</a></p>";
        exit();
    }
}
?>
