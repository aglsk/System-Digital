<?php
session_start();

// Verificar se o usuário já está autenticado
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    header("Location: index.php");
    exit();
}

// Configuração do banco de dados
$servername = "seu_servidor";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar credenciais
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($password_hash);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $password_hash)) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;

        // Registrar hora de entrada
        $login_time = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO login_records (username, login_time) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $login_time);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit();
    } else {
        $error_message = "Usuário ou senha inválidos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - System Digital</title>
    <link rel="icon" href="assets/favicon.png" type="image/png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d1117;
            color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 100px;
        }
        .card {
            background-color: #1e1e1e;
            border: none;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .form-group svg {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4">Login</h2>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <form action="login.php" method="post">
                    <div class="form-group position-relative">
                        <label for="username">Nome de Usuário</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group position-relative">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <svg id="show-hide-password" width="24" height="24" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3a5.978 5.978 0 0 0-5.265 3.162C2.807 6.94 2 7.936 2 8s.807 1.061 1.735 1.838A5.978 5.978 0 0 0 8 13a5.978 5.978 0 0 0 5.265-3.162C13.193 9.061 14 8.065 14 8s-.807-1.061-1.735-1.838A5.978 5.978 0 0 0 8 3zm0 10a4.978 4.978 0 0 1-4.358-2.356A6.007 6.007 0 0 1 4 8a6.007 6.007 0 0 1 .642-2.644A4.978 4.978 0 0 1 8 3a4.978 4.978 0 0 1 4.358 2.356A6.007 6.007 0 0 1 12 8a6.007 6.007 0 0 1-.642 2.644A4.978 4.978 0 0 1 8 13zM8 4a4 4 0 0 0-3.873 3.02A5.978 5.978 0 0 1 4 8a5.978 5.978 0 0 1 .127.98A4 4 0 0 0 8 12a4 4 0 0 0 3.873-3.02A5.978 5.978 0 0 1 12 8a5.978 5.978 0 0 1-.127-.98A4 4 0 0 0 8 4z"/>
                        </svg>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const passwordField = document.getElementById('password');
        const showHidePasswordIcon = document.getElementById('show-hide-password');

        showHidePasswordIcon.addEventListener('click', () => {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                showHidePasswordIcon.setAttribute('class', 'bi bi-eye-slash');
            } else {
                passwordField.type = 'password';
                showHidePasswordIcon.setAttribute('class', 'bi bi-eye');
            }
        });
    </script>
</body>
</html>
