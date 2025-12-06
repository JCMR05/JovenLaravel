@extends('web.app')
@section('contenido')

{{-- Hero de la tienda --}}
<div class="tienda-hero-figma">
    <div class="container">
        <h1>Nuestros Productos</h1>
        <p>Descubre toda nuestra variedad de productos artesanales, hechos con amor y tradición</p>
    </div>
</div>

<section class="tienda-section-figma">
    <div class="container">
        {{-- Filtros estilo Figma --}}
        <div class="filtros-figma-box">
            {{-- Fila 1: Buscador y Ordenar --}}
            <form action="{{ route('web.tienda') }}" method="GET" id="searchForm" class="filtros-row-figma">
                <div class="search-box-figma">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" name="search" placeholder="Buscar productos..." 
                           value="{{ $search ?? '' }}" class="search-input">
                </div>

                @if($categoriaId)
                    <input type="hidden" name="categoria" value="{{ $categoriaId }}">
                @endif

                <div class="sort-box-figma">
                    <svg class="sort-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M7 15l5 5 5-5M7 9l5-5 5 5"></path>
                    </svg>
                    <select name="sort" class="sort-select" id="sortSelect">
                        <option value="newest" {{ ($sort ?? 'newest') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="nameAsc" {{ ($sort ?? '') == 'nameAsc' ? 'selected' : '' }}>Ordenar: A-Z</option>
                        <option value="nameDesc" {{ ($sort ?? '') == 'nameDesc' ? 'selected' : '' }}>Ordenar: Z-A</option>
                        <option value="priceAsc" {{ ($sort ?? '') == 'priceAsc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="priceDesc" {{ ($sort ?? '') == 'priceDesc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    </select>
                </div>
            </form>

            {{-- Separador --}}
            <div class="filtros-separator"></div>

            {{-- Fila 2: Categorías --}}
            <div class="categorias-row">
                <div class="categorias-label">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="21" x2="4" y2="14"></line>
                        <line x1="4" y1="10" x2="4" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12" y2="3"></line>
                        <line x1="20" y1="21" x2="20" y2="16"></line>
                        <line x1="20" y1="12" x2="20" y2="3"></line>
                        <line x1="1" y1="14" x2="7" y2="14"></line>
                        <line x1="9" y1="8" x2="15" y2="8"></line>
                        <line x1="17" y1="16" x2="23" y2="16"></line>
                    </svg>
                    <span>Categorías</span>
                </div>

                <div class="categorias-pills">
                    <a href="{{ route('web.tienda', ['search' => $search, 'sort' => $sort]) }}" 
                       class="cat-pill {{ empty($categoriaId) ? 'active' : '' }}">
                        Todos
                    </a>
                    @foreach($categorias as $cat)
                        <a href="{{ route('web.tienda', ['categoria' => $cat->id, 'search' => $search, 'sort' => $sort]) }}" 
                           class="cat-pill {{ ($categoriaId ?? '') == $cat->id ? 'active' : '' }}">
                            {{ $cat->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Contador de productos --}}
        <div class="productos-count">
            Mostrando <span>{{ $productos->total() }}</span> productos
            @if($search || $categoriaId)
                <a href="{{ route('web.tienda') }}" class="limpiar-filtros">× Limpiar filtros</a>
            @endif
        </div>

        {{-- Grid de productos --}}
        @if($productos->count() > 0)
            <div class="productos-grid-tienda">
                @foreach($productos as $producto)
                    <div class="product-card-tienda">
                        <div class="product-image-tienda">
                            @if($producto->imagen)
                                <img src="{{ asset('uploads/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                            @else
                                <img src="https://via.placeholder.com/300x250?text={{ urlencode($producto->nombre) }}" alt="{{ $producto->nombre }}">
                            @endif
                            
                            @if($producto->destacado)
                                <span class="badge-destacado">Destacado</span>
                            @endif
                            
                            <span class="product-price-badge">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                        </div>
                        <div class="product-info-tienda">
                            @if($producto->categorias->count() > 0)
                                <span class="product-category-tienda">{{ $producto->categorias->first()->nombre }}</span>
                            @endif
                            <h3 class="product-name-tienda">{{ $producto->nombre }}</h3>
                            <p class="product-desc-tienda">{{ Str::limit($producto->descripcion, 70) }}</p>
                            <a href="{{ route('web.show', $producto->id) }}" class="product-btn-tienda">Ver Más</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="pagination-wrapper-figma">
                {{ $productos->appends(request()->query())->links() }}
            </div>
        @else
            <div class="no-products-figma">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <h3>No se encontraron productos</h3>
                <p>Intenta con otros términos de búsqueda o cambia los filtros.</p>
                <a href="{{ route('web.tienda') }}" class="btn-large-figma">Ver todos los productos</a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
document.getElementById('sortSelect').addEventListener('change', function() {
    document.getElementById('searchForm').submit();
});

document.querySelector('.search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchForm').submit();
    }
});
</script>
@endpush

@endsection