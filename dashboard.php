<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
  $_SESSION['error_msg'] = "Faça o login para continuar";
  header("Location: login.php");
  exit();
}

// Conexão com o banco de dados
$conn = mysqli_connect("localhost", "root", "", "filedatabase");

// Processa o upload do arquivo
if (isset($_POST['upload'])) {
  $user_id = $_SESSION['user_id'];
  $filename = mysqli_real_escape_string($conn, $_FILES['file']['name']);
  $filesize = $_FILES['file']['size'];
  $filedata = file_get_contents($_FILES['file']['tmp_name']);

  // Insere o arquivo no banco de dados
  $sql = "INSERT INTO files (user_id, filename, filesize, filedata) VALUES ('$user_id', '$filename', '$filesize', '$filedata')";
  if (mysqli_query($conn, $sql)) {
    $_SESSION['success_msg'] = "Arquivo enviado com sucesso!";
    header("Location: dashboard.php");
    exit();
  } else {
    $_SESSION['error_msg'] = "Erro ao enviar arquivo";
    header("Location: dashboard.php");
    exit();
  }
}
function format_filesize($filesize)
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');
  $i = 0;
  while ($filesize >= 1024 && $i < 4) {
    $filesize /= 1024;
    $i++;
  }
  return round($filesize, 2) . ' ' . $units[$i];
}


// Obtém a lista de arquivos do usuário
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM files WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);

// Verifica se há algum erro ou mensagem de sucesso para exibir
$error_msg = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : "";
$success_msg = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : "";

// Limpa as variáveis de sessão
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <h1 class="text-center">Dashboard</h1>
    <div class="row">
      <div class="col-md-6 mx-auto">
        <?php if ($error_msg): ?>
                                <div class="alert alert-danger"><?php echo $error_msg ?></div>
        <?php endif; ?>
        <?php if ($success_msg): ?>
                                <div class="alert alert-success"><?php echo $success_msg ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file">Selecione o arquivo para enviar</label>
            <input type="file" name="file" id="file" required accept="*">
          </div>
          <button type="submit" name="upload" class="btn btn-primary">Enviar</button>
        </form>
      </div>
    </div>
    <?php if (mysqli_num_rows($result) > 0): ?>
                            <h3 class="mt-5">Arquivos enviados</h3>
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>Arquivo</th>
                                  <th>Tamanho</th>
                                  <th>Ações</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                        <tr>
                                                          <td><?php echo $row['filename'] ?></td>
                                                          <td><?php echo format_filesize($row['filesize']) ?></td>
                                                          <td>
                                                            <a href="download.php?id=<?php echo $row['id'] ?>" class="btn btn-primary">Download</a>
                                                          </td>
                                                        </tr>
                                <?php endwhile; ?>
                              </tbody>
                            </table>
    <?php endif; ?>
  </div>
</body>
</html>

