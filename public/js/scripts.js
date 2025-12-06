// Cart State
let cart = [];
let currentFilter = 'all';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
	renderProducts();
	updateCartUI();
});

// Render Products
function renderProducts() {
	const productsGrid = document.getElementById('productsGrid');
	if (!productsGrid) return;
	const filteredProducts = currentFilter === 'all' 
		? products 
		: products.filter(p => p.category === currentFilter);

	productsGrid.innerHTML = filteredProducts.map(product => `
		<div class="product-card card">
			<div class="product-image">
				<img src="${product.image}" alt="${product.name}" class="card-img-top">
				${product.isNew ? '<span class="product-badge">Nuevo</span>' : ''}
			</div>
			<div class="product-content card-body">
				<h3 class="product-name h5">${product.name}</h3>
				<p class="product-description small text-muted">${product.description}</p>
				<p class="product-price fw-bold">$${product.price.toFixed(2)}</p>
			</div>
			<div class="product-footer card-footer text-center">
				<button class="btn btn-primary" onclick="addToCart(${product.id})">
					<i class="fas fa-shopping-cart"></i>
					Agregar al Carrito
				</button>
			</div>
		</div>
	`).join('');
}

// Filter Products
function filterProducts(category, btnElem) {
	currentFilter = category;
    
	// Update tab buttons
	document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
	if (btnElem && btnElem.classList) {
		btnElem.classList.add('active');
	} else {
		// fallback: try to find by data-category
		const fallback = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.dataset.category === category);
		if (fallback) fallback.classList.add('active');
	}
    
	renderProducts();
}

// Add to Cart
function addToCart(productId) {
	const product = products.find(p => p.id === productId);
	const existingItem = cart.find(item => item.id === productId);
    
	if (existingItem) {
		existingItem.quantity += 1;
	} else {
		cart.push({
			...product,
			quantity: 1
		});
	}
    
	updateCartUI();
    
	// Optional: Show a brief notification
	showNotification('Producto agregado al carrito');
}

// Update Quantity
function updateQuantity(productId, delta) {
	const item = cart.find(item => item.id === productId);
	if (item) {
		item.quantity += delta;
		if (item.quantity <= 0) {
			removeFromCart(productId);
		} else {
			updateCartUI();
		}
	}
}

// Remove from Cart
function removeFromCart(productId) {
	cart = cart.filter(item => item.id !== productId);
	updateCartUI();
}

// Update Cart UI
function updateCartUI() {
	const cartBadge = document.getElementById('cartBadge');
	const cartItems = document.getElementById('cartItems');
	const totalAmount = document.getElementById('totalAmount');
	const cartFooter = document.getElementById('cartFooter');
    
	// Update badge
	const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
	if (cartBadge) {
		cartBadge.textContent = totalItems;
		cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
	}
    
	// Update cart items
	if (!cartItems) return;
	if (cart.length === 0) {
		cartItems.innerHTML = '<div class="empty-cart text-center text-muted"><p>Tu carrito está vacío</p></div>';
		if (cartFooter) cartFooter.style.display = 'none';
	} else {
		cartItems.innerHTML = cart.map(item => `
			<div class="cart-item d-flex mb-3">
				<img src="${item.image}" alt="${item.name}" class="cart-item-image me-2" style="width:64px;height:64px;object-fit:cover;border-radius:.25rem">
				<div class="cart-item-details flex-grow-1">
					<h4 class="cart-item-name h6 mb-1">${item.name}</h4>
					<p class="cart-item-price mb-1">$${item.price.toFixed(2)}</p>
					<div class="cart-item-controls d-flex align-items-center gap-2">
						<button class="quantity-btn btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, -1)"><i class="fas fa-minus"></i></button>
						<span class="cart-item-quantity">${item.quantity}</span>
						<button class="quantity-btn btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, 1)"><i class="fas fa-plus"></i></button>
						<button class="remove-btn btn btn-sm btn-danger ms-2" onclick="removeFromCart(${item.id})"><i class="fas fa-trash"></i></button>
					</div>
				</div>
			</div>
		`).join('');
        
		// Update total
		const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
		if (totalAmount) totalAmount.textContent = `$${total.toFixed(2)}`;
		if (cartFooter) cartFooter.style.display = 'block';
	}
}

// Toggle Cart
function toggleCart() {
	const cartSidebar = document.getElementById('cartSidebar');
	const cartOverlay = document.getElementById('cartOverlay');
    
	if (!cartSidebar || !cartOverlay) return;
    
	cartSidebar.classList.toggle('active');
	cartOverlay.classList.toggle('active');
    
	// Prevent body scroll when cart is open
	if (cartSidebar.classList.contains('active')) {
		document.body.style.overflow = 'hidden';
	} else {
		document.body.style.overflow = '';
	}
}

// Toggle Mobile Menu
function toggleMenu() {
	const mobileMenu = document.getElementById('mobileMenu');
	if (!mobileMenu) return;
	mobileMenu.classList.toggle('active');
}


// Show Notification (optional)
function showNotification(message) {
	// Create a simple notification
	const notification = document.createElement('div');
	notification.style.cssText = `
		position: fixed;
		bottom: 2rem;
		right: 2rem;
		background-color: #10b981;
		color: white;
		padding: 1rem 1.5rem;
		border-radius: 0.5rem;
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
		z-index: 9999;
		animation: slideIn 0.3s ease;
	`;
	notification.textContent = message;
    
	document.body.appendChild(notification);
    
	setTimeout(() => {
		notification.style.animation = 'slideOut 0.3s ease';
		setTimeout(() => {
			if (notification.parentNode) notification.parentNode.removeChild(notification);
		}, 300);
	}, 2000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
	@keyframes slideIn {
		from {
			transform: translateX(100%);
			opacity: 0;
		}
		to {
			transform: translateX(0);
			opacity: 1;
		}
	}
    
	@keyframes slideOut {
		from {
			transform: translateX(0);
			opacity: 1;
		}
		to {
			transform: translateX(100%);
			opacity: 0;
		}
	}
`;
document.head.appendChild(style);

// Close cart when clicking on links
document.querySelectorAll('a[href^="#"]').forEach(link => {
	link.addEventListener('click', () => {
		const cartSidebar = document.getElementById('cartSidebar');
		const cartOverlay = document.getElementById('cartOverlay');
		if (cartSidebar && cartSidebar.classList.contains('active')) {
			cartSidebar.classList.remove('active');
			if (cartOverlay) cartOverlay.classList.remove('active');
			document.body.style.overflow = '';
		}
	});
});

// Scroll suave con offset para navbar fijo
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            // Extraer solo el hash (#sobre-nosotros, #productos, etc.)
            const hash = href.includes('#') ? '#' + href.split('#')[1] : null;
            
            if (!hash || hash === '#') return;
            
            const targetElement = document.querySelector(hash);
            if (targetElement) {
                e.preventDefault();
                const navbarHeight = 60; // ← AJUSTA ESTE VALOR
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Actualizar URL sin recargar
                history.pushState(null, null, hash);
            }
        });
    });
});

// ============================================
// ACCESSIBILITY MENU FUNCTIONS
// ============================================

let currentFontSize = 100;
const MIN_FONT_SIZE = 80;
const MAX_FONT_SIZE = 150;

// Toggle Accessibility Panel
function toggleAccessibility() {
	const panel = document.getElementById('accessibilityPanel');
	if (!panel) return;
	panel.classList.toggle('active');
}

// Increase Font Size
function increaseFontSize() {
	if (currentFontSize < MAX_FONT_SIZE) {
		currentFontSize += 10;
		applyFontSize();
	}
}

// Decrease Font Size
function decreaseFontSize() {
	if (currentFontSize > MIN_FONT_SIZE) {
		currentFontSize -= 10;
		applyFontSize();
	}
}

// Apply Font Size
function applyFontSize() {
	document.documentElement.style.fontSize = currentFontSize + '%';
	const fontSizeValue = document.getElementById('fontSizeValue');
	if (fontSizeValue) {
		fontSizeValue.textContent = currentFontSize + '%';
	}
    
	// Update button states
	const decreaseBtn = document.querySelector('.font-controls .btn-control:first-child');
	const increaseBtn = document.querySelector('.font-controls .btn-control:last-child');
    
	if (decreaseBtn && increaseBtn) {
		decreaseBtn.disabled = currentFontSize <= MIN_FONT_SIZE;
		increaseBtn.disabled = currentFontSize >= MAX_FONT_SIZE;
	}
    
	// Save to localStorage
	try{ localStorage.setItem('fontSize', currentFontSize); } catch(e){}
}

// Toggle High Contrast
function toggleHighContrast() {
	const checkbox = document.getElementById('highContrast');
	if (!checkbox) return;
    
	const isChecked = checkbox.checked;
	if (isChecked) {
		document.documentElement.classList.add('high-contrast');
	} else {
		document.documentElement.classList.remove('high-contrast');
	}
	// Save to localStorage
	try{ localStorage.setItem('highContrast', isChecked); } catch(e){}
}

// Toggle Dark Mode
function toggleDarkMode() {
	const checkbox = document.getElementById('darkMode');
	if (!checkbox) return;
    
	const isChecked = checkbox.checked;
	if (isChecked) {
		document.documentElement.classList.add('dark-mode');
	} else {
		document.documentElement.classList.remove('dark-mode');
	}
	// Save to localStorage
	try{ localStorage.setItem('darkMode', isChecked); } catch(e){}
}

// Reset Accessibility Settings
function resetAccessibility() {
	currentFontSize = 100;
	applyFontSize();
    
	const highContrastCheckbox = document.getElementById('highContrast');
	const darkModeCheckbox = document.getElementById('darkMode');
    
	if (highContrastCheckbox) {
		highContrastCheckbox.checked = false;
	}
	if (darkModeCheckbox) {
		darkModeCheckbox.checked = false;
	}
    
	document.documentElement.classList.remove('high-contrast', 'dark-mode');
    
	// Clear localStorage
	try{ localStorage.removeItem('fontSize'); localStorage.removeItem('highContrast'); localStorage.removeItem('darkMode'); } catch(e){}
}

// Load Accessibility Settings from localStorage
function loadAccessibilitySettings() {
	// Load font size
	try{
		const savedFontSize = localStorage.getItem('fontSize');
		if (savedFontSize) {
			currentFontSize = parseInt(savedFontSize);
			applyFontSize();
		}
	}catch(e){}
    
	// Load high contrast
	try{
		const savedHighContrast = localStorage.getItem('highContrast');
		if (savedHighContrast === 'true') {
			const highContrastCheckbox = document.getElementById('highContrast');
			if (highContrastCheckbox) {
				highContrastCheckbox.checked = true;
			}
			document.documentElement.classList.add('high-contrast');
		}
	}catch(e){}
    
	// Load dark mode
	try{
		const savedDarkMode = localStorage.getItem('darkMode');
		if (savedDarkMode === 'true') {
			const darkModeCheckbox = document.getElementById('darkMode');
			if (darkModeCheckbox) {
				darkModeCheckbox.checked = true;
			}
			document.documentElement.classList.add('dark-mode');
		}
	}catch(e){}
}

// Initialize accessibility features when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
	// Load saved settings
	loadAccessibilitySettings();

	// Initialize controls (listeners for checkboxes/buttons)
	initAccessibilityControls();
	// Initialize footer slice scrolling (prev/next buttons)
	initFooterSlice();
    
	// Close accessibility panel when clicking outside
	document.addEventListener('click', (e) => {
		const panel = document.getElementById('accessibilityPanel');
		const btn = document.getElementById('accessibilityBtn');
        
		if (panel && btn && !panel.contains(e.target) && !btn.contains(e.target)) {
			panel.classList.remove('active');
		}
	});
});

// New: attach event listeners to accessibility controls
function initAccessibilityControls() {
	const highContrastCheckbox = document.getElementById('highContrast');
	const darkModeCheckbox = document.getElementById('darkMode');
	const accessibilityBtn = document.getElementById('accessibilityBtn');
	const increaseBtn = document.getElementById('increaseFont');
	const decreaseBtn = document.getElementById('decreaseFont');
	const resetBtn = document.getElementById('resetAccessibility');

	if (highContrastCheckbox) {
		// use change to catch keyboard toggles too
		highContrastCheckbox.addEventListener('change', () => {
			// ensure checkbox state is respected inside toggle
			toggleHighContrast();
		});
	}

	if (darkModeCheckbox) {
		darkModeCheckbox.addEventListener('change', () => {
			toggleDarkMode();
		});
	}

	if (accessibilityBtn) {
		accessibilityBtn.addEventListener('click', (e) => {
			e.stopPropagation(); // prevent the document click handler from closing immediately
			toggleAccessibility();
		});
		// keyboard accessibility
		accessibilityBtn.addEventListener('keydown', (e) => {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				e.stopPropagation();
				toggleAccessibility();
			}
		});
	}

	if (increaseBtn) {
		increaseBtn.addEventListener('click', (e) => {
			e.preventDefault();
			increaseFontSize();
		});
	}

	if (decreaseBtn) {
		decreaseBtn.addEventListener('click', (e) => {
			e.preventDefault();
			decreaseFontSize();
		});
	}

	if (resetBtn) {
		resetBtn.addEventListener('click', (e) => {
			e.preventDefault();
			resetAccessibility();
		});
	}
}

/* Footer slice scrolling helpers */
function initFooterSlice(){
	const slice = document.getElementById('footerSlice');
	const prev = document.getElementById('footerPrev');
	const next = document.getElementById('footerNext');
	if(!slice || !prev || !next) return;

	const getAmount = ()=> Math.min(Math.round(slice.clientWidth * 0.7), 360);

	prev.addEventListener('click', ()=>{
		slice.scrollBy({left: -getAmount(), behavior:'smooth'});
		// update after animation
		setTimeout(updateFooterButtons, 350);
	});

	next.addEventListener('click', ()=>{
		slice.scrollBy({left: getAmount(), behavior:'smooth'});
		setTimeout(updateFooterButtons, 350);
	});

	slice.addEventListener('scroll', throttle(updateFooterButtons, 120));

	function updateFooterButtons(){
		prev.disabled = slice.scrollLeft <= 4;
		next.disabled = slice.scrollLeft + slice.clientWidth >= slice.scrollWidth - 4;
	}

	function throttle(fn, wait){
		let last = 0;
		return function(...args){
			const now = Date.now();
			if(now - last > wait){ last = now; fn(...args); }
		}
	}

	// initial state
	updateFooterButtons();
	// also update on resize
	window.addEventListener('resize', throttle(updateFooterButtons, 200));
}

/* ============================
   Product panels: AJAX paging + smooth slide animation
   ============================ */
function waitTransitionEnd(el, timeout = 800) {
    return new Promise(resolve => {
        let finished = false;
        function done() {
            if (finished) return;
            finished = true;
            el.removeEventListener('transitionend', done);
            clearTimeout(timer);
            resolve();
        }
        const timer = setTimeout(() => { done(); }, timeout + 50);
        el.addEventListener('transitionend', done);
    });
}

function initProductPanels() {
    document.querySelectorAll('.products-panel').forEach(panel => {
        const content = panel.querySelector('.panel-content');
        const prevBtn = panel.querySelector('.btn-prev');
        const nextBtn = panel.querySelector('.btn-next');

        if (!content || !prevBtn || !nextBtn) return;

        function updateSideButtons() {
            const pagLinks = panel.querySelectorAll('.paginator-wrapper a');
            let prevUrl = null, nextUrl = null;
            pagLinks.forEach(a => {
                const rel = a.getAttribute('rel');
                if (rel === 'prev') prevUrl = a.href;
                if (rel === 'next') nextUrl = a.href;
            });
            prevBtn.style.display = prevUrl ? 'flex' : 'none';
            nextBtn.style.display = nextUrl ? 'flex' : 'none';
            prevBtn.dataset.targetUrl = prevUrl || '';
            nextBtn.dataset.targetUrl = nextUrl || '';
            prevBtn.disabled = !prevUrl;
            nextBtn.disabled = !nextUrl;
        }

        async function ajaxNavigate(url, direction = 'next') {
            if (!url) return;
            prevBtn.disabled = true;
            nextBtn.disabled = true;

            // Out animation
            content.classList.add('animating');
            content.classList.add(direction === 'next' ? 'slide-out-left' : 'slide-out-right');
            await waitTransitionEnd(content, 700);

            // Fetch HTML
            let text;
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                text = await res.text();
            } catch (e) {
                window.location.href = url; return;
            }

            const tmp = document.createElement('div');
            tmp.innerHTML = text;

            // Determine correct replacement panel-content in response
            let newContentEl = null;
            const categorySection = panel.closest('.category-section');
            if (categorySection) {
                const currentPanels = Array.from(document.querySelectorAll('.category-section .products-panel'));
                const idx = currentPanels.indexOf(panel);
                const responsePanels = Array.from(tmp.querySelectorAll('.category-section .products-panel'));
                if (responsePanels[idx]) newContentEl = responsePanels[idx].querySelector('.panel-content');
            } else {
                newContentEl = tmp.querySelector('.products-panel .panel-content');
            }

            if (!newContentEl) { window.location.href = url; return; }

            // Replace content and prepare entry animation
            content.innerHTML = newContentEl.innerHTML;
            content.classList.remove('slide-out-left', 'slide-out-right');

            // Set initial off-screen class
            content.classList.add(direction === 'next' ? 'slide-in-from-right' : 'slide-in-from-left');

            // Force reflow then animate to center
            void content.offsetWidth;
            content.classList.add('slide-to-center');

            await waitTransitionEnd(content, 700);

            // cleanup
            content.classList.remove('animating', 'slide-in-from-right', 'slide-in-from-left', 'slide-to-center');
            // rebind hidden paginator links inside replaced content so updateSideButtons can find new links if any
            bindPaginationLinks(panel);
            updateSideButtons();
        }

        function bindPaginationLinks(root) {
            const links = root.querySelectorAll('.paginator-wrapper a');
            links.forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.href;
                    const rel = this.getAttribute('rel');
                    ajaxNavigate(url, rel === 'prev' ? 'prev' : 'next');
                });
            });
        }

        // side buttons
        prevBtn.addEventListener('click', () => {
            const url = prevBtn.dataset.targetUrl;
            if (url) ajaxNavigate(url, 'prev');
        });
        nextBtn.addEventListener('click', () => {
            const url = nextBtn.dataset.targetUrl;
            if (url) ajaxNavigate(url, 'next');
        });

        bindPaginationLinks(panel);
        updateSideButtons();
    });
}

// Inicializar en DOMContentLoaded (ya hay otros listeners en este archivo, añadir otro no rompe)
document.addEventListener('DOMContentLoaded', function(){
    if (typeof initProductPanels === 'function') initProductPanels();
});

/* ============================
   CARRUSEL FIGMA - TRANSICIONES SUAVES
   ============================ */

window.initCarousel = function(id, products) {
    const track = document.querySelector(`.carousel-track[data-carousel="${id}"]`);
    const dots = document.querySelector(`.carousel-dots[data-carousel="${id}"]`);
    const prevBtn = document.querySelector(`.carousel-btn-prev[data-carousel="${id}"]`);
    const nextBtn = document.querySelector(`.carousel-btn-next[data-carousel="${id}"]`);

    if (!track || !products || products.length === 0) {
        console.log('Carrusel ' + id + ': no track o no products');
        return;
    }

    console.log('Inicializando carrusel: ' + id + ' con ' + products.length + ' productos');

    let current = 0;
    let perPage = getPerPage();
    let autoplay;
    let isAnimating = false;

    function getPerPage() {
        if (window.innerWidth < 640) return 1;
        if (window.innerWidth < 1024) return 2;
        return 3;
    }

    function totalSlides() {
        return Math.ceil(products.length / perPage);
    }

    function createCard(p) {
        return `
            <div class="product-card">
                <div class="product-image">
                    <img src="${p.image}" alt="${p.name}" loading="lazy">
                    <div class="product-price">$${Number(p.price).toLocaleString('es-CO')}</div>
                </div>
                <div class="product-info">
                    <span class="product-category">${p.category}</span>
                    <h3 class="product-name">${p.name}</h3>
                    <p class="product-description">${p.description}</p>
                    <a href="${p.url}" class="product-btn">Ver Más</a>
                </div>
            </div>
        `;
    }

    function render() {
        const start = current * perPage;
        const visible = products.slice(start, start + perPage);
        track.style.gridTemplateColumns = `repeat(${perPage}, 1fr)`;
        track.innerHTML = visible.map(createCard).join('');
        updateButtons();
    }

    function createDots() {
        if (!dots) return;
        dots.innerHTML = '';
        const total = totalSlides();
        if (total <= 1) return;
        for (let i = 0; i < total; i++) {
            const dot = document.createElement('button');
            dot.classList.add('dot');
            if (i === current) dot.classList.add('active');
            dot.onclick = () => { if (!isAnimating) goTo(i); };
            dots.appendChild(dot);
        }
    }

    function updateDots() {
        if (!dots) return;
        dots.querySelectorAll('.dot').forEach((d, i) => {
            d.classList.toggle('active', i === current);
        });
    }

    function updateButtons() {
        if (prevBtn) {
            prevBtn.disabled = current === 0;
            prevBtn.style.opacity = current === 0 ? '0.5' : '1';
        }
        if (nextBtn) {
            nextBtn.disabled = current >= totalSlides() - 1;
            nextBtn.style.opacity = current >= totalSlides() - 1 ? '0.5' : '1';
        }
    }

    function goTo(i, direction = 'next') {
        if (isAnimating || i === current) return;
        isAnimating = true;

        track.style.opacity = '0';
        track.style.transform = 'translateX(' + (direction === 'next' ? '-20px' : '20px') + ')';

        setTimeout(() => {
            current = i;
            render();
            updateDots();
            
            track.style.transform = 'translateX(' + (direction === 'next' ? '20px' : '-20px') + ')';
            
            setTimeout(() => {
                track.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                track.style.opacity = '1';
                track.style.transform = 'translateX(0)';
                
                setTimeout(() => {
                    isAnimating = false;
                }, 400);
            }, 50);
        }, 300);
    }

    function next() {
        if (isAnimating) return;
        if (current < totalSlides() - 1) {
            goTo(current + 1, 'next');
        } else {
            goTo(0, 'next');
        }
    }

    function prev() {
        if (isAnimating) return;
        if (current > 0) {
            goTo(current - 1, 'prev');
        } else {
            goTo(totalSlides() - 1, 'prev');
        }
    }

    function startAutoplay() {
        stopAutoplay();
        if (totalSlides() > 1) {
            autoplay = setInterval(next, 5000);
        }
    }

    function stopAutoplay() {
        if (autoplay) {
            clearInterval(autoplay);
            autoplay = null;
        }
    }

    // Event Listeners
    if (prevBtn) {
        prevBtn.onclick = function(e) {
            e.preventDefault();
            if (isAnimating) return;
            stopAutoplay();
            prev();
            startAutoplay();
        };
    }

    if (nextBtn) {
        nextBtn.onclick = function(e) {
            e.preventDefault();
            if (isAnimating) return;
            stopAutoplay();
            next();
            startAutoplay();
        };
    }

    // Responsive
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const newPerPage = getPerPage();
            if (newPerPage !== perPage) {
                perPage = newPerPage;
                current = 0;
                render();
                createDots();
            }
        }, 150);
    });

    // Pausar en hover
    const wrapper = track.closest('.carousel-wrapper');
    if (wrapper) {
        wrapper.addEventListener('mouseenter', stopAutoplay);
        wrapper.addEventListener('mouseleave', startAutoplay);
    }

    // Inicializar
    track.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    render();
    createDots();
    startAutoplay();
};

// También inicializar al cargar si ya hay datos
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof window.carouselData !== 'undefined') {
            Object.keys(window.carouselData).forEach(function(carouselId) {
                // Solo inicializar si no fue inicializado ya
                const track = document.querySelector(`.carousel-track[data-carousel="${carouselId}"]`);
                if (track && !track.dataset.initialized) {
                    track.dataset.initialized = 'true';
                    window.initCarousel(carouselId, window.carouselData[carouselId]);
                }
            });
        }
    }, 200);
});
