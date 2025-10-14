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
    <header class="header">
        <div class="logo">
            <img src="imagens/logo.png" alt="Logotipo Ponto & Vírgula">
        </div>
        <nav class="menu">
            <a href="index.php">Início</a>
            <a href="index.php">Início</a>
            <a href="promocoes.html">Promoções</a>
            <a href="parcerias.html">Parcerias</a>
            <a href="sobre.html">Sobre nós</a>
        </nav>

        <div class="icones">
            <a href="login.php"><img src="imagens/icone_login.png" alt="Login"></a>
            <?php
            // Se o usuário está logado E é um admin, mostra o link de gerenciamento
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo '<a href="gerenciar_produtos.php"><img src="imagens/engrenagem.png" alt="Gerenciar"></a>'; 
            }
            ?>
            
            <a href="https://www.instagram.com/pontovirgula.ltda/#">
                <img src="imagens/instagram.png" alt="Instagram">
            </a>
        </div>

    </header>
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

    <!-- RODAPÉ -->
    <footer class="footer">
        <div class="footer-container">

            <!-- Sobre a empresa -->
            <div class="footer-section">
                <h3>Ponto & Vírgula</h3>
                <p>Organize com arte, viva com leveza!</p>
                <p>Em breve na Semana do Colégio CTI Bauru 2025, nos dias 20 a 24 de outubro.</p>
            </div>

            <!-- Links úteis -->
            <div class="footer-section links-uteis">
                <h4>Links úteis</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="sobre.html">Sobre nós</a></li>
                    <li><a href="promocoes.html">Promoções</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
            </div>

            <!-- Contato -->
            <div class="footer-section" id="contato">
                <h4>Contato</h4>
                <p>Email: pontoevirgula@gmail.com</p>
                <p>Telefone: +55 11 99999-9999</p>
                <p>Endereço: Avenida Nações Unidas, 58-50, Núcleo Residencial Presidente Geisel, Bauru – SP, CEP
                    17033-260</p>
            </div>

            <!-- Redes sociais -->
            <div class="footer-section">
                <h4>Siga-nos</h4>
                <div class="social-icons">
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" /></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram" /></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter" /></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn" /></a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© 2025 Ponto & Vírgula. Todos os direitos reservados.</p>
        </div>
    </footer>

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