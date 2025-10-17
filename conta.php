<?php
session_start();
// Usando require_once para garantir que o arquivo seja incluído apenas uma vez
require_once "util.php";

$conn = conecta();
$msgSucesso = "";
$msgErro = "";

// 🔹 Caso o usuário queira sair (logout)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// =========================================================================
// PARTE 1: LÓGICA PARA USUÁRIO JÁ LOGADO (ATUALIZAÇÃO DE CONTA)
// =========================================================================
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $email = $_SESSION['login'];

    // Busca os dados atuais do usuário para preencher o formulário
    $select = $conn->prepare("SELECT nome, email FROM usuario WHERE email = :email");
    $select->execute([':email' => $email]);
    $usuario = $select->fetch(PDO::FETCH_ASSOC);

    // 🔹 Se o formulário de atualização for enviado
    if ($_POST) {
        $novoNome = $_POST['nome'] ?? $usuario['nome'];
        $novaSenha = $_POST['senha'] ?? '';
        // MUDANÇA: Captura a confirmação da nova senha
        $confirmaSenha = $_POST['confirma_senha'] ?? '';

        $sql = "UPDATE usuario SET nome = :nome ";
        $params = [':nome' => $novoNome, ':email' => $email];

        // Só atualiza a senha se o campo não estiver vazio
        if ($novaSenha !== '') {
            // MUDANÇA: Verifica se a nova senha e a confirmação são iguais
            if ($novaSenha === $confirmaSenha) {
                $senhaCripto = password_hash($novaSenha, PASSWORD_DEFAULT);
                $sql .= ", senha = :senha "; // Adiciona a atualização de senha na query
                $params[':senha'] = $senhaCripto;
            } else {
                $msgErro = "As novas senhas não conferem!";
            }
        }

        // Se não houve erro de senha, executa a atualização
        if (empty($msgErro)) {
            $sql .= "WHERE email = :email";
            $update = $conn->prepare($sql);
            if ($update->execute($params)) {
                $msgSucesso = "Informações atualizadas com sucesso!";
                // Atualiza o nome do usuário na sessão também
                $_SESSION['login_nome'] = $novoNome; 
                // Atualiza os dados do usuário para exibir na página
                $usuario['nome'] = $novoNome;
            } else {
                $msgErro = "Erro ao atualizar informações.";
            }
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

        <?php if ($msgSucesso) echo "<p style='color:green; text-align:center;'>$msgSucesso</p>"; ?>
        <?php if ($msgErro) echo "<p style='color:red; text-align:center;'>$msgErro</p>"; ?>

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
            <div class="input-group">
                <label for="confirma_senha">Confirmar Nova Senha</label>
                <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Repita a nova senha">
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
    exit; // Termina o script aqui para não carregar a parte de criação de conta
}

// =========================================================================
// PARTE 2: LÓGICA PARA USUÁRIO NÃO LOGADO (CRIAÇÃO DE CONTA)
// =========================================================================
if ($_POST) {
    // MUDANÇA: Captura todos os campos do novo formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // MUDANÇA: Validação principal - as senhas devem ser iguais
    if ($senha !== $confirma_senha) {
        $msgErro = "As senhas não conferem. Tente novamente.";
    } else {
        // Se as senhas conferem, continua com a verificação no banco
        $select = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
        $select->execute([':email' => $email]);

        if ($select->fetch()) {
            $msgErro = "Este e-mail já está cadastrado. Por favor, faça login.";
        } else {
            // Se o e-mail não existe, insere o novo usuário
            $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);

            // MUDANÇA: Corrigido o INSERT para usar a variável $nome
            $insert = $conn->prepare("INSERT INTO usuario (nome, email, senha, admin) VALUES (:nome, :email, :senha, false)");
            $params = [
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senhaCripto
            ];

            if ($insert->execute($params)) {
                // Faz login automático após o cadastro
                $_SESSION['logged_in'] = true;
                $_SESSION['admin'] = false;
                $_SESSION['login'] = $email;
                $_SESSION['login_nome'] = $nome; // Guarda o nome na sessão também
                header("Location: index.php");
                exit;
            } else {
                $msgErro = "Erro ao cadastrar usuário. Tente novamente.";
            }
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

        <?php if ($msgErro) echo "<p style='color:red; text-align:center;'>$msgErro</p>"; ?>

        <form method="POST" action="conta.php">
            <div class="input-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
            </div>
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>
            <div class="input-group">
                <label for="confirma_senha">Confirmar Senha</label>
                <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Repita sua senha" required>
            </div>
            <button type="submit" class="btn-conta">Cadastrar</button>
        </form>
    </div>
</div>
</body>
</html>