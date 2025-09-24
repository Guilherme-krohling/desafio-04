<?php
session_start();
require 'connection.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
 
$userId = $_SESSION['user_id'];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['titulo']) && isset($_POST['descricao'])) {
        $title = trim($_POST['titulo']);
        $desc = trim($_POST['descricao']);
 
        if (!empty($title)) {
            $sql = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
            $sql->bind_param("iss", $userId, $title, $desc);
            $sql->execute();
            $sql->close();
            $_SESSION['mensagem'] = "Tarefa adicionada com sucesso!";
            header("Location: index.php");
            exit;
        }
    }
}
 
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $taskId = $_GET['id'] ?? null;
 
    if ($taskId) {
        if ($action === 'delete') {
            $sql = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $sql->bind_param("ii", $taskId, $userId);
            $sql->execute();
            $sql->close();
            $_SESSION['mensagem'] = "Tarefa excluída com sucesso!";
        } elseif ($action === 'toggle_status') {
            $currentStatus = $_GET['status'] ?? 'pendente';
            $newStatus = ($currentStatus === 'pendente') ? 'concluida' : 'pendente';
 
            $sql = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
            $sql->bind_param("sii", $newStatus, $taskId, $userId);
            $sql->execute();
            $sql->close();
            $_SESSION['mensagem'] = "Status da tarefa atualizado com sucesso!";
        }
    }
    header("Location: index.php");
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $editTitle = trim($_POST['edit_titulo']);
    $editDesc = trim($_POST['edit_descricao']);
 
    if (!empty($editTitle)) {
        $sql = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
        $sql->bind_param("ssii", $editTitle, $editDesc, $editId, $userId);
        $sql->execute();
        $sql->close();
        $_SESSION['mensagem'] = "Tarefa atualizada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Título da tarefa não pode ser vazio!";
    }
    header("Location: index.php");
    exit;
}
 
$order = ($_GET['order'] ?? 'created_at') === 'status' ? 'status' : 'created_at';
 
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
                <?php include 'mensagem.php'; ?>
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Adicionar Nova Tarefa</h2>
                        <form method="POST" action="index.php">
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
                    <h3 class="mb-4 d-flex justify-content-between align-items-center">
                        Minhas Tarefas
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Ordernar por
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="?order=created_at">Data de Criação</a></li>
                                <li><a class="dropdown-item" href="?order=status">Status</a></li>
                            </ul>
                        </div>
                    </h3>
                    <div id="listaTarefas">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="card mb-3 tarefa-item <?php echo ($row['status'] === 'concluida') ? 'tarefa-concluida' : 'tarefa-pendente'; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title tarefa-titulo mb-2"><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p class="card-text tarefa-descricao"><?php echo htmlspecialchars($row['description']); ?></p>
                                        <span class="badge <?php echo ($row['status'] === 'concluida') ? 'bg-success' : 'bg-secondary'; ?> mb-2"><?php echo ucfirst($row['status']); ?></span>
                                        <small class="text-muted d-block mb-2">Criada em: <?php echo date('d/m/Y H:i:s', strtotime($row['created_at'])); ?></small>
                                        <div class="tarefa-botoes">
                                            <button class="btn btn-sm btn-outline-primary editar-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editarTarefaModal" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-titulo="<?php echo htmlspecialchars($row['title']); ?>" 
                                                data-descricao="<?php echo htmlspecialchars($row['description']); ?>">
                                                <i class="bi bi-pencil me-1"></i> Editar
                                            </button>
                                            <a href="?action=toggle_status&id=<?php echo $row['id']; ?>&status=<?php echo $row['status']; ?>" class="btn btn-sm <?php echo ($row['status'] === 'concluida') ? 'btn-warning' : 'btn-success'; ?>">
                                                <i class="bi <?php echo ($row['status'] === 'concluida') ? 'bi-arrow-counterclockwise' : 'bi-check-circle'; ?> me-1"></i>
                                                <?php echo ($row['status'] === 'concluida') ? 'Voltar para Pendente' : 'Marcar como Concluída'; ?>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                                <i class="bi bi-trash me-1"></i> Excluir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                Nenhuma tarefa cadastrada. Adicione uma nova tarefa acima.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="modal fade" id="editarTarefaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarTarefaForm" method="POST" action="index.php">
                        <input type="hidden" name="edit_id" id="editId">
                        <div class="mb-3">
                            <label for="editTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editTitulo" name="edit_titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="editDescricao" name="edit_descricao" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="salvarEdicao">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.editar-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const titulo = this.getAttribute('data-titulo');
                const descricao = this.getAttribute('data-descricao');
 
                document.getElementById('editId').value = id;
                document.getElementById('editTitulo').value = titulo;
                document.getElementById('editDescricao').value = descricao;
            });
        });
 
        const btnSair = document.getElementById('sair');
        if (btnSair) {
            btnSair.addEventListener('click', function() {
                window.location.href = 'logout.php';
            });
        }
    });
    </script>

    <a href="esterEgg.php" style="position: fixed; bottom: 10px; right: 10px; z-index: 1000;">
    <button class="btn btn-sm btn-link text-decoration-none">Não clique aqui</button>
</a></a></a>
</body>
</html>