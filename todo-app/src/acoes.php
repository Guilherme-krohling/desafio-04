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
    mysqli_stmt_close($stmt);
}
 
//LOGIN
// if (isset($_POST['select_usuario'])) {
   
//     if (empty($_POST['emailLogin']) || empty($_POST['senhaLogin'])) {
//         $_SESSION['mensagem'] = "Preencha todos os campos.";
//         header('Location: login.php');
//         exit();
//     }
 
//     $email = mysqli_real_escape_string($conn, $_POST['emailLogin']);
//     $senha = mysqli_real_escape_string($conn, $_POST['senhaLogin']);
 
//     $sql = "SELECT id, name, email, password FROM users WHERE email = '{$email}' LIMIT 1";
//     $result = mysqli_query($conn, $sql);
 
//     if ($result && mysqli_num_rows($result) === 1) {
//         $row = mysqli_fetch_assoc($result);
 
//         if ($senha === $row['password']) {
//             $_SESSION['user_id'] = $row['id'];
//             $_SESSION['user_name'] = $row['name'];
//             $_SESSION['mensagem'] = "Login realizado com sucesso!";
//             header("location: index.php");
//             exit();
//         } else {
//             $_SESSION['mensagem'] = "Senha incorreta.";
//             header("Location: login.php");
//             exit();
//         }
//     } else {
//         $_SESSION['mensagem'] = "Usuário não encontrado.";
//         header("Location: login.php");
//         exit();
//     }
// }
?>