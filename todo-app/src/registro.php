<?php
require 'connection.php';
?>
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
                
                <!-- novo arquivo -->
                <?php include ('mensagem.php'); ?>
                
                <!-- esse action é novo -->
                <form method="POST" action="acoes.php">
                    <div class="mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" name="nomeCadastro" required>
                    </div>
                    <div class="mb-3">
                        <label  class="form-label">Email</label>
                        <input type="email" class="form-control" name="emailCadastro" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="senhaCadastro" id="senhaCadastro" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleSenhaCadastro">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" name="create_usuario" class="btn btn-primary w-100 mb-3">Cadastrar</button>
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