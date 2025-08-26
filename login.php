<?php
// login.php 

require_once __DIR__ . '/cabecalho.php'; // Header do site

if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuarios = [
    ['email' => 'admin@loja.com', 'senha' => '123456', 'nome' => 'Admin'],
    ['email' => 'cliente@loja.com', 'senha' => 'cliente', 'nome' => 'Cliente Demo'],
];

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    $encontrado = null;
    foreach ($usuarios as $u) {
        if (strcasecmp($u['email'], $email) === 0 && $u['senha'] === $senha) {
            $encontrado = $u;
            break;
        }
    }

    if ($encontrado) {
        $_SESSION['usuario'] = [
            'email' => $encontrado['email'],
            'nome' => $encontrado['nome']
        ];
        header('Location: index.php');
        exit;
    } else {
        $erro = 'E-mail ou senha inválidos.';
    }
}
?>

<main class="container-login">
    <section class="card-login">
        <h2 class="titulo">Acesse sua conta</h2>

        <?php if ($erro): ?>
            <div class="erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php" autocomplete="on" novalidate>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="sua senha" required>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Entrar</button>
                <a class="btn btn-outline" href="index.php">Voltar para Home</a>
            </div>
        </form>

        <p class="muted">Use <strong>admin@loja.com</strong> / <strong>123456</strong> ou
            <strong>cliente@loja.com</strong> / <strong>cliente</strong> para testar.
        </p>
    </section>
</main>

<?php require_once __DIR__ . '/rodape.php'; // Footer do site ?>