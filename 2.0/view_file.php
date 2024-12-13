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

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // Obter o arquivo do banco de dados
    $stmt = $conn->prepare("SELECT file_name, file_type, file_data FROM uploads WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->bind_result($fileName, $fileType, $fileData);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "ID do arquivo não fornecido.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Arquivo - System Digital</title>
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
        .media-player {
            width: 100%;
            height: auto;
        }
        .btn-download {
            background-color: #28a745;
            border-color: #28a745;
            color: #ffffff;
        }
        .btn-download:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">System Digital</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-primary" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Visualização de Arquivo</h2>
                <?php if ($fileType): ?>
                    <?php if (strpos($fileType, 'image/') === 0): ?>
                        <img src="data:<?php echo htmlspecialchars($fileType); ?>;base64,<?php echo base64_encode($fileData); ?>" alt="Imagem" class="media-player">
                    <?php elseif (strpos($fileType, 'video/') === 0): ?>
                        <video controls class="media-player">
                            <source src="data:<?php echo htmlspecialchars($fileType); ?>;base64,<?php echo base64_encode($fileData); ?>" type="<?php echo htmlspecialchars($fileType); ?>">
                            Seu navegador não suporta o elemento de vídeo.
                        </video>
                    <?php elseif (strpos($fileType, 'audio/') === 0): ?>
                        <audio controls class="media-player">
                            <source src="data:<?php echo htmlspecialchars($fileType); ?>;base64,<?php echo base64_encode($fileData); ?>" type="<?php echo htmlspecialchars($fileType); ?>">
                            Seu navegador não suporta o elemento de áudio.
                        </audio>
                    <?php else: ?>
                        <p>Tipo de arquivo não suportado para visualização.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Arquivo não encontrado.</p>
                <?php endif; ?>
                <a href="download.php?id=<?php echo htmlspecialchars($fileId); ?>" class="btn btn-download mt-3">Download</a>
                <a href="view_all.php" class="btn btn-secondary mt-3">Voltar para a Lista de Arquivos</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>