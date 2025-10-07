<?php
session_start();
include "util.php";

$msgSucesso = "";
$msgErro = "";

// Se o formulário foi enviado
if ($_POST) {
    $conn = conecta();

    $senha1 = $_POST['senha1'];
    $senha2 = $_POST['senha2'];
    $token = $_GET['token'] ?? '';
    $email = $_SESSION["email"] ?? '';

    if (empty($email)) {
        $msgErro = "Sessão expirada. Volte e solicite a redefinição novamente.";
    } else {
        // Busca a senha atual do banco
        $select = $conn->prepare("SELECT senha FROM usuario WHERE email = :email");
        $select->bindParam(":email", $email);
        $select->execute();
        $linha = $select->fetch(PDO::FETCH_ASSOC);

        if ($linha) {
            $senhaAtual = $linha["senha"];

            // Verifica se o token corresponde à senha antiga
            if ($senhaAtual === $token) {
                if ($senha1 === $senha2) {
                    if (strlen($senha1) >= 6) {
                        $novaSenha = password_hash($senha1, PASSWORD_DEFAULT);
                        $update = $conn->prepare("UPDATE usuario SET senha = :senha WHERE email = :email");
                        $update->bindParam(":senha", $novaSenha);
                        $update->bindParam(":email", $email);

                        if ($update->execute()) {
                            $msgSucesso = "Senha alterada com sucesso!";
                        } else {
                            $msgErro = "Erro ao atualizar a senha.";
                        }
                    } else {
                        $msgErro = "A senha deve ter pelo menos 6 caracteres.";
                    }
                } else {
                    $msgErro = "As senhas não coincidem.";
                }
            } else {
                $msgErro = "Token inválido. O link pode ter expirado.";
            }
        } else {
            $msgErro = "Usuário não encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Redefinir Senha</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="conta-body">

<div class="conta-container">
    <div class="conta-card">
        <a href="login.php" class="close-btn">x</a>

        <h2>Redefinir Senha</h2>
        <p>Digite sua nova senha para acessar sua conta novamente.</p>

        <!-- Mensagens -->
        <?php if ($msgSucesso) echo "<p style='color:green;'>$msgSucesso</p>"; ?>
        <?php if ($msgErro) echo "<p style='color:red;'>$msgErro</p>"; ?>

        <?php if (!$msgSucesso): ?>
        <form method="POST" action="redefinir.php?token=<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
            <div class="input-group">
                <label for="senha1">Nova senha</label>
                <input type="password" id="senha1" name="senha1" maxlength="6" placeholder="Digite nova senha" required>
            </div>

            <div class="input-group">
                <label for="senha2">Confirmar senha</label>
                <input type="password" id="senha2" name="senha2" maxlength="6" placeholder="Repita a senha" required>
            </div>

            <button type="submit" class="btn-conta">Alterar</button>
        </form>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="login.php" class="btn-conta" style="background-color: #6c757d;">Voltar</a>
        </div>
    </div>
</div>

</body>
</html>