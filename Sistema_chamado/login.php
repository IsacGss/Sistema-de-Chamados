<?php
session_start();
include 'conexao.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
    $stmt->execute(['email' => $email, 'senha' => $senha]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        header("Location: painel.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Chamados</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-headset"></i> Sistema de Chamados</h1>
                <p>Faça login para acessar o sistema</p>
            </div>
            
            <?php if ($erro): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                
                <div class="form-group">
                    <input type="password" name="senha" placeholder="Senha" required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
            
            <div class="demo-info">
                <h3>Credenciais de Demonstração:</h3>
                <p><strong>Admin:</strong> admin@sistema.com / password</p>
                <p><strong>Técnico:</strong> joao@sistema.com / password</p>
                <p><strong>Usuário:</strong> maria@sistema.com / password</p>
            </div>
        </div>
    </div>
</body>
</html>