<?php
session_start();
include 'util.php';

// VERIFICAÇÃO DE ADMIN: Se não for admin, expulsa da página
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['admin'] != true) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'login.php';</script>";
    exit;
}

// Conecta ao banco para buscar os produtos
$conn = conecta();
$sql = "SELECT * FROM produto ORDER BY nome";
$select = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos simples para a tabela de gerenciamento */
        .admin-container { padding: 40px; max-width: 1000px; margin: auto; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .admin-table th { background-color: #1D3557; color: white; }
        .admin-table img { max-width: 80px; }
        .admin-table a { color: #E63946; text-decoration: none; }
        .admin-table a:hover { text-decoration: underline; }
        .btn-adicionar {
            display: inline-block;
            background-color: #1D3557;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Gerenciamento de Produtos</h2>
        <p>Olá, <?php echo $_SESSION['login']; ?>! Você tem permissão para gerenciar os produtos.</p>
        <a href="adicionar_produto.php" class="btn-adicionar">Adicionar Novo Produto</a>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Estoque</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($linha = $select->fetch()): ?>
                <tr>
                    <td><img src="imagens/<?php echo htmlspecialchars($linha['imagem']); ?>" alt="<?php echo htmlspecialchars($linha['nome']); ?>"></td>
                    <td><?php echo htmlspecialchars($linha['nome']); ?></td>
                    <td>R$ <?php echo number_format($linha['valor_unitario'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($linha['qtde_estoque']); ?></td>
                    <td><a href="editar_produto.php?id=<?php echo $linha['id_produto']; ?>">Editar</a></td>
                    <td><a href="excluir_produto.php?id=<?php echo $linha['id_produto']; ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="index.php">Voltar para a Loja</a>
    </div>
</body>
</html>