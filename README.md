# Desafio-04: Desenvolvimento de um Sistema de Gerenciamento de Tarefas usando Docker Compose

### Objetivo: Criar um sistema simples de gerenciamento de tarefas (To-Do List) usando PHP, HTML, CSS, MySQL e Docker Compose.

Tecnologias Utilizadas:

⦁	Backend: PHP 5.6 (conforme descrito na vaga)
⦁	Frontend: HTML, CSS, JavaScript
⦁	Framework: Bootstrap 5
⦁	Banco de Dados: MariaDB
⦁	Ambiente de Desenvolvimento: Docker + Docker Compose 

### Requisitos:

1. Crie um diretório chamado "todo-app" e coloque todos os arquivos do projeto dentro desse diretório.

2. Crie um arquivo chamado "docker-compose.yml" no diretório raiz do projeto, com as seguintes configurações:

```yaml
version: '3'
services:
  web:
    image: php:apache
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./php-custom.ini:/usr/local/etc/php/conf.d/php-custom.ini
    depends_on:
      - db
    environment:
      - PHP_INI_SCAN_DIR=/usr/local/etc/php/conf.d
    command:
      bash -c "docker-php-ext-install mysqli && apache2-foreground"
  db:
    image: mariadb
    environment:
      MYSQL_ROOT_PASSWORD: example_root_password
      MYSQL_DATABASE: todo_db
      MYSQL_USER: todo_user
      MYSQL_PASSWORD: example_user_password
    volumes:
      - todo-data:/var/lib/mysql
      - ./bd:/docker-entrypoint-initdb.d
    ports:
      - "3307:3306"
volumes:
  todo-data:
```
Fiz algumas alterações nessa parte. Como:
volumes e environment: php-custom.ini permite ter um controle melhor dos erros.
command: comando de instalação para garantir que a extensão PHP necessária para se conectar ao banco de dados seja instalada automaticamente quando o contêiner é iniciado. Evita erros de conexão.

Na parte de bd adicionei um entrypoint no volume que permite que o init.sql seja executado automaticamente na primeira vez que o contêiner do banco de dados é iniciado, criando a estrutura de tabelas necessária.

3. Crie uma pasta chamada "src" dentro do diretório "todo-app". Todos os arquivos PHP, HTML e CSS do projeto devem ser colocados dentro desta pasta.

4. Implemente o sistema de gerenciamento de tarefas dentro da pasta "src" de acordo com os requisitos mencionados anteriormente.

5. Certifique-se de que o arquivo de conexão com o banco de dados (por exemplo, "connection.php") esteja configurado para se conectar ao contêiner do banco de dados. Use as seguintes configurações de conexão:

   ```php
  <?php
  date_default_timezone_set('America/Sao_Paulo');
  $host = 'db';
  $user = 'todo_user';
  $password = 'example_user_password';
  $database = 'todo_db';

  $conn = new mysqli($host, $user, $password, $database);
  if ($conn->connect_error) {
      die('Connection failed: ' . $conn->connect_error);
  }
   ```
Única mudança é 
6. Certifique-se de que o arquivo "index.php" (ou página principal) esteja configurado como ponto de entrada do sistema e exiba a lista de tarefas e formulários para adicionar e editar tarefas.

7. Teste o sistema localmente usando Docker Compose, execute o seguinte comando no terminal a partir do diretório "todo-app":

   ```
   docker-compose up
   ```

   Isso criará os contêineres para o PHP com Apache e o banco de dados MariaDB. O sistema estará acessível em http://localhost:8080.

   Se der algum erro quando for executar o comando docker-compose up. Verifique se o Docker desktop está aberto e em execução.

8. Página de registro de usuários (campos: nome, e-mail e senha).

9. Página de login para permitir que os usuários acessem o sistema.

10. Página principal após o login, onde o usuário pode adicionar, editar, excluir e marcar tarefas como concluídas.

11. As tarefas devem ter pelo menos os seguintes campos: título, descrição, data de criação e status (pendente/concluída).

12. As tarefas devem ser exibidas em uma lista, e o usuário deve poder ordenar a lista por data de criação ou por status.

13. O sistema deve ter validação de formulário tanto no registro quanto no login.

14. Use MariaDb para armazenar os dados das tarefas e dos usuários.

15. O sistema não precisa ter autenticação de usuário por meio de e-mail. Basta permitir que um usuário registrado faça login usando usuário e senha.

16. O layout não precisa ser complexo, mas deve ser responsivo e ter uma aparência agradável.
