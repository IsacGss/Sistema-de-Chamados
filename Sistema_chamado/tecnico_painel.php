<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'tecnico') {
    header("Location: login.php");
    exit;
}

$tecnico_id = $_SESSION['usuario_id'];

// Listar todos os chamados
$stmt = $conn->prepare("SELECT c.*, u.nome AS usuario_nome FROM chamados c JOIN usuarios u ON c.usuario_id = u.id ORDER BY c.data_abertura DESC");
$stmt->execute();
$chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Técnico</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-headset"></i>
                    <span>Sistema de Chamados</span>
                </div>
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-menu" id="userMenu">
                    <span class="user-info">
                        <i class="fas fa-user-circle"></i>
                        Técnico
                    </span>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="dashboard">
        <div class="container">
            <h1 class="page-title">Painel do Técnico</h1>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Todos os Chamados</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($chamados)): ?>
                        <div class="empty-state">
                            <i class="fas fa-ticket-alt empty-icon"></i>
                            <h3>Nenhum chamado registrado</h3>
                            <p>Ainda não há chamados no sistema</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Usuário</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($chamados as $chamado): ?>
                                        <tr>
                                            <td><?php echo $chamado['id']; ?></td>
                                            <td><?php echo $chamado['titulo']; ?></td>
                                            <td><?php echo $chamado['usuario_nome']; ?></td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    echo $chamado['status'] == 'aberto' ? 'danger' : 
                                                         ($chamado['status'] == 'em_andamento' ? 'warning' : 'success'); 
                                                ?>">
                                                    <?php echo $chamado['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($chamado['data_abertura'])); ?></td>
                                            <td class="actions">
                                                <a href="visualizar_chamado.php?id=<?php echo $chamado['id']; ?>" class="btn btn-sm btn-outline" title="Ver detalhes">
                                                    <i class="fas fa-eye"></i> Detalhes
                                                </a>
                                                <?php if ($chamado['status'] == 'aberto'): ?>
                                                    <a href="atender_chamado.php?id=<?php echo $chamado['id']; ?>" class="btn btn-sm btn-success">
                                                        <i class="fas fa-tools"></i> Atender
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($chamado['status'] == 'em_andamento' && $chamado['tecnico_id'] == $tecnico_id): ?>
                                                    <a href="fechar_chamado.php?id=<?php echo $chamado['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-check"></i> Fechar
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Menu mobile toggle
        document.getElementById('mobileToggle').addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('active');
        });

        // Fechar menu ao clicar fora dele
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const mobileToggle = document.getElementById('mobileToggle');
            
            if (!userMenu.contains(event.target) && !mobileToggle.contains(event.target)) {
                userMenu.classList.remove('active');
            }
        });
    </script>
</body>
</html>