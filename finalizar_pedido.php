<?php
session_start();
include 'util.php'; // Suas funções de conexão com o banco

// 1. Recebe os dados do carrinho enviados pelo formulário
$carrinho_json = $_POST['carrinho_json'] ?? '[]';
$carrinho = json_decode($carrinho_json, true);

// Se o carrinho estiver vazio, redireciona de volta
if (empty($carrinho)) {
    header('Location: carrinho.php');
    exit;
}

// 2. Conecta ao banco de dados
$conn = conecta();

// 3. Determina o identificador: ID do usuário (se logado) ou ID da sessão
$id_usuario = $_SESSION['id_usuario'] ?? null;
$session_id = session_id();

// Inicia uma transação para garantir a integridade dos dados
$conn->beginTransaction();

try {
    // 4. Lógica: Verifica se já existe um pedido 'reservado' para este usuário/sessão
    $id_compra = null;
    
    if ($id_usuario) {
        // Usuário logado: procura um pedido com status 'reservado'
        // MUDANÇA: 'carrinho' -> 'reservado'
        $sql_find_cart = "SELECT id_compra FROM compra WHERE fk_usuario = :id_usuario AND status = 'reservado'";
        $stmt = $conn->prepare($sql_find_cart);
        $stmt->execute([':id_usuario' => $id_usuario]);
        $id_compra = $stmt->fetchColumn();
    } else {
        // Usuário não logado: procura um pedido com status 'reservado' para esta sessão
        // MUDANÇA: 'carrinho' -> 'reservado'
        $sql_find_cart = "SELECT id_compra FROM compra WHERE sessao = :session_id AND status = 'reservado'";
        $stmt = $conn->prepare($sql_find_cart);
        $stmt->execute([':session_id' => $session_id]);
        $id_compra = $stmt->fetchColumn();
    }

    // 5. Se não houver um pedido 'reservado', cria um novo
    if (!$id_compra) {
        // MUDANÇA: O status inicial agora é 'reservado'
        $sql_insert_compra = "INSERT INTO compra (fk_usuario, sessao, data, status) VALUES (:fk_usuario, :sessao, :data, 'reservado')";
        $stmt = $conn->prepare($sql_insert_compra);
        $stmt->execute([
            ':fk_usuario' => $id_usuario,
            ':sessao' => $session_id,
            ':data' => date('Y-m-d H:i:s')
        ]);
        $id_compra = $conn->lastInsertId();
    }

    // 6. Limpa os itens antigos deste pedido para sincronizar com o carrinho atual
    $sql_delete_items = "DELETE FROM compra_produto WHERE fk_compra = :fk_compra";
    $stmt = $conn->prepare($sql_delete_items);
    $stmt->execute([':fk_compra' => $id_compra]);

    $valor_total_compra = 0;

    // 7. Itera sobre os produtos do carrinho e insere na tabela compra_produto
    foreach ($carrinho as $produto) {
        $nome_produto = $produto['nome'];
        $quantidade = $produto['quantidade'];
        $preco_texto = str_replace(['R$', ' '], '', $produto['preco']);
        $preco_texto = str_replace(',', '.', $preco_texto);
        $preco_unitario = (float)$preco_texto;

        $sql_get_id = "SELECT id_produto FROM produto WHERE nome = :nome";
        $stmt_get_id = $conn->prepare($sql_get_id);
        $stmt_get_id->execute([':nome' => $nome_produto]);
        $id_produto = $stmt_get_id->fetchColumn();

        if ($id_produto) {
            $sql_insert_produto = "INSERT INTO compra_produto (fk_compra, fk_produto, quantidade, valor_unitario) VALUES (:fk_compra, :fk_produto, :quantidade, :valor_unitario)";
            $stmt_produto = $conn->prepare($sql_insert_produto);
            $stmt_produto->execute([
                ':fk_compra' => $id_compra,
                ':fk_produto' => $id_produto,
                ':quantidade' => $quantidade,
                ':valor_unitario' => $preco_unitario
            ]);
            $valor_total_compra += $preco_unitario * $quantidade;
        }
    }

    // 8. Atualiza o valor total da compra. O status continua 'reservado'.
    // MUDANÇA: Removemos a alteração de status, pois ele já é 'reservado'.
    $sql_update_compra = "UPDATE compra SET valor_total_compra = :valor_total WHERE id_compra = :id_compra";
    $stmt_update = $conn->prepare($sql_update_compra);
    $stmt_update->execute([
        ':valor_total' => $valor_total_compra,
        ':id_compra' => $id_compra
    ]);

    // Se tudo deu certo, confirma as alterações no banco
    $conn->commit();

    // 9. Limpa o localStorage e redireciona para uma página de sucesso
    echo "<script>
        localStorage.removeItem('carrinho');
        alert('Pedido reservado com sucesso! Prossiga para o pagamento.');
        window.location.href = 'index.php';
    </script>";

} catch (Exception $e) {
    // Se algo deu errado, desfaz todas as alterações
    $conn->rollBack();
    die("Erro ao finalizar o pedido: " . $e->getMessage());
}

exit;
?>