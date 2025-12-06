// Productos
const products = [
    {
        id: 1,
        name: "Pan Artesanal de Masa Madre",
        category: "Panes",
        price: 4.50,
        description: "Pan tradicional elaborado con masa madre natural, fermentado lentamente durante 24 horas para obtener un sabor único y corteza crujiente.",
        image: "https://images.unsplash.com/photo-1627308593341-d886acdc06a2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc2FuJTIwYnJlYWQlMjBiYWtlcnl8ZW58MXx8fHwxNzY0ODkwMTM4fDA&ixlib=rb-4.1.0&q=80&w=1080",
        featured: true
    },
    {
        id: 2,
        name: "Croissant de Mantequilla",
        category: "Bollería",
        price: 2.80,
        description: "Croissant hojaldrado con mantequilla francesa, horneado cada mañana para garantizar frescura y textura perfecta.",
        image: "https://images.unsplash.com/photo-1712723246766-3eaea22e52ff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjcm9pc3NhbnQlMjBwYXN0cnl8ZW58MXx8fHwxNzY0ODkxOTc1fDA&ixlib=rb-4.1.0&q=80&w=1080",
        featured: true
    },
    {
        id: 3,
        name: "Tarta de Chocolate",
        category: "Pasteles",
        price: 25.00,
        description: "Deliciosa tarta de chocolate con tres capas de bizcocho y crema de chocolate belga. Perfecta para celebraciones.",
        image: "https://images.unsplash.com/photo-1651346851254-a1c60422b0d7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjYWtlJTIwYmFrZXJ5fGVufDF8fHx8MTc2NDk0NTI0NHww&ixlib=rb-4.1.0&q=80&w=1080",
        featured: true
    },
    {
        id: 5,
        name: "Pan Integral de Semillas",
        category: "Panes",
        price: 3.90,
        description: "Pan integral rico en fibra, elaborado con semillas de girasol, lino y sésamo. Ideal para una dieta saludable.",
        image: "https://images.unsplash.com/photo-1702742800078-06d9e9af4fd8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYWtlcnklMjBicmVhZCUyMHByb2R1Y3RzfGVufDF8fHx8MTc2NDk3OTk5MXww&ixlib=rb-4.1.0&q=80&w=1080",
        featured: true
    },
    {
        id: 8,
        name: "Empanada Gallega",
        category: "Salados",
        price: 15.00,
        description: "Empanada tradicional gallega rellena de atún, pimientos y cebolla caramelizada.",
        image: "https://images.unsplash.com/photo-1627308593341-d886acdc06a2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc2FuJTIwYnJlYWQlMjBiYWtlcnl8ZW58MXx8fHwxNzY0ODkwMTM4fDA&ixlib=rb-4.1.0&q=80&w=1080",
        featured: true
    }
];

// ======================
// Mobile Menu
// ======================
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileNav = document.getElementById('mobileNav');
const menuIcon = mobileMenuBtn.querySelector('.menu-icon');
const closeIcon = mobileMenuBtn.querySelector('.close-icon');

mobileMenuBtn.addEventListener('click', () => {
    mobileNav.classList.toggle('active');
    menuIcon.classList.toggle('hidden');
    closeIcon.classList.toggle('hidden');
});

// Cerrar menú al hacer clic en un enlace
document.querySelectorAll('.nav-link-mobile').forEach(link => {
    link.addEventListener('click', () => {
        mobileNav.classList.remove('active');
        menuIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
    });
});

// ======================
// Smooth Scroll
// ======================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ======================
// Carousel
// ======================
let currentSlide = 0;
let slidesToShow = 3;
let autoplayInterval;

const carouselTrack = document.getElementById('carouselTrack');
const carouselDots = document.getElementById('carouselDots');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

const featuredProducts = products.filter(p => p.featured);

// Detectar número de slides según ancho de pantalla
function updateSlidesToShow() {
    if (window.innerWidth < 640) {
        slidesToShow = 1;
    } else if (window.innerWidth < 1024) {
        slidesToShow = 2;
    } else {
        slidesToShow = 3;
    }
    updateCarousel();
}

// Crear tarjeta de producto
function createProductCard(product) {
    return `
        <div class="product-card">
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}">
                <div class="product-price">${product.price.toFixed(2)}€</div>
            </div>
            <div class="product-info">
                <span class="product-category">${product.category}</span>
                <h3 class="product-name">${product.name}</h3>
                <p class="product-description">${product.description}</p>
                <a href="#producto-${product.id}" class="product-btn">Ver Más</a>
            </div>
        </div>
    `;
}

// Renderizar carrusel
function renderCarousel() {
    const startIndex = currentSlide * slidesToShow;
    const visibleProducts = featuredProducts.slice(startIndex, startIndex + slidesToShow);
    
    carouselTrack.style.gridTemplateColumns = `repeat(${slidesToShow}, 1fr)`;
    carouselTrack.innerHTML = visibleProducts.map(product => createProductCard(product)).join('');
    
    carouselTrack.style.opacity = '1';
    carouselTrack.style.transform = 'translateX(0)';
}

// Crear dots
function createDots() {
    const totalSlides = Math.ceil(featuredProducts.length / slidesToShow);
    carouselDots.innerHTML = '';
    
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('button');
        dot.classList.add('dot');
        if (i === currentSlide) {
            dot.classList.add('active');
        }
        dot.setAttribute('aria-label', `Ir a slide ${i + 1}`);
        dot.addEventListener('click', () => goToSlide(i));
        carouselDots.appendChild(dot);
    }
}

// Actualizar dots
function updateDots() {
    const dots = carouselDots.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
        if (index === currentSlide) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

// Ir a slide específico
function goToSlide(slideIndex) {
    carouselTrack.style.opacity = '0';
    carouselTrack.style.transform = 'translateX(100px)';
    
    setTimeout(() => {
        currentSlide = slideIndex;
        renderCarousel();
        updateDots();
    }, 300);
}

// Siguiente slide
function nextSlide() {
    const totalSlides = Math.ceil(featuredProducts.length / slidesToShow);
    carouselTrack.style.opacity = '0';
    carouselTrack.style.transform = 'translateX(100px)';
    
    setTimeout(() => {
        currentSlide = (currentSlide + 1) % totalSlides;
        renderCarousel();
        updateDots();
    }, 300);
}

// Slide anterior
function prevSlide() {
    const totalSlides = Math.ceil(featuredProducts.length / slidesToShow);
    carouselTrack.style.opacity = '0';
    carouselTrack.style.transform = 'translateX(-100px)';
    
    setTimeout(() => {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        renderCarousel();
        updateDots();
    }, 300);
}

// Actualizar carrusel completo
function updateCarousel() {
    renderCarousel();
    createDots();
}

// Autoplay
function startAutoplay() {
    autoplayInterval = setInterval(nextSlide, 4000);
}

function stopAutoplay() {
    clearInterval(autoplayInterval);
}

// Event listeners del carrusel
prevBtn.addEventListener('click', () => {
    stopAutoplay();
    prevSlide();
    startAutoplay();
});

nextBtn.addEventListener('click', () => {
    stopAutoplay();
    nextSlide();
    startAutoplay();
});

// Responsive
window.addEventListener('resize', updateSlidesToShow);

// Pausar autoplay al hacer hover
const carouselWrapper = document.querySelector('.carousel-wrapper');
carouselWrapper.addEventListener('mouseenter', stopAutoplay);
carouselWrapper.addEventListener('mouseleave', startAutoplay);

// Inicializar carrusel
updateSlidesToShow();
startAutoplay();

// ======================
// Featured Products
// ======================
function renderFeaturedProducts() {
    const featuredProductsGrid = document.getElementById('featuredProductsGrid');
    const productsToShow = featuredProducts.slice(0, 4);
    
    const productsHTML = productsToShow.map(product => `
        <div class="featured-card">
            <div class="featured-image">
                <img src="${product.image}" alt="${product.name}">
                <div class="featured-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Destacado</span>
                </div>
            </div>
            <div class="featured-info">
                <span class="featured-category">${product.category}</span>
                <h3 class="featured-name">${product.name}</h3>
                <p class="featured-description">${product.description}</p>
                <div class="featured-footer">
                    <span class="featured-price">${product.price.toFixed(2)}€</span>
                    <a href="#producto-${product.id}" class="btn-featured">Ver Más</a>
                </div>
            </div>
        </div>
    `).join('');
    
    featuredProductsGrid.innerHTML = productsHTML;
}

// Inicializar productos destacados
renderFeaturedProducts();

// ======================
// Header Scroll Effect
// ======================
let lastScroll = 0;
const header = document.getElementById('header');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    }
    
    lastScroll = currentScroll;
});
