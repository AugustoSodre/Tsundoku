# Tsundoku - RPG Character Management System

📚 **Tsundoku** (積ん読) é uma palavra japonesa que se refere ao hábito de adquirir materiais de leitura e deixá-los empilhados sem ler. Similar a nós, jogadores de RPG, que colecionamos várias ideias de personagens que talvez nunca joguemos - mas guardamos mesmo assim!

## 📖 Sobre o Projeto

Tsundoku é um sistema de gerenciamento de personagens de D&D 5E desenvolvido em PHP. O projeto permite que usuários criem, editem e gerenciem seus personagens de RPG de forma organizada e intuitiva.

### ✨ Funcionalidades Principais

- Sistema completo de autenticação de usuários
- CRUD completo de personagens
- Sistema de filtragem e ordenação de personagens
- Interface responsiva e moderna
- Gerenciamento de atributos de personagem
- Proteção contra vulnerabilidades comuns

## 🛠️ Tecnologias Utilizadas

- **Frontend:**
  - HTML5
  - CSS3
  - Bootstrap 5
  - JavaScript

- **Backend:**
  - PHP
  - MySQL
  - Apache (XAMPP)

## 🚀 Como Executar o Projeto

### Pré-requisitos

- XAMPP instalado
- MySQL configurado
- PHP 7.4 ou superior

### Instalação

1. Clone o repositório para a pasta htdocs do XAMPP:
```bash
git clone [url-do-repositório] /xampp/htdocs/RPG-Character-Management-System
```

2. Importe o banco de dados:
- Abra o phpMyAdmin
- Crie um novo banco de dados chamado `rpg_manager`
- Importe o arquivo SQL fornecido na pasta `database`

3. Configure a conexão com o banco de dados:
- Abra o arquivo `Configs/db.config.php`
- Ajuste as credenciais conforme necessário:
```php
$host = "localhost";
$dbname = "rpg_manager";
$username = "seu_usuario";
$password = "sua_senha";
```

4. Inicie o Apache e MySQL no XAMPP

5. Acesse o projeto através do navegador:
```
http://localhost/RPG-Character-Management-System
```

## 🗃️ Estrutura do Banco de Dados

O sistema utiliza duas tabelas principais:

- **users**: Armazena informações dos usuários
- **characters**: Dados básicos dos personagens
- **attributes**: Atributos específicos dos personagens

## 🔐 Segurança

O sistema implementa várias medidas de segurança:

- Senhas armazenadas com hash
- Validação de entrada de dados
- Proteção contra SQL Injection
- Gerenciamento seguro de sessões
- Prevenção contra XSS

## 🎯 Futuras Melhorias

- [ ] Sistema de equipamentos
- [ ] Gerenciamento de magias
- [ ] Exportação de ficha em PDF
- [ ] Sistema de campanha compartilhada
- [ ] Dados online para jogadas

## 👨‍💻 Autor

Augusto Sodré

## 📝 Licença

Este projeto está sob a licença [sua escolha de licença].

---

## 🤝 Contribuições

Contribuições são sempre bem-vindas! Sinta-se à vontade para:

1. Fazer um fork do projeto
2. Criar uma branch para sua feature
3. Commitar suas mudanças
4. Fazer push para a branch
5. Abrir um Pull Request