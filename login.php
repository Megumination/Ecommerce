<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header('Location: conta.php');
  exit;
}

if ($_POST) {
  include "util.php";

  $usuario = $_POST['usuario'] ?? '';
  $senha = $_POST['senha'] ?? '';

  $conn = conecta();

  $select = $conn->prepare("SELECT nome, senha, admin 
                              FROM usuario 
                              WHERE email = :usuario");

  $select->bindParam(":usuario", $usuario);
  $select->execute();
  $linha = $select->fetch(PDO::FETCH_ASSOC);

  if ($linha && password_verify($senha, $linha['senha'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['statusConectado'] = true;
    $_SESSION['admin'] = $linha['admin'];
    $_SESSION['login'] = $linha['nome'];

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
              <h2>Login efetuado com sucesso</h2>
              <p>Bem-vindo, {$_SESSION['login']}!</p>
              <p>Você será redirecionado para a página inicial em 5 segundos...</p>
              <a href='index.php'>Clique aqui se não for redirecionado</a>
            </div>
          </div>
        </body>
        </html>";
    exit;
  } else {
    $_SESSION['logged_in'] = false;
    $_SESSION['statusConectado'] = false;
    $_SESSION['admin'] = false;
    $_SESSION['login'] = "";

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
              <h2>Usuário ou senha incorretos</h2>
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
  <link rel="icon" href="imagens/favicon.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
</head>

<body class="login-body">

  <header class="header">
    <div class="logo">
      <img src="imagens/logo.png" alt="Logotipo Ponto & Vírgula">
    </div>
    <nav class="menu">
      <a href="index.php">Início</a>
      <a href="promocoes.html">Promoções</a>
      <a href="parcerias.html">Parcerias</a>
      <a href="sobre.html">Sobre nós</a>
    </nav>

    <div class="icones">
      <a href="login.php"><img src="imagens/icone_login.png" alt="Login"></a>
      <a href="carrinho.php"><img src="imagens/carrinho.png" alt="Carrinho"></a>
      <?php
      // Se o usuário está logado E é um admin, mostra o link de gerenciamento
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        echo '<a href="gerenciar_produtos.php"><img src="imagens/engrenagem.png" alt="Gerenciar"></a>';
      }
      ?>

      <a href="https://www.instagram.com/pontovirgula.ltda/#" target="_blank">
        <img src="imagens/instagram.png" alt="Instagram">
      </a>
    </div>

  </header>

  <div class="login-container">
    <div class="login-card">

      <a href="index.php" class="close-btn">x</a>

      <h2>Entrar</h2>
      <p>Acesse sua conta para continuar</p>

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

  <!-- RODAPÉ -->
  <footer class="footer">
    <div class="footer-container">

      <!-- Sobre a empresa -->
      <div class="footer-section">
        <h3>Ponto & Vírgula</h3>
        <p>Organize com arte, viva com leveza!</p>
        <p>Em breve na Semana do Colégio CTI Bauru 2025, nos dias 21 a 24 de outubro.</p>
        <p>Horários: 8h às 12h e 19h às 22h</p>
      </div>

      <!-- Links úteis -->
      <div class="footer-section links-uteis">
        <h4>Links úteis</h4>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="sobre.html">Sobre nós</a></li>
          <li><a href="promocoes.html">Promoções</a></li>
          <li><a href="#contato">Contato</a></li>
        </ul>
      </div>

      <!-- Contato -->
      <div class="footer-section" id="contato">
        <h4>Contato</h4>
        <p>Email: pontoevirgula@gmail.com</p>
        <p>Telefone: +55 11 99999-9999</p>
        <p>Endereço: Avenida Nações Unidas, n° 58-50, Bairro Vargem Limpa, Bauru – SP, CEP
          17033-260</p>
      </div>

      <!-- Redes sociais -->
      <div class="footer-section">
        <h4>Siga-nos</h4>
        <div class="social-icons">
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" /></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram" /></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter" /></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn" /></a>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© 2025 Ponto & Vírgula. Todos os direitos reservados.</p>
    </div>
  </footer>

</body>

</html>