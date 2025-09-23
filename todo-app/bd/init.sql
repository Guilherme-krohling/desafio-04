CREATE DATABASE IF NOT EXISTS todo_db;
USE todo_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  status ENUM('pendente','concluida') DEFAULT 'pendente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);



-- Inserindo usuários de exemplo
-- INSERT INTO users (name, email, password) VALUES
-- ('João Silva', 'joao@exemplo.com', '123'),
-- ('Maria Santos', 'maria@exemplo.com', '123'),
-- ('Pedro Oliveira', 'pedro@exemplo.com', '123');

-- -- Inserindo tarefas de exemplo
-- INSERT INTO tasks (user_id, title, description, status) VALUES
-- (1, 'Completar relatório', 'Finalizar relatório mensal de vendas', 'pendente'),
-- (1, 'Reunião com cliente', 'Preparar apresentação para reunião', 'pendente'),
-- (2, 'Desenvolver API', 'Criar endpoints para novo sistema', 'finalizada'),
-- (2, 'Revisar código', 'Fazer code review do PR#123', 'pendente'),
-- (3, 'Backup do banco', 'Realizar backup semanal do banco de dados', 'pendente'),
-- (3, 'Atualizar documentação', 'Documentar novas funcionalidades', 'finalizada');