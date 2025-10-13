<?php
// Inicia a sessão para futuramente associar o carrinho a um usuário logado
session_start();
include 'util.php'; // Inclui suas funções de banco de dados
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Carrinho</title>
    <link rel="icon" href="imagens/favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form class="carrinho-body" action="finalizar_pedido.php" method="post">
        <div class="carrinho-container">

            <div class="back-link">
                <a href="index.php"><strong>X</strong></a>
            </div>

            <div class="carrinho-items">
                <h2>🛒 CARRINHO</h2>
                <div id="lista-produtos-carrinho">
                    </div>
                 <p id="carrinho-vazio" style="display: none; text-align: center; margin-top: 20px;">Seu carrinho está vazio.</p>
            </div>

            <div class="resumo-pedido">
                <h2>RESUMO DO PEDIDO</h2>
                <div class="resumo-info">
                    <p>Subtotal (<span id="qtde">0</span> itens)</p>
                    <p id="total">R$0,00</p>
                </div>
                <div class="pagamento-info">
                    <h3>PAGAMENTO</h3>
                    <p>PIX</p>
                    <img src="imagens/pix-qr-code.png" alt="QR Code PIX" class="qr-code">
                    <p>Cartão de Crédito</p>
                    <input type="text" placeholder="Número do Cartão">
                    <input type="text" placeholder="Nome no Cartão">
                    <input type="text" placeholder="Validade (MM/AA)">
                    <input type="text" placeholder="CVV">
                </div>

                <button type="submit" class="btn-confirmar">CONFIRMAR PAGAMENTO</button>
            </div>
        </div>

        <input type="hidden" name="carrinho_json" id="carrinho_json">
    </form>

    <script>
        // Este script será executado assim que a página carregar
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('lista-produtos-carrinho');
            const carrinhoVazioMsg = document.getElementById('carrinho-vazio');
            let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

            if (carrinho.length === 0) {
                carrinhoVazioMsg.style.display = 'block';
            } else {
                carrinhoVazioMsg.style.display = 'none';
                carrinho.forEach((produto, index) => {
                    // Cria o HTML para cada produto no carrinho
                    const itemHTML = `
                        <div class="item-card" data-index="${index}">
                            <img src="${produto.img}" alt="${produto.nome}">
                            <div class="item-info">
                                <h3 class="nome-produto">${produto.nome}</h3>
                                <p class="preco-produto">${produto.preco}</p>
                                <p>
                                    QUANT:
                                    <input type="number" class="quantidade" value="${produto.quantidade}" min="1">
                                    <button type="button" class="btn-excluir">EXCLUIR</button>
                                </p>
                            </div>
                        </div>
                    `;
                    container.innerHTML += itemHTML;
                });
            }
            
            // Após criar os itens, anexa os eventos e atualiza o total
            setupEventListeners();
            atualizarTotal();
        });

        function atualizarTotal() {
            const itens = document.querySelectorAll('.item-card');
            const totalEl = document.getElementById('total');
            const qtdeEl = document.getElementById('qtde');
            const carrinhoJsonInput = document.getElementById('carrinho_json');

            let total = 0;
            let qtde = 0;
            let carrinhoAtualizado = [];

            itens.forEach(item => {
                const nome = item.querySelector('.nome-produto').textContent;
                const precoTexto = item.querySelector('.preco-produto').textContent;
                const preco = parseFloat(precoTexto.replace('R$', '').replace(',', '.'));
                const quantidade = parseInt(item.querySelector('.quantidade').value);
                const img = item.querySelector('img').src;

                total += preco * quantidade;
                qtde += quantidade;
                
                // Reconstrói o array do carrinho para enviar ao PHP
                carrinhoAtualizado.push({ nome, preco: precoTexto, quantidade, img });
            });

            totalEl.textContent = 'R$' + total.toFixed(2).replace('.', ',');
            qtdeEl.textContent = qtde;

            // Atualiza o localStorage com as novas quantidades
            localStorage.setItem("carrinho", JSON.stringify(carrinhoAtualizado));

            // Atualiza o campo escondido do formulário com os dados em formato JSON
            carrinhoJsonInput.value = JSON.stringify(carrinhoAtualizado);
        }

        function setupEventListeners() {
            document.querySelectorAll('.item-card').forEach(item => {
                const input = item.querySelector('.quantidade');
                const btnExcluir = item.querySelector('.btn-excluir');
                
                // Atualiza o total quando a quantidade muda
                input.addEventListener('change', atualizarTotal);
                
                // Remove o item e atualiza o total quando clica em excluir
                btnExcluir.addEventListener('click', () => {
                    item.remove();
                    // Precisamos recalcular tudo e salvar no localStorage
                    atualizarTotal(); 
                });
            });
        }
    </script>
</body>

</html>