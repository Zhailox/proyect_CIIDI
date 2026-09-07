document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const image = entry.target;
                    image.src = image.dataset.src;
                    image.removeAttribute('data-src');
                    image.classList.add('loaded');
                    observer.unobserve(image);
                }
            });
        }, {
            rootMargin: '100px 0px', // Cargar 100px antes de que entren al viewport
            threshold: 0.01
        });

        lazyImages.forEach(image => imageObserver.observe(image));
    } else {
        // Fallback para navegadores antiguos
        lazyImages.forEach(image => {
            image.src = image.dataset.src;
            image.removeAttribute('data-src');
            image.classList.add('loaded');
        });
    }
});