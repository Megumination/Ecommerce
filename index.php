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
    <!-- HEADER -->
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
            <a href="carrinho.html"><img src="imagens/carrinho.png" alt="Carrinho"></a>
            <a href="https://www.instagram.com/pontovirgula.ltda/#" target="_blank">
                <img src="imagens/instagram.png" alt="Instagram"></a>
        </div>
    </header>

    <!-- BANNER-->
    <div class="pena">
        <img src="imagens/penaDouradaEBranca.png" alt="pena dourada e branca" width="200px">
    </div>

    <section class="banner">
        <h1>Organize sua rotina com estilo!</h1>
        <p>Produtos de papelaria e agendas para quem gosta de unir arte, leveza e praticidade.</p>
        <a href="#produtos" class="btn">Ver produtos</a>
    </section>

    <!-- PRODUTOS EM DESTAQUE -->
    <section id="produtos" class="produtos-destaque">
        <h2>Produtos em destaque</h2>
        <div class="cards">
            <div class="card">
                <img src="imagens/agenda1.jpg" alt="Agenda Preta">
                <h3>Agenda Preta</h3>
                <p class="preco">R$19,99</p>
                <button>Comprar</button>
            </div>
            <div class="card">
                <img src="imagens/agenda2.jpg" alt="Agenda Rosa">
                <h3>Agenda Rosa</h3>
                <p class="preco">R$19,99</p>
                <button>Comprar</button>
            </div>
            <div class="card">
                <img src="imagens/agenda3.jpg" alt="Agenda Branca">
                <h3>Agenda Branca</h3>
                <p class="preco">R$19,99</p>
                <button>Comprar</button>
            </div>
        </div>
    </section>

    <!-- Modal de compra -->
    <div id="modalCompra" class="modal">
        <div class="modal-content">
            <!-- Botão de fechar -->
            <span class="close-modal">X</span>

            <div class="modal-body">
                <!-- Imagem do produto -->
                <img id="modalImg" src="" alt="Produto">

                <!-- Informações do produto -->
                <div class="modal-info">
                    <p><strong>Nome:</strong> <span id="modalNome">Produto</span></p>
                    <p><strong>Tamanho:</strong> <span id="modalTamanho">-</span></p>
                    <p><strong>Miolo:</strong> <span id="modalMiolo">-</span></p>
                    <p><strong>Material:</strong> <span id="modalMaterial">-</span></p>
                    <p><strong>Preço:</strong> <span id="modalPreco">R$19,90</span></p>

                    <!-- Quantidade -->
                    <label for="modalQuantidade">Quantidade:</label>
                    <input type="number" id="modalQuantidade" value="1" min="1">
                </div>
            </div>

            <!-- Botão de confirmar compra -->
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
                <p>Endereço: Av. Nações Unidas, 58-50, Bairro Vargem Limpa, 
                <br>Bauru – SP, CEP 17033-260</p>
            </div>

            <!-- Redes sociais -->
            <div class="footer-section">
                <h4>Siga-nos</h4>
                <div class="social-icons">
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" /></a>
                    <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" target="_blank" alt="Instagram" /></a>
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
        // Seleciona elementos do modal
        const modal = document.getElementById("modalCompra");
        const modalNome = document.getElementById("modalNome");
        const modalPreco = document.getElementById("modalPreco");
        const modalImg = document.getElementById("modalImg");
        const modalQuantidade = document.getElementById("modalQuantidade");
        const btnConfirmar = document.getElementById("btnConfirmarCompra");
        const btnFechar = document.querySelector(".close-modal");

        // Seleciona todos os botões "Comprar" dos produtos
        const btnsComprar = document.querySelectorAll(".produtos-destaque .card button");

        // Objeto para armazenar o produto selecionado
        let produtoSelecionado = {};

        // Adiciona evento a cada botão "Comprar"
        btnsComprar.forEach((btn) => {
            btn.addEventListener("click", () => {
                const card = btn.parentElement; // pega o card do produto clicado

                // Preenche o objeto com informações do produto
                produtoSelecionado = {
                    nome: card.querySelector("h3").innerText,
                    preco: card.querySelector(".preco").innerText,
                    img: card.querySelector("img").src
                };

                // Atualiza o conteúdo do modal com os dados do produto
                modalNome.innerText = produtoSelecionado.nome;
                modalPreco.innerText = produtoSelecionado.preco;
                modalImg.src = produtoSelecionado.img;
                modalQuantidade.value = 1; // quantidade padrão

                // Mostra o modal
                modal.style.display = "flex";
            });
        });

        // Fecha o modal quando clica no "X"
        btnFechar.onclick = () => modal.style.display = "none";

        // Fecha o modal quando clica fora do conteúdo
        window.onclick = e => {
            if (e.target == modal) modal.style.display = "none";
        };

        // Confirma a compra e adiciona ao carrinho
        btnConfirmar.addEventListener("click", () => {
            const quantidade = parseInt(modalQuantidade.value);

            // Recupera o carrinho do localStorage ou cria um array vazio
            let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

            // Adiciona o produto selecionado com a quantidade ao carrinho
            carrinho.push({ ...produtoSelecionado, quantidade });

            // Salva o carrinho atualizado no localStorage
            localStorage.setItem("carrinho", JSON.stringify(carrinho));

            // Mostra alerta de confirmação
            alert(produtoSelecionado.nome + " adicionado ao carrinho!");

            // Fecha o modal
            modal.style.display = "none";
        });
    </script>
</body>

</html>