<?php
session_start();
session_destroy();
header('Location: ../views/login.view.php');
exit();
?>
