# Check&Go - Backend Administrativo em PHP

Este projeto consiste na migração e adaptação tecnológica da área de administração do sistema **Check&Go** para PHP, utilizando uma arquitetura robusta com ligação direta à base de dados via **PDO**.

## 🚀 Funcionalidades Implementadas

### 1. Autenticação e Segurança (RBAC)
- **Sistema de Login**: Autenticação segura via PHP Sessions.
- **Controlo de Acessos (RBAC)**:
  - **Administrador**: Acesso total ao sistema (Lojas, Serviços, Colaboradores).
  - **Gerente**: Acesso limitado à gestão da sua própria loja e colaboradores associados.
  - **Colaborador**: Bloqueio de acesso com mensagem personalizada: *"Não tem privilégios suficientes para aceder a esta página"*.

### 2. Gestão de Entidades (CRUDs)
- **Lojas**:
  - Listagem com visualização de serviços ativos e gerente responsável.
  - Criação e Edição com associação dinâmica de serviços e seleção de gerente.
- **Colaboradores**:
  - Listagem robusta com filtros por cargo e loja.
  - Criação direta na base de dados (compatível com sistemas de autenticação externos).
- **Serviços**:
  - Gestão completa de serviços disponíveis no sistema.

### 3. Tecnologia e Performance
- **PDO (PHP Data Objects)**: Ligação direta ao PostgreSQL do Supabase, garantindo maior velocidade e fiabilidade.
- **Connection Pooler**: Configurado para suportar múltiplas ligações simultâneas de forma eficiente.
- **Deteção Dinâmica de Colunas**: O sistema adapta-se automaticamente se a coluna de password se chamar `password` ou `senha`.

### 4. Interface Visual
- **Design Original**: Layout 100% fiel ao projeto original em Node.js.
- **Sidebar Dinâmica**: Menu lateral com ícones, logotipo e informações do utilizador logado.
- **Feedback Visual**: Checkboxes pré-marcadas na edição de lojas para facilitar a gestão de serviços.

## 🛠️ Configuração

1. **Requisitos**:
   - Servidor PHP (Laragon, XAMPP, etc.)
   - Extensão `pdo_pgsql` ativa no PHP.
   - Extensão `pgsql` ativa no PHP

2. **Base de Dados**:
   - Edite o ficheiro `config/database.php` com as suas credenciais do Supabase (Host, User, Password).

3. **Acesso**:
   - Aponte o seu navegador para `https://patric.antrob.eu/public/login.php`.

## 📁 Estrutura de Pastas
- `config/`: Configurações de base de dados.
- `controllers/`: Lógica de negócio e manipulação de dados.
- `middleware/`: Proteção de rotas e segurança.
- `views/`: Interfaces e páginas do sistema.
- `public/`: Ficheiros públicos (CSS, Imagens, Login).

---
Desenvolvido para a disciplina de Programação Web.
