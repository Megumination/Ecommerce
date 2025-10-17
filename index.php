<?php
// ---- CÓDIGO DE DEPURAÇÃO (temporário) ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ---- FIM DO CÓDIGO DE DEPURAÇÃO ----

// INICIA A SESSÃO PARA PODER USAR AS VARIÁVEIS $_SESSION
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto & Vírgula | Início</title>
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
            <a href="promocoes.html">Promoções</a>
            <a href="parcerias.html">Parcerias</a>
            <a href="sobre.html">Sobre nós</a>
        </nav>

        <div class="icones">
            <a href="login.php"><img src="imagens/icone_login.png" alt="Login"></a>
            <a href="carrinho.php"><img src="imagens/carrinho.png" alt="Carrinho"></a>

            <?php
            // Se o usuário está logado E é um admin, mostra o link de gerenciamento
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo '<a href="gerenciar_produtos.php"><img src="imagens/engrenagem.png" alt="Gerenciar"></a>';
            }
            ?>

            <a href="https://www.instagram.com/pontovirgula.ltda/#" target="_blank">
                <img src="imagens/instagram.png" alt="Instagram">
            </a>
        </div>
    </header>

    <section class="banner">
        <h1>Organize sua rotina com estilo!</h1>
        <p>Produtos de papelaria e agendas para quem gosta de unir arte, leveza e praticidade.</p>
        <a href="#produtos" class="btn">Ver produtos</a>
    </section>

    <!-- SEÇÃO DE BENEFÍCIOS -->
    <section class="section-beneficios">
        <h2>Nossos Benefícios</h2>
        <div class="circle-cards">

            <!-- Design exclusivo (brilhos/losango) -->
            <div class="circle-card">
                <div class="circle-icon">
                    <svg viewBox="0 0 24 24">
                        <polygon points="12,2 15,10 23,12 15,14 12,22 9,14 1,12 9,10" />
                    </svg>
                </div>
                <h3>Design exclusivo</h3>
                <p>Modelos únicos criados pelos alunos, só aqui na nossa escola.</p>
            </div>

            <!-- Alta qualidade (símbolo de "check") -->
            <div class="circle-card">
                <div class="circle-icon">
                    <svg viewBox="0 0 24 24">
                        <polyline points="4 12 10 18 20 6" />
                    </svg>
                </div>
                <h3>Alta qualidade</h3>
                <p>Cadernos resistentes e perfeitos para todas as matérias.</p>
            </div>

            <!-- Feito com carinho (coração) -->
            <div class="circle-card">
                <div class="circle-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 21s-8-6-8-11a5 5 0 0 1 8-4 5 5 0 0 1 8 4c0 5-8 11-8 11z" />
                    </svg>
                </div>
                <h3>Feito com carinho</h3>
                <p>Produzido pensando no conforto e estilo de cada estudante.</p>
            </div>

        </div>
    </section>


    <section id="produtos" class="produtos-destaque">
        <h2>Produtos em destaque</h2>
        <div class="cards">
            <div class="card">
                <div class="card-image-wrapper">
                    <img src="imagens/cadernoIpe.png" alt="Caderno Ipê rosa">
                </div>
                <h3>Caderno Ipê Rosa</h3>
                <p class="preco">R$23,00</p>
                <button>Comprar</button>
            </div>
            <div class="card">
                <div class="card-image-wrapper">
                    <img src="imagens/cadernoCTI.png" alt="Caderno CTI">
                </div>
                <h3>Caderno curso técnico CTI</h3>
                <p class="preco">R$23,00</p>
                <button>Comprar</button>
            </div>
        </div>
    </section>

    <div id="modalCompra" class="modal">
        <div class="modal-content">
            <span class="close-modal">X</span>
            <div class="modal-body">
                <img id="modalImg" src="" alt="Produto">
                <div class="modal-info">
                    <p><strong>Nome:</strong> <span id="modalNome">Produto</span></p>
                    <p><strong>Tamanho:</strong> <span id="modalTamanho">A5 (14,8 x 21 cm)</span></p>
                    <p><strong>Miolo:</strong> <span id="modalMiolo">Papel sulfite pautado simples</span></p>
                    <p><strong>Material:</strong> <span id="modalMaterial">Capa flexível</span></p>
                    <p><strong>Preço:</strong> <span id="modalPreco">R$23,00</span></p>
                    <label for="modalQuantidade">Quantidade:</label>
                    <input type="number" id="modalQuantidade" value="1" min="1">
                </div>
            </div>
            <button id="btnConfirmarCompra" class="btn">Adicionar ao Carrinho</button>
        </div>
    </div>

    <!-- RODAPÉ -->
    <footer class="footer">
        <div class="footer-container">

            <!-- Sobre a empresa -->
            <div class="footer-section">
                <h3>Ponto & Vírgula</h3>
                <p>Organize com arte, viva com leveza!</p>
                <p>Em breve na Semana do Colégio CTI Bauru 2025, nos dias 21 a 24 de outubro.</p>
                <p>Horários: 8h às 12h e 19h às 22h</p>
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
                <p>Endereço: Avenida Nações Unidas, n° 58-50, Bairro Vargem Limpa, Bauru – SP, CEP
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
        document.addEventListener('DOMContentLoaded', () => {
            // --- SELEÇÃO DOS ELEMENTOS ---
            const botoesComprar = document.querySelectorAll('.card button');
            const modal = document.getElementById('modalCompra');
            const closeModalBtn = document.querySelector('.close-modal');
            const btnConfirmarCompra = document.getElementById('btnConfirmarCompra');

            let produtoAtual = {}; // Variável para guardar os dados do produto clicado

            // --- FUNÇÃO PARA ABRIR O MODAL ---
            botoesComprar.forEach(botao => {
                botao.addEventListener('click', (event) => {
                    // Pega o card pai do botão que foi clicado
                    const card = event.target.closest('.card');

                    // Extrai as informações do produto do card
                    const nomeProduto = card.querySelector('h3').textContent;
                    const precoProduto = card.querySelector('.preco').textContent;
                    const imgProduto = card.querySelector('img').src;

                    // Guarda as informações do produto atual
                    produtoAtual = {
                        nome: nomeProduto,
                        preco: precoProduto,
                        img: imgProduto
                    };

                    // Preenche o modal com as informações do produto
                    document.getElementById('modalImg').src = imgProduto;
                    document.getElementById('modalNome').textContent = nomeProduto;
                    document.getElementById('modalPreco').textContent = precoProduto;
                    document.getElementById('modalQuantidade').value = 1; // Reseta a quantidade para 1

                    // Mostra o modal
                    modal.style.display = 'flex';
                });
            });

            // --- FUNÇÃO PARA FECHAR O MODAL ---
            const fecharModal = () => {
                modal.style.display = 'none';
            };

            closeModalBtn.addEventListener('click', fecharModal);
            // Fecha o modal se clicar fora da caixa de conteúdo
            window.addEventListener('click', (event) => {
                if (event.target == modal) {
                    fecharModal();
                }
            });

            // --- FUNÇÃO PARA ADICIONAR AO CARRINHO (localStorage) ---
            btnConfirmarCompra.addEventListener('click', () => {
                // Pega a quantidade do input do modal
                const quantidade = parseInt(document.getElementById('modalQuantidade').value);

                // Pega o carrinho existente no localStorage ou cria um array vazio
                let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

                // Verifica se o produto já existe no carrinho
                const produtoExistente = carrinho.find(item => item.nome === produtoAtual.nome);

                if (produtoExistente) {
                    // Se já existe, apenas aumenta a quantidade
                    produtoExistente.quantidade += quantidade;
                } else {
                    // Se não existe, adiciona o produto novo ao carrinho
                    carrinho.push({
                        nome: produtoAtual.nome,
                        preco: produtoAtual.preco,
                        img: produtoAtual.img,
                        quantidade: quantidade
                    });
                }

                // Salva o carrinho atualizado de volta no localStorage
                localStorage.setItem("carrinho", JSON.stringify(carrinho));

                // Avisa o usuário e fecha o modal
                alert(`${produtoAtual.nome} foi adicionado ao carrinho!`);
                fecharModal();
            });
        });
    </script>
</body>
</body>

</html>