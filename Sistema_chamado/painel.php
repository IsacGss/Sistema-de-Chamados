<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$tipo = $_SESSION['usuario_tipo'];

if ($tipo == 'admin') {
    header("Location: admin_painel.php");
} elseif ($tipo == 'tecnico') {
    header("Location: tecnico_painel.php");
} elseif ($tipo == 'usuario') {
    header("Location: usuario_painel.php");
}
exit;
?>