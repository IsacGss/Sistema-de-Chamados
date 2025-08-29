<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'tecnico') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$tecnico_id = $_SESSION['usuario_id'];

// Usar data_fechamento em vez de data_technamento
$stmt = $conn->prepare("UPDATE chamados SET status = 'fechado', data_fechamento = NOW() WHERE id = :id AND tecnico_id = :tecnico_id AND status = 'em_andamento'");
$stmt->execute(['id' => $id, 'tecnico_id' => $tecnico_id]);

header("Location: tecnico_painel.php");
exit;
?>