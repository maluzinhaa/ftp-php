<?php
session_start();

// Conexão com o banco de dados
$conn = mysqli_connect("localhost", "root", "", "filedatabase");

if (isset($_POST['login'])) {
    // Recebendo dados do formulário de login
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Verifica se o usuário existe no banco de dados
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Obtém o hash da senha do usuário
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['password'];

        // Verifica se a senha informada está correta
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php");
            exit();
        }
    } else {
        header("Location: login.php");
        exit();
    }
}
?>
