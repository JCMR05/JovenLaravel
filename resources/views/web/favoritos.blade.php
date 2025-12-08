@extends('web.app')

@section('contenido')
<section class="favoritos-section">
    <div class="container">
        {{-- Header --}}
        <div class="favoritos-header">
            <div class="favoritos-title-wrapper">
                <div class="favoritos-icon-circle">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div>
                    <h1 class="favoritos-title">Mis Favoritos</h1>
                    <p class="favoritos-subtitle">{{ $favoritos->count() }} {{ $favoritos->count() == 1 ? 'producto guardado' : 'productos guardados' }}</p>
                </div>
            </div>
            
            @if($favoritos->count() > 0)
                <button type="button" class="btn-limpiar-favoritos" onclick="limpiarFavoritos()">
                    <i class="bi bi-trash3"></i>
                    Limpiar todo
                </button>
            @endif
        </div>

        @if($favoritos->count() > 0)
            {{-- Grid de productos favoritos --}}
            <div class="favoritos-grid">
                @foreach($favoritos as $producto)
                    <div class="favorito-card" id="favorito-{{ $producto->id }}">
                        <div class="favorito-image-container">
                            <a href="{{ route('web.show', $producto->id) }}">
                                @if($producto->imagen)
                                    <img src="{{ asset('uploads/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="favorito-image">
                                @else
                                    <img src="https://via.placeholder.com/300x200?text={{ urlencode($producto->nombre) }}" alt="{{ $producto->nombre }}" class="favorito-image">
                                @endif
                            </a>
                            
                            <button type="button" class="btn-remove-favorito" onclick="eliminarFavorito({{ $producto->id }})" title="Eliminar de favoritos">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            @if($producto->destacado)
                                <span class="badge-destacado">
                                    <i class="bi bi-star-fill"></i> Destacado
                                </span>
                            @endif
                        </div>
                        
                        <div class="favorito-info">
                            @if($producto->categorias->count() > 0)
                                <span class="favorito-categoria">{{ $producto->categorias->first()->nombre }}</span>
                            @endif
                            
                            <h3 class="favorito-nombre">
                                <a href="{{ route('web.show', $producto->id) }}">{{ $producto->nombre }}</a>
                            </h3>
                            
                            <p class="favorito-descripcion">{{ Str::limit($producto->descripcion, 80) }}</p>
                            
                            <div class="favorito-footer">
                                <span class="favorito-precio">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                                
                                <form action="{{ route('carrito.agregar') }}" method="POST" class="favorito-form-carrito">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="btn-agregar-carrito">
                                        <i class="bi bi-cart-plus"></i>
                                        Agregar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Estado vacío --}}
            <div class="favoritos-empty">
                <div class="empty-icon-circle">
                    <i class="bi bi-heart"></i>
                </div>
                <h2>No tienes favoritos aún</h2>
                <p>Explora nuestra tienda y guarda los productos que más te gusten</p>
                <a href="{{ route('web.tienda') }}" class="btn-explorar">
                    <i class="bi bi-shop"></i>
                    Explorar Productos
                </a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
// Token CSRF para peticiones AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

function eliminarFavorito(productoId) {
    if (!confirm('¿Estás seguro de eliminar este producto de favoritos?')) return;
    
    fetch(`/favoritos/${productoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById(`favorito-${productoId}`);
            if (card) {
                card.style.transform = 'scale(0.8)';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.remove();
                    // Si no quedan favoritos, recargar para mostrar estado vacío
                    if (document.querySelectorAll('.favorito-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            mostrarToast(data.mensaje, 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarToast('Error al eliminar de favoritos', 'error');
    });
}

function limpiarFavoritos() {
    if (!confirm('¿Estás seguro de eliminar todos tus favoritos?')) return;
    
    fetch('/favoritos-limpiar', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarToast('Error al limpiar favoritos', 'error');
    });
}

function mostrarToast(mensaje, tipo = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${tipo}`;
    toast.innerHTML = `
        <i class="bi bi-${tipo === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'}"></i>
        <span>${mensaje}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush
@endsection