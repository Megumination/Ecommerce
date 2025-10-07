<?php
include "util.php";
include "emails.php";

$msgSucesso = "";
$msgErro = "";

if ($_POST) {   
    $conn = conecta();
    $email = $_POST['email'];

    $select = $conn->prepare("SELECT nome, senha FROM usuario WHERE email = :email");
    $select->bindParam(':email', $email);
    $select->execute();
    $linha = $select->fetch();

    if ($linha) {
        $token = $linha['senha']; 
        $nome = $linha['nome'];
        $seusite = "eq2.ini2b"; // altere para o nome do seu site real

        $html = "<h4>Redefinir sua senha</h4><br>
                <b>Oi $nome</b>, <br>
                Clique no link para redefinir sua senha:<br>
                http://$seusite.projetoscti.com.br/redefinir.php?token=$token";

        $_SESSION["email"] = $email;

        if (EnviaEmail($email, '* Recupere a sua senha !! *', $html)) {
            $msgSucesso = "E-mail enviado com sucesso! Verifique sua caixa de entrada ou spam.";
        } else {
            $msgErro = "Erro ao enviar e-mail. Tente novamente mais tarde.";
        }
    } else {
        $msgErro = "E-mail não cadastrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Recuperar Senha</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="conta-body">

<div class="conta-container">
    <div class="conta-card">
        <a href="login.php" class="close-btn">x</a>

        <h2>Recuperar Senha</h2>
        <p>Digite o e-mail cadastrado para receber o link de redefinição.</p>

        <!-- Mensagens -->
        <?php if ($msgSucesso) echo "<p style='color:green;'>$msgSucesso</p>"; ?>
        <?php if ($msgErro) echo "<p style='color:red;'>$msgErro</p>"; ?>

        <form method="POST" action="esqueci.php">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>

            <button type="submit" class="btn-conta">Enviar</button>
        </form>

        <div style="margin-top: 20px;">
            <a href="login.php" class="btn-conta" style="background-color: #6c757d;">Voltar</a>
        </div>
    </div>
</div>

</body>
</html>