@php
    $perPage = 3;
    $totalItems = $items->count();
    $totalPages = ceil($totalItems / $perPage);
    $firstPageItems = $items->take($perPage);
@endphp

<div class="carousel-wrapper">
    @if($totalPages > 1)
    <button class="carousel-btn carousel-btn-prev" data-carousel="{{ $carouselId }}" aria-label="Anterior" disabled>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <button class="carousel-btn carousel-btn-next" data-carousel="{{ $carouselId }}" aria-label="Siguiente">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
    @endif

    <div class="carousel-content">
        <div class="carousel-track" data-carousel="{{ $carouselId }}">
            {{-- Renderizar primeras tarjetas directamente en HTML --}}
            @foreach($firstPageItems as $producto)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ asset('uploads/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" loading="lazy">
                    <div class="product-price">${{ number_format($producto->precio, 0, ',', '.') }}</div>
                </div>
                <div class="product-info">
                    <span class="product-category">{{ $categoryName ?? ($producto->categorias->first()->nombre ?? 'General') }}</span>
                    <h3 class="product-name">{{ $producto->nombre }}</h3>
                    <p class="product-description">{{ Str::limit($producto->descripcion ?? 'Delicioso producto artesanal', 80) }}</p>
                    <a href="{{ route('web.show', $producto->id) }}" class="product-btn">Ver Más</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($totalPages > 1)
<div class="carousel-dots" data-carousel="{{ $carouselId }}">
    @for($i = 0; $i < $totalPages; $i++)
    <button class="dot {{ $i === 0 ? 'active' : '' }}" data-page="{{ $i }}"></button>
    @endfor
</div>

{{-- Datos para JavaScript (para navegación) --}}
<script>
    window.carouselData = window.carouselData || {};
    window.carouselData['{{ $carouselId }}'] = [
        @foreach($items as $producto)
        {
            id: {{ $producto->id }},
            name: "{{ addslashes($producto->nombre) }}",
            category: "{{ $categoryName ?? ($producto->categorias->first()->nombre ?? 'General') }}",
            price: {{ $producto->precio }},
            description: "{{ addslashes(Str::limit($producto->descripcion ?? 'Delicioso producto artesanal', 80)) }}",
            image: "{{ asset('uploads/productos/' . $producto->imagen) }}",
            url: "{{ route('web.show', $producto->id) }}"
        },
        @endforeach
    ];
</script>
@endif