<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: conta.php');
    exit;
}

if ($_POST) {
    include "util.php";

    $usuario = $_POST['usuario'] ?? '';
    $senha   = $_POST['senha'] ?? '';

    $conn = conecta();

    $select = $conn->prepare("SELECT nome, senha, admin 
                              FROM usuario 
                              WHERE email = :usuario");

    $select->bindParam(":usuario", $usuario);
    $select->execute();
    $linha = $select->fetch(PDO::FETCH_ASSOC);

    if ($linha && password_verify($senha, $linha['senha'])) {
        $_SESSION['logged_in']        = true;
        $_SESSION['statusConectado']  = true;
        $_SESSION['admin']            = $linha['admin'];
        $_SESSION['login']            = $linha['nome'];

        // Redireciona em 5 segundos
        echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
          <meta charset='UTF-8'>
          <meta http-equiv='refresh' content='5;url=index.php'>
          <title>Login realizado</title>
          <link rel='stylesheet' href='style.css'>
        </head>
        <body class='login-body'>
          <div class='login-container'>
            <div class='login-card'>
              <h2>Login efetuado com sucesso </h2>
              <p>Bem-vindo, {$_SESSION['login']}!</p>
              <p>Você será redirecionado para a página inicial em 5 segundos...</p>
              <a href='index.php'>Clique aqui se não for redirecionado</a>
            </div>
          </div>
        </body>
        </html>";
        exit;
    } else {
        $_SESSION['logged_in']        = false;
        $_SESSION['statusConectado']  = false;
        $_SESSION['admin']            = false;
        $_SESSION['login']            = "";

        echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
          <meta charset='UTF-8'>
          <meta http-equiv='refresh' content='5;url=login.php'>
          <title>Erro no login</title>
          <link rel='stylesheet' href='style.css'>
        </head>
        <body class='login-body'>
          <div class='login-container'>
            <div class='login-card'>
              <h2> Usuário ou senha incorretos</h2>
              <p>Tente novamente.</p>
              <p>Você será redirecionado para a página de login em 5 segundos...</p>
              <a href='login.php'>Clique aqui se não for redirecionado</a>
            </div>
          </div>
        </body>
        </html>";
        exit;
    }
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ponto & Vírgula | Login</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="login-body">

  <div class="login-container">
    <div class="login-card">
      <h2>Entrar</h2>
      <p>Acesse sua conta para continuar</p>

      <!-- Formulário corrigido -->
      <form method="POST" action="login.php">
        <div class="input-group">
          <label for="usuario">E-mail</label>
          <input type="email" id="usuario" name="usuario" placeholder="Digite seu e-mail" required>
        </div>

        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
        </div>

        <button type="submit" class="btn-login">Entrar</button>
      </form>

      <div class="extra-links">
        <a href="esqueci.php">Esqueci minha senha</a>
        <a href="conta.php">Criar conta</a>
      </div>
    </div>
  </div>

</body>
</html>