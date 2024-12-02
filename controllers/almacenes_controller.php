<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? null;

    if ($accion === 'crear_almacen') {
        // Validar y capturar los datos
        $nombre = trim($_POST['nombre'] ?? '');
        $ubicaciones = $_POST['ubicaciones'] ?? [];
        $capacidades_min = $_POST['capacidades_min'] ?? [];
        $capacidades_max = $_POST['capacidades_max'] ?? [];
        $punto_reorden = isset($_POST['punto_reorden']) ? 1 : 0;

        // Filtrar y validar datos
        $ubicaciones = array_map('trim', $ubicaciones);
        $ubicaciones = array_filter($ubicaciones);

        if (empty($nombre)) {
            die("El nombre del almacén es obligatorio.");
        }

        if (empty($ubicaciones) || count($ubicaciones) !== count($capacidades_min) || count($ubicaciones) !== count($capacidades_max)) {
            die("Todas las ubicaciones deben tener capacidades válidas.");
        }

        foreach ($capacidades_min as $i => $cap_min) {
            if ($cap_min <= 0 || $capacidades_max[$i] <= 0 || $cap_min > $capacidades_max[$i]) {
                die("Capacidades inválidas para la ubicación: {$ubicaciones[$i]}");
            }
        }

        // Insertar en la base de datos
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO almacenes (nombre, punto_reorden) VALUES (?, ?)");
            $stmt->execute([$nombre, $punto_reorden]);

            $almacen_id = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO ubicaciones (almacen_id, nombre, capacidad_min, capacidad_max) VALUES (?, ?, ?, ?)");
            foreach ($ubicaciones as $i => $ubicacion) {
                $stmt->execute([$almacen_id, $ubicacion, $capacidades_min[$i], $capacidades_max[$i]]);
            }

            $conn->commit();
            header("Location: ../views/almacenes.view.php?success=1");
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            die("Error al guardar el almacén: " . $e->getMessage());
        }
    }
} else {
    header("Location: ../views/almacenes.view.php");
    exit();
}
