<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$mensagem = "";

// Buscar usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'] ? $_POST['senha'] : $usuario['senha']; // Manter senha se não alterada
    $tipo = $_POST['tipo'];

    $stmt_update = $conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, tipo = :tipo WHERE id = :id");
    $stmt_update->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha, 'tipo' => $tipo, 'id' => $id]);
    $mensagem = "Usuário atualizado com sucesso!";
    // Atualizar dados buscados
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
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
                    <a href="admin_painel.php" class="btn btn-outline">
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
                    <h1 class="card-title">Editar Usuário</h1>
                </div>
                <div class="card-body">
                    <?php if ($mensagem): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $mensagem; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="form">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $usuario['nome']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo $usuario['email']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="senha" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                            <input type="password" id="senha" name="senha" class="form-control" placeholder="Nova senha">
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo" class="form-label">Tipo de Usuário</label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="admin" <?php if ($usuario['tipo'] == 'admin') echo 'selected'; ?>>Administrador</option>
                                <option value="tecnico" <?php if ($usuario['tipo'] == 'tecnico') echo 'selected'; ?>>Técnico</option>
                                <option value="usuario" <?php if ($usuario['tipo'] == 'usuario') echo 'selected'; ?>>Usuário</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Atualizar Usuário
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