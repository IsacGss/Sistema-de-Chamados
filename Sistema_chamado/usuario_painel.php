<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'usuario') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Listar chamados do usuário
$stmt = $conn->prepare("SELECT * FROM chamados WHERE usuario_id = :id ORDER BY data_abertura DESC");
$stmt->execute(['id' => $usuario_id]);
$chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
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
                        Usuário
                    </span>
                    <a href="criar_chamado.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Chamado
                    </a>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="dashboard">
        <div class="container">
            <h1 class="page-title">Painel do Usuário</h1>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Meus Chamados</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($chamados)): ?>
                        <div class="empty-state">
                            <i class="fas fa-ticket-alt empty-icon"></i>
                            <h3>Nenhum chamado registrado</h3>
                            <p>Você ainda não abriu nenhum chamado</p>
                            <a href="criar_chamado.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Criar Primeiro Chamado
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($chamados as $chamado): ?>
                                        <tr>
                                            <td><?php echo $chamado['id']; ?></td>
                                            <td><?php echo $chamado['titulo']; ?></td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    echo $chamado['status'] == 'aberto' ? 'danger' : 
                                                         ($chamado['status'] == 'em_andamento' ? 'warning' : 'success'); 
                                                ?>">
                                                    <?php echo $chamado['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($chamado['data_abertura'])); ?></td>
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