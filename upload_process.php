<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: login.php");
    exit();
}

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

// Processar o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK) {
    $fileName = $_FILES['fileUpload']['name'];
    $fileType = $_FILES['fileUpload']['type'];
    $fileData = file_get_contents($_FILES['fileUpload']['tmp_name']);

    // Preparar e executar a inserção
    $stmt = $conn->prepare("INSERT INTO uploads (file_name, file_type, file_data) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fileName, $fileType, $fileData);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Arquivo carregado com sucesso!";
    } else {
        $_SESSION['message'] = "Erro ao carregar o arquivo: " . $conn->error;
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "Nenhum arquivo enviado ou houve um erro no envio.";
}

$conn->close();

// Redirecionar para o formulário de upload
header("Location: upload_form.php");
exit();
?>