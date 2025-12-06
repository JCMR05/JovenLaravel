@extends('web.app')
@section('header')
@endsection
@section('contenido')

{{-- Barra de búsqueda y filtros --}}
<form method="GET" action="{{route('web.index')}}">
    <div class="container px-4 px-lg-5 mt-4">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar productos..."
                        aria-label="Buscar productos" name="search" value="{{request('search')}}">
                    <button class="btn btn-outline-dark" type="button" id="filterButton" data-bs-toggle="collapse"
                        data-bs-target="#filterOptions" aria-expanded="false" aria-controls="filterOptions">
                        <i class="bi bi-funnel"></i> Filtros
                    </button>
                    <button class="btn btn-outline-dark" type="submit" id="searchButton">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group">
                    <label class="input-group-text" for="sortSelect">Ordenar por:</label>
                    <select class="form-select" id="sortSelect" name="sort">
                        <option value="priceAsc" {{ request('sort') == 'priceAsc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="priceDesc" {{ request('sort') == 'priceDesc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="container px-4 px-lg-5">
        <div class="collapse mt-2" id="filterOptions">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-2"><strong>Categorías</strong></p>
                        <div class="d-flex flex-wrap">
                            @foreach($categoriasFiltro ?? collect() as $catOption)
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" value="{{ $catOption->id }}" id="cat-{{ $catOption->id }}" name="categories[]" {{ in_array($catOption->id, (array) request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat-{{ $catOption->id }}">{{ $catOption->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <div>
                            <button type="submit" class="btn btn-primary me-2">Aplicar filtros</button>
                            <a href="{{ route('web.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Sección de Productos --}}
<div id="productos">
    @if(!empty($productos) || request('search'))
        {{-- RESULTADOS DE BÚSQUEDA --}}
        @php
            $productos = $productos ?? collect();
        @endphp
        
        <div class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-header">
                    <h2>Resultados de búsqueda</h2>
                    <p>Mostrando resultados para: "{{ request('search') }}"</p>
                </div>

                @if($productos->count() > 0)
                <div class="carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev" data-carousel="search" aria-label="Anterior">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="carousel-btn carousel-btn-next" data-carousel="search" aria-label="Siguiente">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                    <div class="carousel-content">
                        <div class="carousel-track" data-carousel="search"></div>
                    </div>
                </div>
                <div class="carousel-dots" data-carousel="search"></div>
                @else
                <p class="text-center text-muted">No se encontraron productos.</p>
                @endif
            </div>
        </div>

        <script>
            window.carouselData = window.carouselData || {};
            window.carouselData['search'] = [
                @foreach($productos as $producto)
                {
                    id: {{ $producto->id }},
                    name: "{{ addslashes($producto->nombre) }}",
                    category: "{{ $producto->categorias->first()->nombre ?? 'General' }}",
                    price: {{ $producto->precio }},
                    description: "{{ addslashes(Str::limit($producto->descripcion ?? 'Delicioso producto artesanal', 80)) }}",
                    image: "{{ $producto->imagen && filter_var($producto->imagen, FILTER_VALIDATE_URL) ? $producto->imagen : asset('uploads/productos/' . $producto->imagen) }}",
                    url: "{{ route('web.show', $producto->id) }}"
                },
                @endforeach
            ];
        </script>

    @else
        {{-- VISTA POR CATEGORÍAS CON CARRUSEL FIGMA --}}
        @foreach($categorias as $categoria)
            @if($categoria->productos->count() > 0)
            <div class="carousel-section" style="background: {{ $loop->even ? '#fff9f0' : 'linear-gradient(to bottom, #ffffff, #fef3c7)' }};">
                <div class="carousel-container">
                    <div class="carousel-header">
                        <h2>{{ $categoria->nombre }}</h2>
                        <p>Descubre nuestra selección de {{ strtolower($categoria->nombre) }} elaborados artesanalmente.</p>
                    </div>

                    <div class="carousel-wrapper">
                        <button class="carousel-btn carousel-btn-prev" data-carousel="cat-{{ $categoria->id }}" aria-label="Anterior">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button class="carousel-btn carousel-btn-next" data-carousel="cat-{{ $categoria->id }}" aria-label="Siguiente">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                        <div class="carousel-content">
                            <div class="carousel-track" data-carousel="cat-{{ $categoria->id }}"></div>
                        </div>
                    </div>
                    <div class="carousel-dots" data-carousel="cat-{{ $categoria->id }}"></div>
                </div>
            </div>

            <script>
                window.carouselData = window.carouselData || {};
                window.carouselData['cat-{{ $categoria->id }}'] = [
                    @foreach($categoria->productos as $producto)
                    {
                        id: {{ $producto->id }},
                        name: "{{ addslashes($producto->nombre) }}",
                        category: "{{ $categoria->nombre }}",
                        price: {{ $producto->precio }},
                        description: "{{ addslashes(Str::limit($producto->descripcion ?? 'Delicioso producto artesanal', 80)) }}",
                        image: "{{ asset('uploads/productos/' . $producto->imagen) }}",
                        url: "{{ route('web.show', $producto->id) }}"
                    },
                    @endforeach
                ];
            </script>
            @endif
        @endforeach
    @endif
</div>

{{-- About Section --}}
<section class="about-section" id="sobre-nosotros">
    <div class="container">
        <div class="section-header text-center mb-4">
            <h2 class="section-title">Nuestra Historia</h2>
            <p class="section-subtitle">Somos una panadería familiar dedicada a mantener viva la tradición del pan artesanal,
                combinando recetas tradicionales con las mejores técnicas modernas.</p>
        </div>
        <div class="features-grid row">
            <div class="feature-card col-md-4 mb-3 text-center">
                <div class="feature-icon mb-2"><i class="fas fa-clock fa-2x"></i></div>
                <h3>Horneado Diario</h3>
                <p>Todos nuestros productos son elaborados frescos cada mañana</p>
            </div>
            <div class="feature-card col-md-4 mb-3 text-center">
                <div class="feature-icon mb-2"><i class="fas fa-heart fa-2x"></i></div>
                <h3>Ingredientes Naturales</h3>
                <p>Utilizamos solo ingredientes de la más alta calidad, sin conservantes</p>
            </div>
            <div class="feature-card col-md-4 mb-3 text-center">
                <div class="feature-icon mb-2"><i class="fas fa-award fa-2x"></i></div>
                <h3>Tradición Artesanal</h3>
                <p>Más de 30 años de experiencia en el arte de la panadería</p>
            </div>
        </div>
    </div>
</section>

<link href="{{ asset('css/styles.css') }}?v={{ time() }}" rel="stylesheet">
<script src="{{ asset('js/scripts.js') }}?v={{ time() }}"></script>
@endsection


