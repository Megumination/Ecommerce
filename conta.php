<?php
session_start(); // iniciar sessão antes de qualquer saída HTML
include "util.php";

$msgSucesso = "";
$msgErro = "";

if ($_POST) {
    $conn = conecta();

    $email = $_POST['usuario'];
    $senha = $_POST['senha'];
    $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica se o usuário já existe
    $select = $conn->prepare("SELECT * FROM usuario WHERE email = :email");
    $select->bindParam(":email", $email);
    $select->execute();
    $linha = $select->fetch();

    if ($linha) {
        // Se usuário existe, atualiza senha
        $update = $conn->prepare("UPDATE usuario SET senha = :senha WHERE email = :email");
        $update->bindParam(":senha", $senhaCripto);
        $update->bindParam(":email", $email);

        if ($update->execute()) {
            $msgSucesso = "Senha atualizada com sucesso!";
        } else {
            $msgErro = "Erro ao atualizar senha.";
        }
    } else {
        // Se usuário não existe, insere novo
        $insert = $conn->prepare("INSERT INTO usuario (nome, email, senha, admin) VALUES (:nome, :email, :senha, false)");
        $insert->bindParam(":nome", $email); // você pode trocar pelo nome real
        $insert->bindParam(":email", $email);
        $insert->bindParam(":senha", $senhaCripto);

        if ($insert->execute()) {
            $msgSucesso = "Usuário cadastrado com sucesso!";
        } else {
            $msgErro = "Erro ao cadastrar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Conta</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="conta-body">

<div class="conta-container">
    <div class="conta-card">
        <a href="login.php" class="close-btn">x</a>

        <h2>Minha Conta</h2>
        <p>Gerencie suas informações e pedidos</p>
        <h3>Detalhes da Conta</h3>

        <!-- Exibe mensagens -->
        <?php if ($msgSucesso) echo "<p style='color:green;'>$msgSucesso</p>"; ?>
        <?php if ($msgErro) echo "<p style='color:red;'>$msgErro</p>"; ?>

        <form method="POST" action="conta.php">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="usuario" placeholder="Digite seu e-mail" required>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-conta">Salvar Alterações</button>
        </form>
    </div>
</div>

</body>
</html>
