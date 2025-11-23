// Funcionalidad del carrusel
document.addEventListener('DOMContentLoaded', function() {
    const carouselInner = document.querySelector('.carousel-inner');
    const indicators = document.querySelectorAll('.carousel-indicator');
    let currentSlide = 0;
    const totalSlides = document.querySelectorAll('.carousel-item').length;
    
    // Función para cambiar de slide
    function goToSlide(slideIndex) {
        if (slideIndex < 0) slideIndex = totalSlides - 1;
        if (slideIndex >= totalSlides) slideIndex = 0;
        
        carouselInner.style.transform = `translateX(-${slideIndex * 100}%)`;
        
        // Actualizar indicadores
        indicators.forEach((indicator, index) => {
            if (index === slideIndex) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
        
        currentSlide = slideIndex;
    }
    
    // Agregar eventos a los indicadores
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToSlide(index);
        });
    });
    
    // Cambio automático de slides
    setInterval(() => {
        goToSlide(currentSlide + 1);
    }, 5000);
});