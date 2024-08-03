<?php
session_start();

if (isset($_SESSION['username'])) {
    // Configuração do banco de dados
    $servername = "seu_servidor";
    $username = "seu_usuario";
    $password = "sua_senha";
    $dbname = "seu_banco_de_dados";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Registrar hora de saída
    $logout_time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("UPDATE login_records SET logout_time = ? WHERE username = ? AND logout_time IS NULL");
    $stmt->bind_param("ss", $logout_time, $_SESSION['username']);
    $stmt->execute();
    $stmt->close();

    $conn->close();
}

session_destroy();
header("Location: login.php");
exit();
