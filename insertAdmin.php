<?php
  if($_SESSION['admin'] != true)
  {
    header("Location: index.php");
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

      <form action = "" method = "post">
        <div class="input-group">
          <label for="nome">Nome</label>
          <input type="text"  name="nome" placeholder="Digite seu nome" required>
        </div>

        <div class="input-group">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" >
        </div>

        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password"  name= "senha" placeholder="Digite sua senha" required>
        </div>

        <button type="submit" class="btn-login">Entrar</button>
      </form>

      <div class="extra-links">
        <a href="#">Esqueci minha senha</a>
        <a href="#">Criar conta</a>
      </div>
    </div>
  </div>

</body>
   

<?php 
    if ( $_POST ) {
        
        include "util.php";
        
        $conn = conecta();

        $nome    = $_POST['nome'];
        $usuario = $_POST['usuario'];

        $senha   =  password_hash($_POST['senha'],PASSWORD_DEFAULT);
        $varSql = "insert into usuario (nome,email,senha,admin) values
                                (:nome,:usuario,:senha,true)";

        $insert = $conn->prepare($varSql);

        $insert->bindParam(":nome",$nome);
        $insert->bindParam(":usuario",$usuario);
        $insert->bindParam(":senha",$senha);

        $insert->execute();
    }
    
    
?>
 </html>

