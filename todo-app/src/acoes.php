<?php
session_start();
//require 'connection.php';
include 'connection.php';
 
//CADASTRO
if (isset($_POST['create_usuario'])){
    $name= mysqli_real_escape_string($conn, trim($_POST['nomeCadastro']));
    $email= mysqli_real_escape_string($conn, trim($_POST['emailCadastro']));
    $senha= password_hash(trim($_POST['senhaCadastro']), PASSWORD_DEFAULT); // Hash da senha
 
    // Using prepared statement for security
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $senha);
    mysqli_stmt_execute($stmt);
 
    if(mysqli_stmt_affected_rows($stmt) > 0){
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar. Tente novamente.";
        header("Location: registro.php");
        exit;
    }
    //mysqli_stmt_close($stmt);
}
?>