<?php
// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se foi enviado um ID de arquivo válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_msg'] = "ID de arquivo inválido";
    header("Location: dashboard.php");
    exit();
}

// Obtém o ID de usuário e de arquivo
$user_id = $_SESSION['user_id'];
$file_id = $_GET['id'];

// Conecta ao banco de dados
$conn = mysqli_connect("localhost", "root", "", "filedatabase");
if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Verifica se o arquivo pertence ao usuário
$sql = "SELECT * FROM files WHERE id='$file_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    $_SESSION['error_msg'] = "Arquivo não encontrado";
    header("Location: dashboard.php");
    exit();
}

// Obtém as informações do arquivo
$row = mysqli_fetch_assoc($result);
$filename = $row['filename'];
$filedata = $row['filedata'];
$filesize = $row['filesize'];

// Define os headers para o download
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Length: $filesize");

// Escreve os dados do arquivo na saída
echo $filedata;