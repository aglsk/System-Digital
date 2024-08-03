# System Digital

**System Digital** é um sistema de gerenciamento de mídia digital que permite aos usuários fazer upload, visualizar e gerenciar arquivos de mídia, incluindo imagens, vídeos e áudios. Este sistema é baseado em PHP e utiliza MySQL para armazenar e gerenciar dados.

## Funcionalidades

- **Upload de Arquivos**: Permite aos usuários fazer upload de imagens, vídeos e áudios.
- **Visualização de Arquivos**: Exibe uma pré-visualização dos arquivos carregados antes de completar o upload.
- **Painel de Administração**: Área restrita para administração onde é possível visualizar todos os arquivos carregados, excluí-los e acessar a área de upload.
- **Gerenciamento de Sessões**: Sistema de login e logout para proteger áreas administrativas.
- **Limpeza Automática**: Script para apagar arquivos que estão armazenados há mais de 7 dias.

## Tecnologias Utilizadas

- **PHP**: Linguagem de programação para o backend.
- **MySQL**: Banco de dados para armazenamento de informações.
- **Bootstrap**: Framework CSS para estilização da interface.
- **jQuery**: Biblioteca JavaScript para manipulação do DOM e eventos.

## Instalação

1. **Clone o Repositório**

   ```bash
   git clone https://github.com/aglsk/System-Digital.git
   ```

2. **Configuração do Ambiente**

   - Configure um servidor web local, como XAMPP ou WAMP.
   - Crie um banco de dados no MySQL e importe o schema fornecido no arquivo `schema.sql`.

3. **Configuração do Banco de Dados**

   Edite os arquivos `upload_process.php` e `admin_panel.php` para incluir as configurações do seu banco de dados:

   ```php
   $servername = "seu_servidor";
   $username = "seu_usuario";
   $password = "sua_senha";
   $dbname = "seu_banco_de_dados";
   ```

4. **Suba os Arquivos**

   - Coloque os arquivos do projeto na pasta `htdocs` (para XAMPP) ou equivalente do seu servidor web.

5. **Crie um Usuário Administrador**

   Use um cliente MySQL para inserir um usuário administrador na tabela `users`:

   ```sql
   INSERT INTO users (username, password_hash, display_name) VALUES ('admin', '<hash_da_senha>', 'Administrador');
   ```

   Substitua `<hash_da_senha>` com o hash da senha gerado usando `password_hash()`.

## Estrutura do Projeto

- **`index.php`**: Página principal que lista todos os arquivos.
- **`upload_form.php`**: Formulário para fazer upload de novos arquivos.
- **`upload_process.php`**: Processa o upload e armazenamento dos arquivos.
- **`admin_panel.php`**: Painel de administração para gerenciar arquivos e usuários.
- **`delete_old_files.php`**: Script para apagar arquivos antigos.
- **`login.php`**: Tela de login.
- **`logout.php`**: Finaliza a sessão do usuário.
- **`view_file.php`**: Exibe a visualização de arquivos individuais.
- **`schema.sql`**: Script para criar as tabelas do banco de dados.

## Uso

1. **Login**

   Acesse a página `login.php` e faça login com suas credenciais.

2. **Upload de Arquivos**

   Após o login, você pode acessar `upload_form.php` para carregar novos arquivos.

3. **Visualização de Arquivos**

   Acesse `index.php` para visualizar todos os arquivos carregados. Você pode pré-visualizar, baixar e excluir arquivos através da interface.

4. **Administração**

   Na área de administração (`admin_panel.php`), você pode gerenciar arquivos e acessar o painel de administração.

## Contribuições

Contribuições são bem-vindas! Se você deseja melhorar o projeto, por favor, envie um pull request com suas alterações.

## Licença

Este projeto está licenciado sob a [GPL-3.0 license](LICENSE).

---

**System Digital** é um projeto de código aberto desenvolvido para gerenciamento de mídia digital. Para mais informações, consulte a documentação e o código-fonte disponível neste repositório.

``` senha
User: admin
Pass: admin
```
