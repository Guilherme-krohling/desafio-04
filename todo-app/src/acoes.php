<?php
session_start();
//require 'connection.php';
include 'connection.php';

//CADASTRO
if (isset($_POST['create_usuario'])){
    $name= mysqli_real_escape_string($conn, trim($_POST['nomeCadastro']));
    $email= mysqli_real_escape_string($conn, trim($_POST['emailCadastro']));
    //$senha= isset($_POST['senhaCadastro']) ? mysqli_real_escape_string($conn, password_hash(trim($_POST['senhaCadastro']), PASSWORD_DEFAULT)) : '';
    $senha= mysqli_real_escape_string($conn, trim($_POST['senhaCadastro']));

    $sql= "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$senha')";

    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn) > 0){
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar. Tente novamente.";
        header("Location: registro.php");
        exit;
    }
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