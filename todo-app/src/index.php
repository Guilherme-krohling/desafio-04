<?php
session_start();
require_once 'connection.php';
 
// Redireciona para login se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
 
// Adiciona nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $title = trim($_POST['titulo']);
    $desc = trim($_POST['descricao']);
    $userId = $_SESSION['user_id']; // Moved here to be defined before use
 
    if ($title !== '') {
        $sql = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        $sql->bind_param("iss", $userId, $title, $desc);
        $sql->execute();
        $sql->close();
    }
}
 
// Ordenação
$order = ($_GET['order'] ?? 'created_at') === 'status' ? 'status' : 'created_at';
 
// Lista tarefas
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=? ORDER BY $order DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Tarefas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="sair">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
 
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Adicionar Nova Tarefa</h2>
                        <form id="tarefaForm" method="POST" action="">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Tarefa</label>
                                <input type="text" class="form-control" name="titulo" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" name="descricao" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Adicionar Tarefa</button>
                        </form>
                    </div>
                </div>
 
                <div class="mt-5">
                    <h3 class="mb-4">Minhas Tarefas</h3>
                    <div id="listaTarefas">
                        <!-- As tarefas serão adicionadas aqui via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Modal para editar tarefa -->
    <div class="modal fade" id="editarTarefaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarTarefaForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editTitulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="editDescricao" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="salvarEdicao">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>