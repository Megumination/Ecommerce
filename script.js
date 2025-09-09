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