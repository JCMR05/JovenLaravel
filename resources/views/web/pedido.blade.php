@extends('web.app')
@section('contenido')
<!-- Carrito Section -->
<section class="perfil-page-wrapper py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="cart-header-section mb-5">
            <h1 class="fw-bold mb-2">Mi Carrito</h1>
            <p class="lead text-muted" id="cartCount">
                @if(count($carrito) > 0)
                    {{ count($carrito) }} artículos en tu carrito
                @else
                    Tu carrito está vacío
                @endif
            </p>
        </div>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                @if (session('mensaje'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('mensaje') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @forelse($carrito as $id => $item)
                <div class="cart-item-card mb-3">
                    <div class="cart-item-wrapper">
                        <div class="cart-item-image">
                            <img src="{{ asset('uploads/productos/' . $item['imagen']) }}" 
                                alt="{{ $item['nombre'] }}" class="img-fluid">
                        </div>

                        <div class="cart-item-content">
                            <h5 class="cart-item-name mb-1">{{ $item['nombre'] }}</h5>
                            <p class="cart-item-code text-muted mb-0">{{ $item['codigo'] }}</p>
                        </div>

                        <div class="cart-item-controls">
                            <div class="quantity-selector">
                                <a href="{{ route('carrito.restar', ['producto_id' => $id]) }}" 
                                    class="quantity-btn btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <span class="quantity-value">{{ $item['cantidad'] }}</span>
                                <a href="{{ route('carrito.sumar', ['producto_id' => $id]) }}" 
                                    class="quantity-btn btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>

                        <div class="cart-item-price">
                            <div class="price-info">
                                <p class="price-per-unit">${{ number_format($item['precio'], 2) }}</p>
                                <p class="price-subtotal fw-bold">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
                            </div>
                        </div>

                        <div class="cart-item-actions">
                            <a href="{{ route('carrito.eliminar', $id) }}" 
                                class="btn btn-sm btn-outline-danger" title="Eliminar del carrito">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-cart-state">
                    <div class="empty-content">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Tu carrito está vacío</h3>
                        <p>No tienes productos en tu carrito. ¡Empieza a comprar!</p>
                        <a href="/" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Continuar comprando
                        </a>
                    </div>
                </div>
                @endforelse

                @if (count($carrito) > 0)
                <div class="cart-actions mt-4">
                    <a href="/" class="btn perfil-btn perfil-btn-logout">
                        <i class="fas fa-arrow-left me-2"></i>Continuar comprando
                    </a>
                    <a href="{{ route('carrito.vaciar') }}" class="btn perfil-btn perfil-btn-favorites">
                        <i class="fas fa-trash me-2"></i>Vaciar carrito
                    </a>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary-card">
                    <h4 class="summary-title mb-4">Resumen del Pedido</h4>

                    @php
                        $total = 0;
                        $subtotal = 0;
                        $tax = 0;
                        foreach ($carrito as $item) {
                            $subtotal += $item['precio'] * $item['cantidad'];
                        }
                        $tax = $subtotal * 0.1; // 10% tax
                        $total = $subtotal + $tax;
                    @endphp

                    <div class="summary-section">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span class="summary-value">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Impuestos (10%)</span>
                            <span class="summary-value">${{ number_format($tax, 2) }}</span>
                        </div>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total">
                        <span class="total-label">Total</span>
                        <span class="total-amount">${{ number_format($total, 2) }}</span>
                    </div>

                    <div class="summary-actions">
                        @if (Auth::check() && count($carrito) > 0)
                            <form action="{{ route('pedido.realizar') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check-circle me-2"></i>Realizar Pedido
                                </button>
                            </form>
                        @elseif(!Auth::check() && count($carrito) > 0)
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                            <p class="text-center text-muted mt-3" style="font-size: 0.875rem;">
                                Necesitas iniciar sesión para completar tu pedido
                            </p>
                        @else
                            <a href="/" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-bag me-2"></i>Selecciona Productos
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection