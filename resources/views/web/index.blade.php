@extends('web.app')
@section('header')
@endsection
@section('contenido')

{{-- Sección de Productos por Categoría --}}
<div id="productos">
    @if(!empty($productos) && request('search'))
        @php $productos = $productos ?? collect(); @endphp
        
        <div class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-header">
                    <h2>Resultados de búsqueda</h2>
                    <p>Mostrando resultados para: "{{ request('search') }}"</p>
                </div>

                @if($productos->count() > 0)
                    @include('web.partials.carousel', ['items' => $productos, 'carouselId' => 'search'])
                @else
                    <p class="text-center text-muted">No se encontraron productos.</p>
                @endif
            </div>
        </div>

    @else
        @foreach($categorias as $categoria)
            @if($categoria->productos->count() > 0)
            <div class="carousel-section" style="background: {{ $loop->even ? '#fff9f0' : 'linear-gradient(to bottom, #ffffff, #fef3c7)' }};">
                <div class="carousel-container">
                    <div class="carousel-header">
                        <h2>{{ $categoria->nombre }}</h2>
                        <p>Descubre nuestra selección de {{ strtolower($categoria->nombre) }} elaborados artesanalmente.</p>
                    </div>

                    @include('web.partials.carousel', ['items' => $categoria->productos, 'carouselId' => 'cat-'.$categoria->id, 'categoryName' => $categoria->nombre])
                </div>
            </div>
            @endif
        @endforeach
    @endif
</div>

{{-- ============================================
    SOBRE NOSOTROS - Diseño Figma
    ============================================ --}}
<section id="sobre-nosotros" class="about-section-figma">
    <div class="container">
        <div class="about-grid">
            {{-- Imagen --}}
            <div class="about-image-wrapper">
                <img src="{{ asset('uploads/productos/panaderia-interior.jpg') }}" 
                     alt="Nuestra panadería"
                     onerror="this.src='https://images.unsplash.com/photo-1627308593341-d886acdc06a2?w=600&h=500&fit=crop'">
                <div class="about-decoration"></div>
            </div>

            {{-- Contenido --}}
            <div class="about-content-figma">
                <h2>Sobre Nosotros</h2>
                <p class="about-text">
                    En <strong>El Parche de Pan</strong> llevamos años elaborando pan artesanal 
                    con la misma pasión y dedicación del primer día. Nuestra familia ha pasado 
                    de generación en generación el arte de la panadería tradicional.
                </p>
                <p class="about-text">
                    Utilizamos únicamente ingredientes naturales de la más alta calidad, 
                    respetando los tiempos de fermentación y cocción que hacen de nuestros 
                    productos algo único y especial.
                </p>

                <div class="features-figma">
                    <div class="feature-item-figma">
                        <div class="feature-icon-figma">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h4>Horneado Diario</h4>
                            <p>Productos frescos cada mañana</p>
                        </div>
                    </div>

                    <div class="feature-item-figma">
                        <div class="feature-icon-figma">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div>
                            <h4>100% Natural</h4>
                            <p>Sin conservantes artificiales</p>
                        </div>
                    </div>

                    <div class="feature-item-figma">
                        <div class="feature-icon-figma">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <h4>Recetas Tradicionales</h4>
                            <p>El sabor de siempre</p>
                        </div>
                    </div>

                    <div class="feature-item-figma">
                        <div class="feature-icon-figma">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h4>Hecho con Amor</h4>
                            <p>Pasión en cada producto</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
    PRODUCTOS DESTACADOS - Diseño Figma
    ============================================ --}}
@if(isset($productosDestacados) && $productosDestacados->count() > 0)
<section id="productos-destacados" class="featured-section-figma">
    <div class="container">
        <div class="section-header-figma center">
            <h2>Productos Destacados</h2>
            <p>Descubre los productos más populares y amados por nuestros clientes</p>
        </div>

        <div class="products-grid-figma">
            @foreach($productosDestacados as $producto)
            <div class="featured-card-figma">
                <div class="featured-image-figma">
                    @if($producto->imagen)
                        <img src="{{ asset('uploads/productos/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                    @else
                        <img src="https://via.placeholder.com/300x200?text={{ urlencode($producto->nombre) }}" alt="{{ $producto->nombre }}">
                    @endif
                    <div class="featured-badge-figma">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>Destacado</span>
                    </div>
                </div>
                <div class="featured-info-figma">
                    @if($producto->categorias->count() > 0)
                        <span class="featured-category-figma">{{ $producto->categorias->first()->nombre }}</span>
                    @endif
                    <h3 class="featured-name-figma">{{ $producto->nombre }}</h3>
                    <p class="featured-description-figma">{{ Str::limit($producto->descripcion, 80) }}</p>
                    <div class="featured-footer-figma">
                        <span class="featured-price-figma">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                        <a href="{{ route('web.show', $producto->id) }}" class="btn-featured-figma">Ver Más</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="center">
            <a href="{{ route('web.tienda') }}" class="btn-large-figma">Ver Todos los Productos</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================
    UBICACIÓN - Diseño Figma
    ============================================ --}}
<section id="ubicacion" class="location-section-figma">
    <div class="container">
        <div class="section-header-figma">
            <h2>Visítanos</h2>
            <p>Te esperamos en nuestra panadería para que disfrutes de nuestros productos recién horneados</p>
        </div>

        <div class="location-grid">
            {{-- Mapa --}}
            <div class="map-container">
                {{-- Reemplaza las coordenadas con la ubicación real de tu panadería --}}
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.123456789!2d-74.0817!3d4.6097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNMKwMzYnMzQuOSJOIDc0wrAwNCc1NC4xIlc!5e0!3m2!1ses!2sco!4v1234567890"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Ubicación de El Parche de Pan">
                </iframe>
            </div>

            {{-- Información de contacto --}}
            <div class="contact-info-figma">
                <h3>Información de Contacto</h3>

                <div class="contact-items-figma">
                    {{-- Dirección --}}
                    <div class="contact-item-figma">
                        <div class="contact-icon-figma">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4>Dirección</h4>
                            <p>Calle Principal #123<br>Bogotá, Colombia</p>
                        </div>
                    </div>

                    {{-- Teléfono --}}
                    <div class="contact-item-figma">
                        <div class="contact-icon-figma">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4>Teléfono</h4>
                            <a href="tel:+573001234567">+57 300 123 4567</a>
                        </div>
                    </div>

                    {{-- Horario --}}
                    <div class="contact-item-figma">
                        <div class="contact-icon-figma">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h4>Horario</h4>
                            <p><strong>Lunes - Viernes:</strong> 6:00 AM - 8:00 PM</p>
                            <p><strong>Sábados - Domingos:</strong> 7:00 AM - 6:00 PM</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="contact-item-figma">
                        <div class="contact-icon-figma">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <a href="mailto:info@elparchedepan.com">info@elparchedepan.com</a>
                        </div>
                    </div>
                </div>

                {{-- Botón de direcciones --}}
                <a href="https://www.google.com/maps/dir/?api=1&destination=4.6097,-74.0817" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="btn-directions">
                    <i class="fas fa-directions me-2"></i>
                    Cómo Llegar
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

