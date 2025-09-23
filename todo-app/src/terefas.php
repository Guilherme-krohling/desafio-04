<!-- EU FAÇO IGUAL A TELA DE REGISTRO ??????????? DEIXANDO O PHP AQUI E CONSULTANDO depois -->

<?php
session_start();
//require 'connection.php';
include 'connection.php';

if (isset($_POST['create_tarefa'])){
    $titulo = mysqli_real_escape_string($conn, trim($_POST['tituloTarefa']));
    $descricao = mysqli_real_escape_string($conn, trim($_POST['descricaoTarefa']));
    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks (user_id, title, description) VALUES ('$userId', '$titulo', '$descricao')";
    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn) > 0){
        $_SESSION['mensagem'] = "Tarefa adicionada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao adicionar tarefa.";
    }

    header("Location: tarefas.php");
    exit;
}

?>