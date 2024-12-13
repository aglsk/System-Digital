<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

// Configuração do banco de dados
$servername = "seu_servidor";$username = "seu_usuario";$password = "sua_senha";$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter registros de login
$result = $conn->query("SELECT username, login_time, logout_time FROM login_records ORDER BY login_time DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Login</title>
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
        .table {
            color: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">System Digital</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-primary" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="upload_form.php">Upload</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="admin_panel.php">Admin Panel</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Registros de Login</h2>
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Hora de Entrada</th>
                            <th>Hora de Saída</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['login_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['logout_time']) ?: 'Ainda logado'; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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