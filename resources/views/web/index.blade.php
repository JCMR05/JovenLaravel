@extends('web.app')
@section('header')
@endsection
@section('contenido')
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
                        <option value="priceAsc" {{ request('sort') == 'priceAsc' ? 'selected' : '' }}>Precio: menor a
                            mayor</option>
                        <option value="priceDesc" {{ request('sort') == 'priceDesc' ? 'selected' : '' }}>Precio: mayor a
                            menor</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- Collapse: filter options -->
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
                            <a href="{{ route('web.index', array_merge(request()->except('categories', 'page'), ['categories' => []])) }}" class="btn btn-outline-secondary">Limpiar filtros</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Section: si hay búsqueda mostrar solo productos -->
<section class="py-5">
    <div class="container-fluid px-lg-5 mt-1">
        @if(!empty($productos) || request('search'))
            @php
                $productos = $productos ?? (isset($productos) ? $productos : \App\Models\Producto::where('nombre','like','%'.request('search').'%')->paginate(8));
            @endphp

            <!-- Panel único para búsqueda -->
            <div class="search-results" style="background: #ffffffff; border-radius: 1rem; padding: 2rem;">
                <h3 class="mb-4">Resultados de búsqueda: "{{ request('search') }}"</h3>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    @forelse($productos as $producto)
                        <div class="col mb-5">
                            <div class="card h-100">
                                <img class="card-img-top" src="{{ $producto->imagen && filter_var($producto->imagen, FILTER_VALIDATE_URL) ? $producto->imagen : asset('uploads/productos/'. $producto->imagen) }}" alt="{{ $producto->nombre }}" />
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <h5 class="fw-bolder">{{ $producto->nombre }}</h5>
                                        $ {{ number_format($producto->precio, 2) }}
                                    </div>
                                </div>
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center">
                                        <a class="btn btn-outline-dark mt-auto" href="{{ route('web.show', $producto->id) }}">Ver producto</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p>No se encontraron productos.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $productos->links() }}
                </div>
            </div>

        @else
            {{-- Vista por categorías (sin búsqueda) --}}
             @foreach($categorias as $categoria)
                <!-- Panel para cada categoría -->
                <div class="category-section mb-5">
                    <h3 class="section-title mb-4">{{ $categoria->nombre }}</h3>
                    <div class="category-products" style="background: #ece6e6; border-radius: 1rem; padding: 2rem;">
                        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                            @forelse($categoria->productos as $producto)
                                <div class="col mb-5">
                                    <div class="card h-100">
                                        <img class="card-img-top" src="{{ asset('uploads/productos/'. $producto->imagen) }}" alt="{{ $producto->nombre }}" />
                                        <div class="card-body p-4">
                                            <div class="text-center">
                                                <h5 class="fw-bolder">{{ $producto->nombre }}</h5>
                                                $ {{ number_format($producto->precio, 2) }}
                                            </div>
                                        </div>
                                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                            <div class="text-center">
                                                <a class="btn btn-outline-dark mt-auto" href="{{ route('web.show', $producto->id) }}">Ver producto</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p>No hay productos en esta categoría.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            {{ $categoria->productos->appends(request()->except('page_cat_'.$categoria->id))->links() }}
                        </div>
                    </div>
                </div>
             @endforeach
        @endif
    </div>
</section>

<!-- Estilos y scripts para transición y paginación lateral -->
@push('styles')
<!-- removed: moved styles to public/css/styles.css -->
@endpush

<!-- Cargar CSS centralizado -->
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

<!-- Se eliminó el JS del carrusel (scripts.js) -->
    <!-- About Section (moved from index.html) -->
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
@endsection


