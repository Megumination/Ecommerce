<?php
session_start();
include 'util.php';

// VERIFICAÇÃO DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'login.php';</script>";
    exit;
}

if ($_POST) {
    $conn = conecta();
    
    // 1. Pega os dados do formulário
    $nome = $_POST['nome'];
    // CORREÇÃO: Usando os nomes corretos das colunas do seu banco
    $valor_unitario = $_POST['valor']; 
    $descricao = $_POST['descricao'];
    $qtde_estoque = $_POST['estoque'];

    try {
        // Inicia uma transação para garantir que tudo ocorra com sucesso
        $conn->beginTransaction();

        // 2. INSERE o produto no banco SEM o nome da imagem
        // A coluna 'imagem' ficará com o valor NULL ou seu valor padrão por enquanto
        $sql_insert = "INSERT INTO produto (nome, valor_unitario, descricao, qtde_estoque) 
                       VALUES (:nome, :valor, :descricao, :estoque)";
        
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->execute([
            ':nome' => $nome,
            ':valor' => $valor_unitario,
            ':descricao' => $descricao,
            ':estoque' => $qtde_estoque
        ]);

        // 3. PEGA O ID do produto que acabamos de inserir
        $id_produto = $conn->lastInsertId();

        // 4. Lida com o UPLOAD DA IMAGEM, se ela foi enviada
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            
            // Pega a extensão do arquivo original (ex: "jpg", "png")
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            
            // MONTA O NOVO NOME do arquivo usando o ID do produto
            // Exemplo: se o ID for 42 e a extensão for png, o nome será "42.png"
            $novo_nome_imagem = $id_produto . '.' . $extensao;

            // Define o caminho completo de destino
            $destino = "imagens/" . $novo_nome_imagem; 
            
            // Move o arquivo temporário para o destino final com o novo nome
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                
                // 5. ATUALIZA o registro no banco com o novo nome da imagem
                $sql_update = "UPDATE produto SET imagem = :imagem WHERE id_produto = :id";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->execute([
                    ':imagem' => $novo_nome_imagem,
                    ':id' => $id_produto
                ]);

            } else {
                // Se a imagem não puder ser movida, desfaz a transação
                throw new Exception("Erro ao mover o arquivo de imagem.");
            }
        }
        
        // Se tudo deu certo, confirma as operações no banco
        $conn->commit();

    } catch (Exception $e) {
        // Se qualquer passo falhar, desfaz tudo para não deixar dados inconsistentes
        $conn->rollBack();
        die("Erro ao salvar produto: " . $e->getMessage());
    }
    
    header("Location: gerenciar_produtos.php");
    exit;
}
?>