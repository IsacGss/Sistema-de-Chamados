<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'tecnico') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$tecnico_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("UPDATE chamados SET status = 'em_andamento', tecnico_id = :tecnico_id WHERE id = :id AND status = 'aberto'");
$stmt->execute(['tecnico_id' => $tecnico_id, 'id' => $id]);

header("Location: tecnico_painel.php");
exit;
?>