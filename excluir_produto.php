<?php
session_start();
include 'util.php';

// VERIFICAÇÃO DE ADMIN
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['admin'] != true) {
    echo "<script>alert('Acesso negado!'); window.location.href = 'login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $conn = conecta();
    $id = $_GET['id'];

    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $delete = $conn->prepare($sql);
    $delete->execute([':id' => $id]);

    header("Location: gerenciar_produtos.php");
} else {
    // Se não houver ID, volta para a página de gerenciamento
    header("Location: gerenciar_produtos.php");
}
?>