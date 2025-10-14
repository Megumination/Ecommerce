<?php
session_start();
include 'util.php';

// VERIFICAÇÃO DE ADMIN: Só admins podem acessar
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['admin'] != true) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'login.php';</script>";
    exit;
}

// Verifica se um ID de produto foi passado pela URL
if (!isset($_GET['id'])) {
    header("Location: gerenciar_produtos.php");
    exit;
}

$id_produto = $_GET['id'];
$conn = conecta();

// Busca os dados do produto específico no banco
$sql = "SELECT * FROM produto WHERE id_produto = :id";
$select = $conn->prepare($sql);
$select->execute([':id' => $id_produto]);
$produto = $select->fetch(PDO::FETCH_ASSOC);

// Se não encontrou o produto, volta para a página de gerenciamento
if (!$produto) {
    header("Location: gerenciar_produtos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Editar Produto: <?php echo htmlspecialchars($produto['nome']); ?></h2>

        <form action="atualizar_produto.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto']; ?>">

            <div class="input-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            </div>

            <div class="input-group">
                <label for="valor">Valor (R$)</label>
                <input type="number" id="valor" name="valor" step="0.01" value="<?php echo htmlspecialchars($produto['valor_unitario']); ?>" required>
            </div>

            <div class="input-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="input-group">
                <label for="estoque">Estoque</label>
                <input type="number" id="estoque" name="estoque" value="<?php echo htmlspecialchars($produto['qtde_estoque']); ?>" required>
            </div>

            <div class="input-group">
                <label for="imagem">Alterar Imagem (opcional)</label>
                <input type="file" id="imagem" name="imagem">
                <p>Imagem atual:</p>
                <?php if (!empty($produto['imagem'])): ?>
                    <img src="imagens/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem atual" style="max-width: 150px; border-radius: 5px;">
                <?php else: ?>
                    <p>Nenhuma imagem cadastrada.</p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-login">Atualizar Produto</button>
        </form>
    </div>
</body>
</html>