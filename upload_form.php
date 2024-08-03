<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

// Mensagem para o usuário
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);

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

// Verificar se o arquivo já foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fileUpload'])) {
    $fileName = $_FILES['fileUpload']['name'];
    $fileType = $_FILES['fileUpload']['type'];
    $fileData = file_get_contents($_FILES['fileUpload']['tmp_name']);

    // Checar se o arquivo já existe
    $stmt = $conn->prepare("SELECT id FROM uploads WHERE file_name = ? LIMIT 1");
    $stmt->bind_param("s", $fileName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Arquivo já foi enviado.";
        header("Location: upload_form.php");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivos</title>
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
        .preview-container {
            margin-top: 20px;
            position: relative;
        }
        .preview-container img,
        .preview-container video,
        .preview-container audio {
            max-width: 100%;
            height: auto;
        }
        .btn-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            color: #ff0000;
            display: none; /* Inicialmente oculto */
        }
        .btn-remove svg {
            width: 24px;
            height: 24px;
        }
        .file-info {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .file-info span {
            margin-right: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (max-width: 576px) {
            .btn-remove {
                top: 5px;
                right: 5px;
            }
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
                    <a class="btn btn-primary" href="admin_panel.php">Admin Panel</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Formulário de Upload</h2>
                <?php if ($message): ?>
                    <div class="alert alert-info">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <form action="upload_process.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileUpload">Escolha um arquivo:</label>
                        <input type="file" id="fileUpload" name="fileUpload" class="form-control-file" accept="image/*,video/*,audio/*" onchange="previewFile()" required>
                    </div>
                    <div class="preview-container" id="previewContainer">
                        <!-- Pré-visualização e botão de exclusão do arquivo -->
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const file = document.querySelector('#fileUpload').files[0];
            const previewContainer = document.querySelector('#previewContainer');
            previewContainer.innerHTML = '';
            const btnRemove = document.createElement('button');
            btnRemove.className = 'btn-remove';
            btnRemove.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
            btnRemove.onclick = removeFile;
            previewContainer.appendChild(btnRemove);

            if (file) {
                const fileType = file.type;
                const fileName = file.name;

                const fileInfo = document.createElement('div');
                fileInfo.className = 'file-info';
                const fileNameSpan = document.createElement('span');
                fileNameSpan.textContent = fileName;
                fileInfo.appendChild(fileNameSpan);
                previewContainer.appendChild(fileInfo);

                if (fileType.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.alt = 'Pré-visualização da imagem';
                    previewContainer.appendChild(img);
                } else if (fileType.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.controls = true;
                    video.src = URL.createObjectURL(file);
                    previewContainer.appendChild(video);
                } else if (fileType.startsWith('audio/')) {
                    const audio = document.createElement('audio');
                    audio.controls = true;
                    audio.src = URL.createObjectURL(file);
                    previewContainer.appendChild(audio);
                }

                btnRemove.style.display = 'block'; // Mostrar o botão de exclusão
            }
        }

        function removeFile() {
            document.querySelector('#fileUpload').value = '';
            document.querySelector('#previewContainer').innerHTML = '';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
