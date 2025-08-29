<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] != 'tecnico' && $_SESSION['usuario_tipo'] != 'admin')) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// Buscar informações do chamado
$stmt = $conn->prepare("SELECT c.*, u.nome AS usuario_nome, u.email AS usuario_email, 
                               t.nome AS tecnico_nome 
                        FROM chamados c 
                        JOIN usuarios u ON c.usuario_id = u.id 
                        LEFT JOIN usuarios t ON c.tecnico_id = t.id 
                        WHERE c.id = :id");
$stmt->execute(['id' => $id]);
$chamado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chamado) {
    header("Location: " . ($_SESSION['usuario_tipo'] == 'admin' ? 'admin_painel.php' : 'tecnico_painel.php'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamado #<?php echo $chamado['id']; ?> - Sistema de Chamados</title>
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
                        <?php echo ucfirst($_SESSION['usuario_tipo']); ?>
                    </span>
                    <a href="<?php echo $_SESSION['usuario_tipo'] == 'admin' ? 'admin_painel.php' : 'tecnico_painel.php'; ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Voltar
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
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Chamado #<?php echo $chamado['id']; ?></h1>
                    <span class="badge badge-<?php 
                        echo $chamado['status'] == 'aberto' ? 'danger' : 
                             ($chamado['status'] == 'em_andamento' ? 'warning' : 'success'); 
                    ?>">
                        <?php echo $chamado['status']; ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="chamado-details">
                        <div class="detail-row">
                            <div class="detail-label">Título</div>
                            <div class="detail-value"><?php echo $chamado['titulo']; ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Descrição</div>
                            <div class="detail-value"><?php echo nl2br($chamado['descricao']); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Solicitante</div>
                            <div class="detail-value"><?php echo $chamado['usuario_nome']; ?> (<?php echo $chamado['usuario_email']; ?>)</div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Técnico Responsável</div>
                            <div class="detail-value"><?php echo $chamado['tecnico_nome'] ? $chamado['tecnico_nome'] : 'Nenhum técnico atribuído'; ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Data de Abertura</div>
                            <div class="detail-value"><?php echo date('d/m/Y H:i', strtotime($chamado['data_abertura'])); ?></div>
                        </div>
                        
                        <?php if (!empty($chamado['data_fechamento'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Data de Fechamento</div>
                            <div class="detail-value"><?php echo date('d/m/Y H:i', strtotime($chamado['data_fechamento'])); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($_SESSION['usuario_tipo'] == 'tecnico'): ?>
                    <div class="chamado-actions">
                        <?php if ($chamado['status'] == 'aberto'): ?>
                            <a href="atender_chamado.php?id=<?php echo $chamado['id']; ?>" class="btn btn-success">
                                <i class="fas fa-tools"></i> Atender Chamado
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($chamado['status'] == 'em_andamento' && $chamado['tecnico_id'] == $_SESSION['usuario_id']): ?>
                            <a href="fechar_chamado.php?id=<?php echo $chamado['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-check"></i> Fechar Chamado
                            </a>
                        <?php endif; ?>
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