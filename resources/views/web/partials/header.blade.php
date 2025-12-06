<!-- Cargar estilos (si no se cargan globalmente en layout) -->
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

<section class="hero-figma" id="inicio">
    <div class="hero-overlay-figma"></div>
    <div class="hero-image-figma" style="background-image: url('https://images.unsplash.com/photo-1726981897420-0778c14deedf?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYWtlcnklMjBpbnRlcmlvciUyMHNob3B8ZW58MXx8fHwxNzY0ODc4OTY1fDA&ixlib=rb-4.1.0&q=80&w=1920');"></div>
    <div class="container hero-container-figma">
        <div class="hero-content-figma">
            <h1 class="hero-title-figma">
                Pan Artesanal
                <br>
                <span class="hero-highlight-figma">Horneado Cada Día</span>
            </h1>
            <p class="hero-description-figma">
                Elaboramos nuestros productos con ingredientes naturales y recetas tradicionales.
                Más de 30 años de experiencia en cada bocado.
            </p>
            <div class="hero-buttons-figma">
                <a href="{{ route('web.tienda') }}"  class="btn-hero-primary-figma">
                    Ver Todos los Productos
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
                <a href="#sobre-nosotros" class="btn-hero-secondary-figma">Conocer Más</a>
            </div>
        </div>
    </div>
</section>