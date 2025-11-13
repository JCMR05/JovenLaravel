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
            <div class="products-panel position-relative" style="background: #ece6e6; border-radius: 1rem; padding: 2rem; display: flex; align-items: center; gap: 2rem;">
                
                <!-- Botón Anterior -->
                <button class="btn-side btn-prev btn btn-outline-dark" 
                        style="flex-shrink: 0; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; display: none; cursor: pointer;">
                    &#10094;
                </button>

                <!-- Contenedor de productos -->
                <div class="panel-content flex-grow-1" style="min-width: 0;">
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

                    <!-- Paginador oculto (solo para extraer URLs) -->
                    <div class="paginator-wrapper d-none">
                        {{ $productos->links() }}
                    </div>
                </div> <!-- .panel-content -->

                <!-- Botón Siguiente -->
                <button class="btn-side btn-next btn btn-outline-dark" 
                        style="flex-shrink: 0; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; display: none; cursor: pointer;">
                    &#10095;
                </button>
            </div> <!-- .products-panel -->

        @else
            {{-- Vista por categorías (sin búsqueda) --}}
            @foreach($categorias as $categoria)
                <!-- Panel para cada categoría -->
                <div class="category-section mb-5">
                    <h3 class="section-title mb-4">{{ $categoria->nombre }}</h3>
                    
                    <div class="products-panel position-relative" style="background: #ece6e6; border-radius: 1rem; padding: 2rem; display: flex; align-items: center; gap: 2rem;">
                        
                        <!-- Botón Anterior por categoría -->
                        <button class="btn-side btn-prev btn btn-outline-dark" 
                                style="flex-shrink: 0; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; display: none; cursor: pointer;">
                            &#10094;
                        </button>

                        <!-- Contenedor de productos por categoría -->
                        <div class="panel-content flex-grow-1" style="min-width: 0;">
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

                            <!-- Paginador oculto para cada categoría -->
                            <div class="paginator-wrapper d-none">
                                {{ $categoria->productos->appends(request()->except('page_cat_'.$categoria->id))->links() }}
                            </div>
                        </div> <!-- .panel-content -->

                        <!-- Botón Siguiente por categoría -->
                        <button class="btn-side btn-next btn btn-outline-dark" 
                                style="flex-shrink: 0; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; display: none; cursor: pointer;">
                            &#10095;
                        </button>
                    </div> <!-- .products-panel -->
                </div> <!-- .category-section -->
            @endforeach
        @endif
    </div>
</section>

<!-- Estilos y scripts para transición y paginación lateral -->
@push('styles')
<style>
.products-panel { 
    overflow: visible; 
    position: relative;
}
.panel-content { 
    transition: transform 400ms ease, opacity 400ms ease; 
    overflow: hidden;
}
.panel-content.slide-out-left { 
    transform: translateX(-30px); 
    opacity: 0; 
}
.panel-content.slide-in-right { 
    transform: translateX(30px); 
    opacity: 0; 
}
.panel-content.slide-in { 
    transform: translateX(0); 
    opacity: 1; 
}
.btn-side { 
    transition: all 300ms ease; 
}
.btn-side:hover {
    background-color: #e9ecef;
    transform: scale(1.1);
}
.btn-side:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.paginator-wrapper .pagination { 
    margin: 0; 
}
</style>
@endpush

@push('scripts')
<script>
(function(){
    function initPanel(panel){
        const content = panel.querySelector('.panel-content');
        const prevBtn = panel.querySelector('.btn-prev');
        const nextBtn = panel.querySelector('.btn-next');

        // Mostrar/ocultar botones laterales según existencia de prev/next en paginador
        function updateSideButtons(){
            const pagLinks = panel.querySelectorAll('.paginator-wrapper a');
            let prevUrl = null, nextUrl = null;
            
            pagLinks.forEach(a => {
                const rel = a.getAttribute('rel');
                if(rel === 'prev') prevUrl = a.href;
                if(rel === 'next') nextUrl = a.href;
            });

            // Mostrar/ocultar botones
            prevBtn.style.display = prevUrl ? 'flex' : 'none';
            nextBtn.style.display = nextUrl ? 'flex' : 'none';
            
            prevBtn.dataset.targetUrl = prevUrl || '';
            nextBtn.dataset.targetUrl = nextUrl || '';
        }

        // Manejar la navegación vía AJAX con animación
        async function ajaxNavigate(url){
            if(!url) return;
            
            // anim out
            content.classList.add('slide-out-left');
            await new Promise(r => setTimeout(r, 220));
            
            try {
                const res = await fetch(url, { 
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const text = await res.text();
                const tmp = document.createElement('div');
                tmp.innerHTML = text;
                
                // Buscar TODOS los panel-content de la página retornada
                const allPanels = tmp.querySelectorAll('.products-panel .panel-content');
                let newContent = null;
                
                // Si es búsqueda, tomar el primer panel
                if(panel.closest('.category-section') === null){
                    newContent = allPanels[0];
                } else {
                    // Si es categoría, necesitamos identificar cuál es el correcto
                    // Contamos cuál categoría es este panel en el DOM actual
                    const allCurrentPanels = document.querySelectorAll('.category-section .products-panel');
                    let panelIndex = 0;
                    allCurrentPanels.forEach((p, idx) => {
                        if(p === panel) panelIndex = idx;
                    });
                    // Tomar el panel correspondiente de la respuesta
                    const responseCategories = tmp.querySelectorAll('.category-section .products-panel');
                    if(responseCategories[panelIndex]){
                        newContent = responseCategories[panelIndex].querySelector('.panel-content');
                    }
                }
                
                if(newContent){
                    content.innerHTML = newContent.innerHTML;
                    bindPaginationLinks(panel);
                } else {
                    window.location.href = url;
                    return;
                }
            } catch(e) {
                console.error(e);
                window.location.href = url;
                return;
            }
            
            // anim in
            content.classList.remove('slide-out-left');
            content.classList.add('slide-in-right');
            requestAnimationFrame(() => {
                content.classList.add('slide-in');
                content.classList.remove('slide-in-right');
            });
            
            setTimeout(() => {
                content.classList.remove('slide-in');
            }, 400);
            
            updateSideButtons();
        }

        // bind side buttons
        prevBtn.addEventListener('click', () => ajaxNavigate(prevBtn.dataset.targetUrl));
        nextBtn.addEventListener('click', () => ajaxNavigate(nextBtn.dataset.targetUrl));

        // bind pagination links inside panel
        function bindPaginationLinks(root){
            const links = root.querySelectorAll('.paginator-wrapper a');
            links.forEach(a => {
                a.addEventListener('click', function(e){
                    const url = this.href;
                    if(!url) return;
                    e.preventDefault();
                    ajaxNavigate(url);
                });
            });
        }

        bindPaginationLinks(panel);
        updateSideButtons();
    }

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.products-panel').forEach(initPanel);
    });
})();
</script>
@endpush

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


