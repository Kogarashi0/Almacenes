<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "No autorizado."]);
    exit();
}

require_once '../config.php';

// Obtener datos enviados en formato JSON
$request_body = file_get_contents("php://input");
$data = json_decode($request_body, true);

if (isset($data['ubicacion_id']) && is_numeric($data['ubicacion_id'])) {
    $ubicacion_id = intval($data['ubicacion_id']);

    try {
        // Eliminar la ubicación de la base de datos
        $query = "DELETE FROM ubicaciones WHERE id = :ubicacion_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ubicacion_id', $ubicacion_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo eliminar la ubicación."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error en el servidor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Datos inválidos."]);
}