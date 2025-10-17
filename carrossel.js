document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.carrossel-slides');
    const slides = document.querySelectorAll('.slide-membro');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    if (!container || slides.length === 0 || !prevBtn || !nextBtn) return;

    let currentIndex = 0;
    const totalSlides = slides.length;
    const intervalTime = 4000; // 4 segundos
    let autoSlideInterval;

    function showSlide(index) {
        if (index >= totalSlides) currentIndex = 0;
        else if (index < 0) currentIndex = totalSlides - 1;
        else currentIndex = index;

        container.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            currentIndex++;
            showSlide(currentIndex);
        }, intervalTime);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    prevBtn.addEventListener('click', () => {
        currentIndex--;
        showSlide(currentIndex);
        resetAutoSlide();
    });

    nextBtn.addEventListener('click', () => {
        currentIndex++;
        showSlide(currentIndex);
        resetAutoSlide();
    });

    showSlide(currentIndex); // Mostra o primeiro slide na inicialização
    startAutoSlide();
});
