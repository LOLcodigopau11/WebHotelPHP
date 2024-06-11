<?php
require_once '../../database/Conexion.php';

if (isset($_POST['cod'])) {
    $cod = $_POST['cod'];

    $con = new Conexion();
    $db = $con->getCon();
    // Preparar la consulta para obtener el id del pago asociado a la reserva
    $stmt = $db->prepare("SELECT pago_id FROM tb_reserva WHERE id_reserva = :cod");
    $stmt->bindParam(':cod', $cod);
    $stmt->execute();
    $row = $stmt->fetch();
    $pago_id = $row['pago_id'];

    // Preparar la consulta para eliminar la reserva
    $stmt_reserva = $db->prepare("DELETE FROM tb_reserva WHERE id_reserva = :cod");
    $stmt_reserva->bindParam(':cod', $cod);

    // Preparar la consulta para eliminar el registro de pago asociado
    $stmt_pago = $db->prepare("DELETE FROM tb_pago WHERE pago_id = :pago_id");
    $stmt_pago->bindParam(':pago_id', $pago_id);

    // Iniciar una transacción para asegurar que ambas consultas se completen correctamente o ninguna
    $db->beginTransaction();

    try {
        // Ejecutar la consulta para eliminar la reserva
        $stmt_reserva->execute();

        // Ejecutar la consulta para eliminar el registro de pago asociado
        $stmt_pago->execute();

        // Confirmar la transacción
        $db->commit();
        // Envía una respuesta de éxito al cliente
        echo "success";
    } catch (PDOException $e) {
        // Si alguna consulta falla, se revierte la transacción y se envía una respuesta de error al cliente
        $db->rollBack();
        echo "error";
    }
} 