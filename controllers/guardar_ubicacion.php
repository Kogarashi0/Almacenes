<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/ver_subicaciones.view.php?almacen_id=$almacen_id&success=Ubicación creada");
    exit();
}

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $almacen_id = intval($_POST['almacen_id']);
    $nombre = trim($_POST['nombre']);
    $capacidad_min = intval($_POST['capacidad_min']);
    $capacidad_max = intval($_POST['capacidad_max']);

    if (empty($nombre) || $capacidad_min < 0 || $capacidad_max <= $capacidad_min) {
        // Validar que los datos sean correctos
        header("Location: ../views/crear_ubicacion.view.php?almacen_id=$almacen_id&error=Datos inválidos");
        exit();
    }

    try {
        $query = "INSERT INTO ubicaciones (almacen_id, nombre, capacidad_min, capacidad_max) 
                  VALUES (:almacen_id, :nombre, :capacidad_min, :capacidad_max)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':capacidad_min', $capacidad_min, PDO::PARAM_INT);
        $stmt->bindParam(':capacidad_max', $capacidad_max, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../views/ver_ubicaciones.view.php?almacen_id=$almacen_id&success=Ubicación creada");
        exit();
    } catch (PDOException $e) {
        error_log("Error al guardar ubicación: " . $e->getMessage());
        header("Location: ../views/crear_ubicacion.view.php?almacen_id=$almacen_id&error=No se pudo guardar");
        exit();
    }
} else {
    header('Location: ../views/almacenes.view.php');
    exit();
}