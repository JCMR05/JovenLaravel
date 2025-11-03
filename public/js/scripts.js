// Scripts merged from the design's script.js
// Products Data
const products = [
	{
		id: 1,
		name: "Pan Artesanal",
		description: "Pan rústico con masa madre natural",
		price: 4.50,
		image: "https://images.unsplash.com/photo-1627308593341-d886acdc06a2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc2FuJTIwYnJlYWQlMjBiYWtlcnl8ZW58MXx8fHwxNzYxNjg1ODMzfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
		category: "Panes",
		isNew: true
	},
	{
		id: 2,
		name: "Croissants",
		description: "Croissants de mantequilla recién horneados",
		price: 2.80,
		image: "https://images.unsplash.com/photo-1654923064797-26af6b093027?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmcmVzaCUyMGNyb2lzc2FudHMlMjBwYXN0cnl8ZW58MXx8fHwxNzYxNzQ0NDMzfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
		category: "Bollería",
		isNew: false
	},
	{
		id: 3,
		name: "Pan de Masa Madre",
		description: "Pan tradicional con fermentación lenta",
		price: 5.20,
		image: "https://images.unsplash.com/photo-1597604391235-a7429b4b350c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzb3VyZG91Z2glMjBicmVhZHxlbnwxfHx8fDE3NjE3Mjg0MzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
		category: "Panes",
		isNew: true
	},
	{
		id: 4,
		name: "Tarta de Frutas",
		description: "Deliciosa tarta con frutas de temporada",
		price: 18.50,
		image: "https://images.unsplash.com/photo-1706463996554-6c6318946b3f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjYWtlJTIwcGFzdHJ5JTIwZGVzc2VydHxlbnwxfHx8fDE3NjE3ODE1MDR8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
		category: "Repostería",
		isNew: false
	},
	{
		id: 5,
		name: "Baguette",
		description: "Baguette francesa tradicional",
		price: 3.20,
		image: "https://images.unsplash.com/photo-1686233964668-45a34531b750?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYWd1ZXR0ZSUyMGZyZW5jaCUyMGJyZWFkfGVufDF8fHx8MTc2MTc4MTUwNHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
		category: "Panes",
		isNew: false
	},
	{
		id: 6,
		name: "Panecillos Integrales",
		description: "Pack de 6 panecillos de harina integral",
		price: 4.00,
		image: "https://images.unsplash.com/photo-1627308593341-d886acdc06a2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc2FuJTIwYnJlYWQlMjBiYWtlcnl8ZW58MXx8fHwxNzYxNjg1ODMzfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
		category: "Panes",
		isNew: false
	}
];

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

// Scroll to Products
function scrollToProducts() {
	const el = document.getElementById('productos');
	if (el) el.scrollIntoView({ behavior: 'smooth' });
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
