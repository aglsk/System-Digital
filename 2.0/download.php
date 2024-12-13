<?php
// Iniciar a sessão
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

    if ($fileName && $fileType && $fileData) {
        // Enviar os cabeçalhos apropriados para o download
        header("Content-Type: $fileType");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        echo $fileData;
    } else {
        echo "Arquivo não encontrado.";
    }
}

$conn->close();
?>