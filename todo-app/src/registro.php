<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validações básicas
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "E-mail inválido.";
    } else {
        // Verifica e insere
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "E-mail já cadastrado.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $ins->bind_param("sss", $name, $email, $hash);
            if ($ins->execute()) {
                $_SESSION['success'] = "Cadastro realizado com sucesso!";
                header("Location: login.php");                
                exit;
            } else {
                $error = "Erro ao cadastrar.";
            }
        }
    }
}
?>
<!-- HTML simples com form -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">Cadastro</h2>

                <!-- <form id="cadastroForm"> -->      

        
                    
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nomeCompleto" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nomeCompleto" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailCadastro" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailCadastro" required>
                    </div>
                    <div class="mb-3">
                        <label for="senhaCadastro" class="form-label">Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="senhaCadastro" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleSenhaCadastro">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Cadastrar</button>
                    <div class="text-center">
                        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>