<?php
session_start();

// VERIFICAÇÃO DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['admin'] != true) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Adicionar Novo Produto</h2>
        
        <form action="salvar_produto.php" method="post" enctype="multipart/form-data">

            <div class="input-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="input-group">
                <label for="valor">Valor (R$)</label>
                <input type="number" id="valor" name="valor" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao"></textarea>
            </div>

            <div class="input-group">
                <label for="estoque">Estoque</label>
                <input type="number" id="estoque" name="estoque" required>
            </div>
            
            <div class="input-group">
                <label for="imagem">Imagem do Produto</label>
                <input type="file" id="imagem" name="imagem">
            </div>

            <button type="submit" class="btn-login">Salvar Produto</button>
        </form>
    </div>
</body>
</html>