# Sistema de Chamados - HelpDesk

Sistema de gerenciamento de chamados técnicos desenvolvido em PHP com interface responsiva e design moderno. Permite que usuários abram chamados, técnicos atendam e resolvam problemas, e administradores gerenciem o sistema completo.

## 🚀 Funcionalidades

- **Autenticação de usuários** com três níveis de acesso (Admin, Técnico, Usuário)
- **Abertura de chamados** por usuários com descrição detalhada dos problemas
- **Painel para técnicos** visualizar, atender e fechar chamados
- **Painel administrativo** completo para gerenciar usuários e monitorar todos os chamados
- **Design responsivo** que funciona perfeitamente em mobile e desktop
- **Interface intuitiva** inspirada no TomTicket com experiência profissional

## 👥 Papeis de Usuário

- **Administrador**: Gerencia todos os usuários e visualiza todos os chamados
- **Técnico**: Atende e resolve chamados, visualizando descrições completas dos problemas
- **Usuário**: Abre novos chamados e acompanha o status de seus pedidos

## 🛠️ Tecnologias Utilizadas

- PHP 7.4+
- MySQL/MariaDB
- HTML5, CSS3 com design responsivo
- JavaScript (para interações de UI)
- Font Awesome para ícones
- Google Fonts (Poppins)

## 📦 Instalação

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/sistema-chamados.git
```

2. Configure o banco de dados MySQL:
- Importe o arquivo `database.sql` (se disponível)
- Ou execute as queries de criação das tabelas

3. Configure a conexão com o banco no arquivo `conexao.php`:
```php
$host = "localhost";
$usuario = "seu_usuario";
$senha = "sua_senha";
$banco = "sistema_chamados";
```

4. Acesse o sistema via navegador e faça login com as credenciais padrão:
- Admin: admin@sistema.com / password
- Técnico: joao@sistema.com / password  
- Usuário: maria@sistema.com / password

## 🌟 Recursos Destacados

- Interface limpa e profissional com paleta de cores harmoniosa
- Estatísticas em tempo real no dashboard
- Sistema de badges coloridos para status de chamados
- Animações suaves e transições elegantes
- Menu mobile com toggle para dispositivos menores
- Formulários validados e mensagens de feedback
