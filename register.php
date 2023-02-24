<?php
session_start();

// Conexão com o banco de dados
$conn = mysqli_connect("localhost", "root", "", "filedatabase");

if (isset($_POST['register'])) {
    // Recebendo dados do formulário de registro
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Verifica se as senhas conferem
    if ($password !== $confirm_password) {
        header("Location: index.php");
        exit();
    }

    // Verifica se o nome de usuário já está em uso
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        header("Location: index.php");
        exit();
    }

    // Hashing da senha para armazenamento seguro no banco de dados
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insere o novo usuário no banco de dados
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
    if (mysqli_query($conn, $sql)) {
        header("Location: formlogin.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
}
?>