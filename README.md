# 🕹️ Cartucho Velho - Plataforma de Jogos Retrô

> "Sopre o cartucho e aperte Start."

O **Cartucho Velho** é uma plataforma web desenvolvida como Projeto de Conclusão de Curso (TCC), focada em preservar a nostalgia dos jogos clássicos através de uma interface inspirada no Windows 98, mas com tecnologias web modernas.

## 📋 Sobre o Projeto

O sistema oferece uma coleção de jogos clássicos recriados em JavaScript puro, envoltos em um ambiente que simula um sistema operacional antigo. O projeto foca em:
- **Design de Interface (UI):** Estética retrô consistente (Windows 98/Pixel Art).
- **Experiência do Usuário (UX):** Comentários via AJAX (sem refresh), feedback visual e sonoro.
- **Acessibilidade:** Modo de Alto Contraste e controle de áudio.

### 🎮 Jogos Disponíveis
* **PONG:** O clássico do tênis de mesa.
* **Snake (Cobrinha):** Com lógica de "Input Buffer" para zero delay.
* **Campo Minado:** Com algoritmos de recursividade (Flood Fill).
* **Jogo da Velha:** Modos Player vs Player e Player vs CPU.

## 🚀 Tecnologias Utilizadas

* **Frontend:** HTML5, CSS3 (Bootstrap 5 customizado), JavaScript (Vanilla).
* **Backend:** PHP 8.x.
* **Banco de Dados:** MySQL.
* **Servidor Local Sugerido:** XAMPP, Laragon ou WAMP.

## ⚙️ Instalação e Configuração

Siga os passos abaixo para rodar o projeto localmente:

### 1. Pré-requisitos
Certifique-se de ter um ambiente de servidor local instalado

### 2. Clonar o Repositório
git clone [https://github.com/devNicolasAmaral/cartucho-velho.git](https://github.com/devNicolasAmaral/cartucho-velho.git)
Mova a pasta do projeto para dentro do diretório do seu servidor.

### 3. Configurar o Banco de Dados
Abra o phpMyAdmin.
Crie um novo banco de dados chamado db_cartucho_velho (ou o nome que estiver no seu config.php).
Importe o arquivo cv.sql (que deve estar na pasta /0-setup deste repositório).

### 4. Configurar Conexão
Verifique o arquivo dev/exec/conexao_banco.php e config.php e certifique-se de que as credenciais batem com as do seu servidor local.

### 5. Executar
Acesse no seu navegador: http://localhost/cartucho-velho/index.php

🧪 Funcionalidades de Destaque
Sistema de Login/Cadastro: Autenticação segura com PHP Sessions.
Comentários Assíncronos: Postagem de mensagens nos jogos sem recarregar a página (Fetch API).
Design Responsivo: Layout adaptável mantendo a estética retrô.
Upload de Perfil: Gerenciamento de avatares de usuário.

Desenvolvido por Matheus Lopes, Nicolas Amaral, Raisa Silva - 2025.
