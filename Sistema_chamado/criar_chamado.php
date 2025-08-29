<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'usuario') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $stmt = $conn->prepare("INSERT INTO chamados (titulo, descricao, usuario_id) VALUES (:titulo, :descricao, :usuario_id)");
    $stmt->execute(['titulo' => $titulo, 'descricao' => $descricao, 'usuario_id' => $usuario_id]);
    $mensagem = "Chamado criado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Chamado</title>
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
                    <a href="usuario_painel.php" class="btn btn-outline">
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
                    <h1 class="card-title">Criar Novo Chamado</h1>
                </div>
                <div class="card-body">
                    <?php if ($mensagem): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $mensagem; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="form">
                        <div class="form-group">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Resumo do problema" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea id="descricao" name="descricao" class="form-control" placeholder="Descreva detalhadamente o problema" rows="5" required></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar Chamado
                            </button>
                        </div>
                    </form>
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