<?php
session_start();
require_once 'connection.php';

// Redireciona para login se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Adiciona nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $userId = $_SESSION['user_id'];

    if ($title !== '') {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $title, $desc);
        $stmt->execute();
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>

    <h2 class="mt-4">Adicionar Tarefa</h2>
    <form method="post">
        <input type="text" name="title" placeholder="Título" required class="form-control mb-2">
        <textarea name="description" placeholder="Descrição" class="form-control mb-2"></textarea>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>

    <h2 class="mt-4">Minhas Tarefas</h2>
    <a href="?order=created_at">Ordenar por Data</a> | 
    <a href="?order=status">Ordenar por Status</a>
    <ul class="list-group mt-2">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                - <?php echo htmlspecialchars($row['description']); ?>
                - <em><?php echo $row['status']; ?></em>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
