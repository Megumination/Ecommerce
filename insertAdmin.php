<?php 
  session_start();
  include "util.php";

  $conn = conecta();

  // Verifica se já existe algum admin cadastrado
  $check = $conn->query("SELECT COUNT(*) as total FROM usuario WHERE admin = true");
  $qtdAdmins = $check->fetch(PDO::FETCH_ASSOC)['total'];

  // Se já existir admin, só ele pode acessar
  if ($qtdAdmins < 0) {
      if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['admin'] != true) {
          header("Location: login.php");
          exit;
      }
  }
?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Criar Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  </head>
  <body class="login-body">

    <div class="login-container">
      <div class="login-card">
        <h2>Criar Administrador</h2>
        <?php if ($qtdAdmins == 0): ?>
          <p><strong>Atenção:</strong> Nenhum administrador existe ainda. Este será o primeiro admin do sistema.</p>
        <?php else: ?>
          <p>Cadastre um novo usuário admin</p>
        <?php endif; ?>

        <form action="" method="post">
          <div class="input-group">
            <label for="nome">Nome</label>
            <input type="text" name="nome" placeholder="Digite o nome" required>
          </div>

          <div class="input-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Digite o e-mail" required>
          </div>

          <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" name="senha" placeholder="Digite a senha" required>
          </div>

          <button type="submit" class="btn-login">Criar Admin</button>
        </form>
      </div>
    </div>
  </body>
</html>


<?php 
if ($_POST) {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nome, email, senha, admin) 
            VALUES (:nome, :email, :senha, true)";
    $insert = $conn->prepare($sql);

    $insert->bindParam(":nome", $nome);
    $insert->bindParam(":email", $email);
    $insert->bindParam(":senha", $senha);

    if ($insert->execute()) {
        echo "<p style='color:green; text-align:center;'>Administrador criado com sucesso!</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Erro ao criar administrador.</p>";
    }
}
?>