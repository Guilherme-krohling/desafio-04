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
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Erro ao cadastrar.";
            }
        }
    }
}
?>
<!-- HTML simples com form -->
