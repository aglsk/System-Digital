<?php
session_start();

// Configuração do banco de dados
$servername = "seu_servidor";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter todos os arquivos
$result = $conn->query("SELECT id, file_name, file_type, file_data FROM uploads");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Digital</title>
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
        .btn-view {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
        }
        .btn-view:hover {
            background-color: #0056b3;
            border-color: #004085;
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
        .navbar {
            margin-bottom: 30px;
        }
        .navbar-nav .nav-item {
            margin-left: 10px;
            margin-right: 10px;
        }
        .modal-content {
            background-color: #1e1e1e;
            color: #f8f9fa;
        }
        .modal-body img,
        .modal-body video,
        .modal-body audio {
            width: 100%;
            height: auto;
        }
        .modal-body p {
            font-size: 14px;
        }
        .table td,
        .table th {
            vertical-align: middle;
        }
        .truncate {
            max-width: 150px; /* Limite de 14 caracteres (aproximadamente) */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @media (max-width: 576px) {
            .btn-view {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="upload_form.php">System Digital</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']): ?>
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
                <h2 class="text-center mb-4">Visualização de Todos os Arquivos</h2>
                <table class="table table-dark table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Conteúdo</th>
                            <th>Tipo de Arquivo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="truncate"><?php echo htmlspecialchars($row['file_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['file_type']); ?></td>
                                <td>
                                    <a href="view_file.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-view d-none d-md-inline">Visualizar</a>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#previewModal<?php echo htmlspecialchars($row['id']); ?>">
                                        Pré-visualizar
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de Pré-visualização -->
                            <div class="modal fade" id="previewModal<?php echo htmlspecialchars($row['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Pré-visualização: <?php echo htmlspecialchars($row['file_name']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            $fileType = htmlspecialchars($row['file_type']);
                                            $fileData = base64_encode($row['file_data']);
                                            if (strpos($fileType, 'image/') === 0): ?>
                                                <img src="data:<?php echo $fileType; ?>;base64,<?php echo $fileData; ?>" alt="Preview">
                                            <?php elseif (strpos($fileType, 'video/') === 0): ?>
                                                <video controls>
                                                    <source src="data:<?php echo $fileType; ?>;base64,<?php echo $fileData; ?>" type="<?php echo $fileType; ?>">
                                                    Seu navegador não suporta o elemento de vídeo.
                                                </video>
                                            <?php elseif (strpos($fileType, 'audio/') === 0): ?>
                                                <audio controls>
                                                    <source src="data:<?php echo $fileType; ?>;base64,<?php echo $fileData; ?>" type="<?php echo $fileType; ?>">
                                                    Seu navegador não suporta o elemento de áudio.
                                                </audio>
                                            <?php else: ?>
                                                <p>Preview não disponível.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="download.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-download">Download</a>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
