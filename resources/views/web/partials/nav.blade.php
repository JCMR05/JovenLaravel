<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="{{ route('home') }}">🥖 La Panadería</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#productos">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#sobre-nosotros">Sobre Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contacto">Contacto</a></li>
                
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('perfil') }}">Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a></li>
                @endauth
            </ul>
            <a href="{{route('carrito.mostrar')}}" class="btn btn-outline-light d-flex align-items-center">
                <i class="bi-cart-fill me-1"></i>
                <span class="me-2">Pedido</span>
                <span class="badge bg-light text-dark ms-1 rounded-pill">
                    {{ session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0 }}
                </span>
            </a>
        </div>
    </div>
</nav>