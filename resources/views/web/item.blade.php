@extends('web.app')
@section('contenido')

<section class="product-detail-section">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav class="breadcrumb-figma">
            <a href="{{ route('home') }}">Inicio</a>
            <span class="separator">/</span>
            <a href="{{ route('web.tienda') }}">Productos</a>
            <span class="separator">/</span>
            @if($producto->categorias->count() > 0)
                <a href="{{ route('web.tienda', ['categoria' => $producto->categorias->first()->id]) }}">{{ $producto->categorias->first()->nombre }}</a>
                <span class="separator">/</span>
            @endif
            <span class="current">{{ $producto->nombre }}</span>
        </nav>

        <div class="product-detail-grid">
            {{-- Imagen del producto --}}
            <div class="product-image-detail">
                <div class="image-wrapper">
                    @if($producto->imagen)
                        <img src="{{ asset('uploads/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                    @else
                        <img src="https://via.placeholder.com/600x500?text={{ urlencode($producto->nombre) }}" alt="{{ $producto->nombre }}">
                    @endif
                    
                    @if($producto->destacado)
                        <span class="badge-destacado-detail">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            Destacado
                        </span>
                    @endif
                </div>
            </div>

            {{-- Información del producto --}}
            <div class="product-info-detail">
                @if($producto->categorias->count() > 0)
                    <span class="product-category-detail">{{ $producto->categorias->first()->nombre }}</span>
                @endif

                <h1 class="product-name-detail">{{ $producto->nombre }}</h1>

                <div class="product-price-detail">
                    ${{ number_format($producto->precio, 0, ',', '.') }}
                </div>

                <p class="product-description-detail">{{ $producto->descripcion }}</p>

                <div class="product-meta">
                    <div class="meta-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span>SKU: {{ $producto->codigo }}</span>
                    </div>
                    <div class="meta-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Disponible para entrega</span>
                    </div>
                </div>

                @if (session('mensaje'))
                    <div class="alert-success-figma">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        {{ session('mensaje') }}
                    </div>
                @endif

                {{-- Formulario de agregar al carrito --}}
                <form action="{{ route('carrito.agregar') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">

                    <div class="quantity-selector">
                        <label>Cantidad:</label>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" data-action="decrease">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                            <input type="number" name="cantidad" id="inputQuantity" value="1" min="1" class="qty-input">
                            <button type="button" class="qty-btn" data-action="increase">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn-add-cart">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Agregar al Carrito
                        </button>
                        <a href="{{ route('web.tienda') }}" class="btn-back">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Seguir Comprando
                        </a>
                    </div>
                </form>

                {{-- Características --}}
                <div class="product-features">
                    <div class="feature">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </div>
                        <div>
                            <h4>Envío Rápido</h4>
                            <p>Entrega en 24-48 horas</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4>100% Natural</h4>
                            <p>Sin conservantes</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <h4>Recogida en Tienda</h4>
                            <p>Disponible hoy</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    
    e.preventDefault();
    const action = btn.getAttribute('data-action');
    const input = document.getElementById('inputQuantity');
    if (!input) return;
    
    let value = parseInt(input.value) || 1;
    if (action === 'increase') {
        value++;
    } else if (action === 'decrease') {
        value = Math.max(1, value - 1);
    }
    input.value = value;
});

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('inputQuantity');
    if (!input) return;
    
    input.addEventListener('input', function() {
        if (this.value === '' || isNaN(this.value) || parseInt(this.value) < 1) {
            this.value = 1;
        } else {
            this.value = parseInt(this.value);
        }
    });
});
</script>
@endpush

@endsection