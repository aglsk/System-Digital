<?php
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

// Data limite (7 dias atrás)
$limit_date = date('Y-m-d H:i:s', strtotime('-7 days'));

// Preparar a consulta para excluir arquivos antigos
$stmt = $conn->prepare("DELETE FROM uploads WHERE upload_date < ?");
$stmt->bind_param("s", $limit_date);

// Executar a consulta
if ($stmt->execute()) {
    echo "Arquivos antigos foram excluídos com sucesso.";
} else {
    echo "Erro ao excluir arquivos antigos: " . $conn->error;
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
