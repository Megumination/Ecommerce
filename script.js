const itens = document.querySelectorAll('.item-card');
const totalEl = document.getElementById('total');
const qtdeEl = document.getElementById('qtde');


function atualizarTotal() {
    let total = 0;
    let qtde = 0;
    itens.forEach(item => {
        const preco = parseFloat(item.querySelector('.preco-produto').textContent.replace('R$', '').replace(',', '.'));
        const quantidade = parseInt(item.querySelector('.quantidade').value);
        total += preco * quantidade;
        qtde += quantidade;
    });
    totalEl.textContent = 'R$' + total.toFixed(2).replace('.', ',');
    qtdeEl.textContent = qtde;
}


itens.forEach(item => {
    const input = item.querySelector('.quantidade');
    const btnExcluir = item.querySelector('.btn-excluir');


    input.addEventListener('change', atualizarTotal);
    btnExcluir.addEventListener('click', () => {
        item.remove();
        atualizarTotal();
    });
});


atualizarTotal();

/* CARROSSEL DOS INTEGRANTES*/
document.addEventListener('DOMContentLoaded', () => {
    // 1. Seleção de Elementos e Variáveis
    const container = document.querySelector('.carrossel-slides');
    const slides = document.querySelectorAll('.slide-membro');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    // Boa prática: interrompe se faltar algum elemento crucial
    if (!container || slides.length === 0 || !prevBtn || !nextBtn) {
        return; 
    }

    let currentIndex = 0;
    const totalSlides = slides.length;
    const intervalTime = 4000; // 4 segundos para passagem automática
    let autoSlideInterval;

    // 2. Função para mover os slides
    function showSlide(index) {
        // Lógica de loop: 
        if (index >= totalSlides) {
            currentIndex = 0; // Volta ao primeiro
        } else if (index < 0) {
            currentIndex = totalSlides - 1; // Vai para o último
        } else {
            currentIndex = index;
        }

        // Move o container (100% para cada slide)
        const offset = -currentIndex * 100;
        container.style.transform = `translateX(${offset}%)`;
    }

    // 3. Função para iniciar o movimento automático
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            showSlide(currentIndex + 1);
        }, intervalTime);
    }
    
    // 4. Função para resetar o temporizador após interação manual
    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide(); // Inicia o timer novamente
    }

    // 5. Adiciona os Event Listeners (Faz os botões funcionarem)
    prevBtn.addEventListener('click', () => {
        showSlide(currentIndex - 1);
        resetAutoSlide();
    });

    nextBtn.addEventListener('click', () => {
        showSlide(currentIndex + 1);
        resetAutoSlide();
    });

    // Inicia o carrossel automático ao carregar a página
    startAutoSlide();
});