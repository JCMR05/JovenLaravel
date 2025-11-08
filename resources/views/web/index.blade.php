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
</form>
<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-1">
        @foreach($categorias as $categoria)
            <div class="mb-4">
                <h3 class="section-title">{{ $categoria->nombre }}</h3>
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

                {{-- Paginación de productos para esta categoría.
                     Conserva los demás parámetros de la request excepto la propia página de esta categoría. --}}
                <div class="d-flex justify-content-center">
                    {{ $categoria->productos->appends(request()->except('page_cat_'.$categoria->id))->links() }}
                </div>
            </div>
        @endforeach

        {{-- Paginación de categorías (5 por página) --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $categorias->appends(request()->except('page'))->links() }}
        </div>
    </div>
</section>
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
