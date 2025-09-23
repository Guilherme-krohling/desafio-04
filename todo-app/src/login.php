<?php
session_start();
require 'connection.php';
 
if (isset($_POST['select_usuario'])) {
    $email = trim($_POST['emailLogin']);
    $senha = trim($_POST['senhaLogin']);
 
    // Busca o usuário pelo email usando prepared statement
    $sql = "SELECT id, name, password FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
 
    if ($row = mysqli_fetch_assoc($result)) {
        // Verificação de senha (compatível com hash e texto plano)
        $loginSuccessful = false;
       
        // Verifica se a senha no banco está em formato hash
        if (password_get_info($row['password'])['algo'] !== null) {
            // Senha está em hash - usa password_verify
            $loginSuccessful = password_verify($senha, $row['password']);
        } else {
            // Senha está em texto plano - comparação direta
            $loginSuccessful = ($senha === $row['password']);
        }
       
        if ($loginSuccessful) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['mensagem'] = "Senha incorreta.";
        }
    } else {
        $_SESSION['mensagem'] = "Usuário não encontrado.";
    }
    mysqli_stmt_close($stmt);
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">Login</h2>
 
                <?php include 'mensagem.php'; ?>
 
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="emailLogin"required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="senhaLogin" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleSenha">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" name="select_usuario" class="btn btn-primary w-100 mb-3">Entrar</button>
                    <div class="text-center">
                        <p>Não tem uma conta? <a href="registro.php">Cadastre-se</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>