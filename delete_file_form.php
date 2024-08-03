<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

// Configuração do banco de dados
$servername = "seu_servidor";$username = "seu_usuario";$password = "sua_senha";$dbname = "seu_banco_de_dados";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Excluir arquivo específico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['file_id']);

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM uploads WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<p>Arquivo excluído com sucesso.</p>";
        } else {
            echo "<p>Erro ao excluir o arquivo.</p>";
        }
        $stmt->close();
    }
}

// Obter todos os arquivos
$result = $conn->query("SELECT id, file_name FROM uploads");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Arquivos - System Digital</title>
    <link rel="icon" href="assets/favicon.png" type="image/png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d1117;
            color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background-color: #1e1e1e;
            border: none;
            border-radius: 10px;
        }
        .btn-danger {
            margin-right: 10px;
        }
        .navbar {
            margin-bottom: 30px;
        }
        .navbar-nav .nav-item {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">System Digital</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="index.php">Visualizar Arquivos</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="upload_form.php">Upload</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="admin_panel.php">Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Sair</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Excluir Arquivos Específicos</h2>
                <form action="delete_file_form.php" method="post">
                    <div class="form-group">
                        <label for="file_id">Selecione o arquivo para excluir:</label>
                        <select id="file_id" name="file_id" class="form-control" required>
                            <option value="">Escolha um arquivo</option>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <?php echo htmlspecialchars($row['file_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Excluir Arquivo</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
