<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Listar todos os usuários
$stmt_users = $conn->prepare("SELECT * FROM usuarios");
$stmt_users->execute();
$usuarios = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Listar todos os chamados
$stmt_chamados = $conn->prepare("SELECT c.*, u.nome AS usuario_nome, t.nome AS tecnico_nome FROM chamados c 
                                 JOIN usuarios u ON c.usuario_id = u.id 
                                 LEFT JOIN usuarios t ON c.tecnico_id = t.id 
                                 ORDER BY c.data_abertura DESC");
$stmt_chamados->execute();
$chamados = $stmt_chamados->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
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
                        Administrador
                    </span>
                    <a href="adicionar_usuario.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Adicionar Usuário
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
            <h1 class="page-title">Painel do Administrador</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value"><?php echo count($usuarios); ?></div>
                    <div class="stat-label">Usuários</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-value"><?php echo count($chamados); ?></div>
                    <div class="stat-label">Chamados</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <?php
                    $abertos = array_filter($chamados, function($chamado) {
                        return $chamado['status'] == 'aberto';
                    });
                    ?>
                    <div class="stat-value"><?php echo count($abertos); ?></div>
                    <div class="stat-label">Em Aberto</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <?php
                    $fechados = array_filter($chamados, function($chamado) {
                        return $chamado['status'] == 'fechado';
                    });
                    ?>
                    <div class="stat-value"><?php echo count($fechados); ?></div>
                    <div class="stat-label">Resolvidos</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Usuários do Sistema</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($usuarios)): ?>
                        <div class="empty-state">
                            <i class="fas fa-users empty-icon"></i>
                            <h3>Nenhum usuário registrado</h3>
                            <p>Adicione usuários para começar a usar o sistema</p>
                            <a href="adicionar_usuario.php" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Adicionar Usuário
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Tipo</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['nome']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    echo $user['tipo'] == 'admin' ? 'primary' : 
                                                         ($user['tipo'] == 'tecnico' ? 'warning' : 'secondary'); 
                                                ?>">
                                                    <?php echo $user['tipo']; ?>
                                                </span>
                                            </td>
                                            <td class="actions">
                                                <a href="editar_usuario.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

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
                                        <th>Técnico</th>
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
                                            <td><?php echo $chamado['tecnico_nome'] ?? 'N/A'; ?></td>
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