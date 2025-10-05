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

      <form>
        <div class="input-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" placeholder="Digite seu e-mail" required>
        </div>

        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" placeholder="Digite sua senha" required>
        </div>

        <button type="submit" class="btn-login">Entrar</button>
      </form>

      <div class="extra-links">
        <a href="#">Esqueci minha senha</a>
        <a href="conta.php">Criar conta</a>
      </div>
    </div>
  </div>

</body>

    <?php
         
         if ( $_POST ) {
            include "util.php";
            session_start();

            $usuario = $_POST['usuario'];
            $conn = conecta();

			$select = $conn->prepare("select nome,senha,admin 
                                      from usuario 
                                      where email=:usuario");

            $select->bindParam(":usuario",$usuario);


            /*
             OBSERVACAO, ao adotarmos criptografia na senha, 
             lembre que insertUsuario.php precisara passar por uma melhoria:
             (...)
              $senhaCripto = password_hash($senha,PASSWORD_DEFAULT);
              $insert->bindParams(":senha",$senhaCripto);
             (...)
            */

            $senha   = $_POST['senha'];

            $select->execute();
            $linha = $select->fetch();
            
            if ( password_verify($senha,$linha['senha']) ) {
                $_SESSION['statusConectado'] = true;
                $_SESSION['admin'] = $linha['admin'];
                $_SESSION['login'] = $linha['nome'];
            } else {
                $_SESSION['statusConectado'] = false;
                $_SESSION['admin'] = false;
                $_SESSION['login'] = "";
            }
            
            header("location: index.php");            
                        
         }       
    ?>
</html>
