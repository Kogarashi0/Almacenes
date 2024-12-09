<?php
require_once '../config.php';
session_start();

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "No tienes permisos para realizar esta acción.";
    exit();
}

// Verificar si se recibió el ID del almacén
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['almacen_id'])) {
    $almacen_id = intval($_POST['almacen_id']);

    try {
        // Iniciar una transacción para asegurar consistencia
        $conn->beginTransaction();

        // Eliminar ubicaciones asociadas al almacén
        $deleteLocationsQuery = "DELETE FROM ubicaciones WHERE almacen_id = :almacen_id";
        $stmt = $conn->prepare($deleteLocationsQuery);
        $stmt->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
        $stmt->execute();

        // Eliminar el almacén
        $deleteWarehouseQuery = "DELETE FROM almacenes WHERE id = :almacen_id";
        $stmt = $conn->prepare($deleteWarehouseQuery);
        $stmt->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        // Redirigir con éxito
        header('Location: ../views/almacenes.view.php?status=success');
        exit();
    } catch (Exception $e) {
        // Revertir la transacción si ocurre un error
        $conn->rollBack();
        error_log("Error al eliminar el almacén: " . $e->getMessage());
        header('Location: ../views/almacenes.view.php?status=error');
        exit();
    }
} else {
    header('Location: ../views/almacenes.view.php?status=invalid_request');
    exit();
}