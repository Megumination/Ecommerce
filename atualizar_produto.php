<?php
session_start();
include 'util.php';

// VERIFICAÇÃO DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['admin'] != true) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'login.php';</script>";
    exit;
}

// Verifica se os dados foram enviados via POST
if ($_POST) {
    $conn = conecta();

    // Pega os dados do formulário
    $id_produto = $_POST['id_produto'];
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'];
    $estoque = $_POST['estoque'];

    // SQL base para o update
    $sql = "UPDATE produto SET 
                nome = :nome, 
                valor_unitario = :valor, 
                descricao = :descricao, 
                qtde_estoque = :estoque 
            WHERE id_produto = :id";

    // Lida com o upload de uma nova imagem, se houver
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $nome_arquivo = $_FILES['imagem']['name'];
        $destino = "imagens/" . $nome_arquivo;
        
        // Se o upload for bem-sucedido, adiciona a imagem ao SQL
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            // Adiciona a atualização da imagem na query SQL
            $sql = "UPDATE produto SET 
                        nome = :nome, 
                        valor_unitario = :valor, 
                        descricao = :descricao, 
                        qtde_estoque = :estoque,
                        imagem = :imagem 
                    WHERE id_produto = :id";
        }
    }

    $update = $conn->prepare($sql);

    // Binds dos parâmetros
    $update->bindParam(':id', $id_produto);
    $update->bindParam(':nome', $nome);
    $update->bindParam(':valor', $valor);
    $update->bindParam(':descricao', $descricao);
    $update->bindParam(':estoque', $estoque);

    // Se uma nova imagem foi enviada, faz o bind dela também
    if (isset($nome_arquivo)) {
        $update->bindParam(':imagem', $nome_arquivo);
    }

    $update->execute();

    // Redireciona de volta para a lista de produtos
    header("Location: gerenciar_produtos.php");
    exit;

} else {
    // Se alguém tentar acessar o script diretamente, redireciona
    header("Location: gerenciar_produtos.php");
    exit;
}
?>