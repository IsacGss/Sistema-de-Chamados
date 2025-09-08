-- Criação do banco de dados
CREATE DATABASE sistema_chamados;
USE sistema_chamados;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'tecnico', 'usuario') NOT NULL
);

-- Tabela de Chamados
CREATE TABLE chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    usuario_id INT NOT NULL,
    tecnico_id INT DEFAULT NULL,
    status ENUM('aberto', 'em_andamento', 'fechado') DEFAULT 'aberto',
    data_abertura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_fechamento DATETIME DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Inserção de dados iniciais para demonstração (baseado no login.php)
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Administrador', 'admin@sistema.com', 'password', 'admin'),
('João Técnico', 'joao@sistema.com', 'password', 'tecnico'),
('Maria Usuário', 'maria@sistema.com', 'password', 'usuario');
