<?php
session_start();
include "util.php";

$conn = conecta();

$msgSucesso = "";
$msgErro = "";

// 🔹 Caso o usuário queira sair
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// 🔹 Caso o usuário esteja logado, buscamos seus dados
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $email = $_SESSION['login'];

    $select = $conn->prepare("SELECT nome, email FROM usuario WHERE email = :email");
    $select->bindParam(":email", $email);
    $select->execute();
    $usuario = $select->fetch(PDO::FETCH_ASSOC);

    // 🔹 Se estiver atualizando informações
    if ($_POST) {
        $novoNome = $_POST['nome'] ?? $usuario['nome'];
        $novaSenha = $_POST['senha'] ?? '';

        if ($novaSenha !== '') {
            $senhaCripto = password_hash($novaSenha, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE usuario SET nome = :nome, senha = :senha WHERE email = :email");
            $update->bindParam(":nome", $novoNome);
            $update->bindParam(":senha", $senhaCripto);
            $update->bindParam(":email", $email);
        } else {
            $update = $conn->prepare("UPDATE usuario SET nome = :nome WHERE email = :email");
            $update->bindParam(":nome", $novoNome);
            $update->bindParam(":email", $email);
        }

        if ($update->execute()) {
            $_SESSION['login'] = $novoNome;
            $msgSucesso = "Informações atualizadas com sucesso!";
        } else {
            $msgErro = "Erro ao atualizar informações.";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Minha Conta</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
</head>
<body class="conta-body">

<div class="conta-container">
    <div class="conta-card">
        <a href="index.php" class="close-btn">x</a>

        <h2>Minha Conta</h2>
        <p>Bem-vindo, <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>!</p>
        <h3>Editar informações</h3>

        <!-- Mensagens -->
        <?php if ($msgSucesso) echo "<p style='color:green;'>$msgSucesso</p>"; ?>
        <?php if ($msgErro) echo "<p style='color:red;'>$msgErro</p>"; ?>

        <form method="POST" action="conta.php">
            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
            </div>

            <div class="input-group">
                <label for="senha">Nova Senha (opcional)</label>
                <input type="password" id="senha" name="senha" placeholder="Deixe em branco para não alterar">
            </div>

            <button type="submit" class="btn-conta">Salvar Alterações</button>
        </form>

        <div style="margin-top: 20px;">
            <a href="conta.php?logout=true" class="btn-conta" style="background-color: #d9534f;">Sair</a>
        </div>
    </div>
</div>

</body>
</html>
<?php
    exit;
}

// 🔹 Se o usuário não estiver logado, exibe a tela de criação de conta
if ($_POST) {
    $email = $_POST['usuario'];
    $senha = $_POST['senha'];
    $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica se o usuário já existe
    $select = $conn->prepare("SELECT * FROM usuario WHERE email = :email");
    $select->bindParam(":email", $email);
    $select->execute();
    $linha = $select->fetch(PDO::FETCH_ASSOC);

    if ($linha) {
        $msgErro = "Usuário já cadastrado. Faça login ou use outro e-mail.";
    } else {
        // Cria o usuário e faz login automático
        $insert = $conn->prepare("INSERT INTO usuario (nome, email, senha, admin) VALUES (:nome, :email, :senha, false)");
        $insert->bindParam(":nome", $email);
        $insert->bindParam(":email", $email);
        $insert->bindParam(":senha", $senhaCripto);

        if ($insert->execute()) {
            $_SESSION['logged_in'] = true;
            $_SESSION['statusConectado'] = true;
            $_SESSION['admin'] = false;
            $_SESSION['login'] = $email;
            header("Location: index.php");
            exit;
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
    <title>Ponto & Vírgula | Criar Conta</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="conta-body">

<div class="conta-container">
    <div class="conta-card">
        <a href="login.php" class="close-btn">x</a>

        <h2>Criar Conta</h2>
        <p>Cadastre-se para começar</p>

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

            <button type="submit" class="btn-conta">Cadastrar</button>
        </form>
    </div>
</div>

</body>
</html>