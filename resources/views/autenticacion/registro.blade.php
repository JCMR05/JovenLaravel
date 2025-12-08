@extends('autenticacion.app')
@section('titulo', 'Crear Cuenta - El Parche de Pan')

@section('auth-content')
<div class="auth-wrapper">
    <!-- Benefits Panel -->
    <div class="auth-benefits">
        <h2>Bienvenido a El Parche de Pan</h2>
        <p>Accede a tu cuenta para disfrutar de todas las ventajas</p>
        
        <div class="benefit-list">
            <div class="benefit-item">
                <div class="benefit-icon"><i class="bi bi-check-lg"></i></div>
                <div class="benefit-text">
                    <h4>Guarda tus productos favoritos</h4>
                    <p>Marca tus panes preferidos y accede a ellos fácilmente</p>
                </div>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon"><i class="bi bi-check-lg"></i></div>
                <div class="benefit-text">
                    <h4>Realiza pedidos más rápido</h4>
                    <p>Tu información guardada para compras más rápidas</p>
                </div>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon"><i class="bi bi-check-lg"></i></div>
                <div class="benefit-text">
                    <h4>Consulta tu historial</h4>
                    <p>Revisa todos tus pedidos anteriores</p>
                </div>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon"><i class="bi bi-check-lg"></i></div>
                <div class="benefit-text">
                    <h4>Acumula puntos</h4>
                    <p>Gana puntos por cada compra que realices</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Panel -->
    <div class="auth-form-panel">
        <!-- Tabs -->
        <div class="auth-tabs">
            <a href="{{ route('login') }}" class="auth-tab">Iniciar Sesión</a>
            <a href="{{ route('registro.create') }}" class="auth-tab active">Crear Cuenta</a>
        </div>

        <h2 class="form-title">Crear Cuenta</h2>
        <p class="form-subtitle">Completa tus datos para registrarte</p>

        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form -->
        <form action="{{ route('registro.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-person"></i> Nombre Completo
                </label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    class="form-input @error('name') is-invalid @enderror" 
                    placeholder="Juan Pérez"
                >
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-envelope"></i> Correo Electrónico
                </label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    class="form-input @error('email') is-invalid @enderror" 
                    placeholder="tu@email.com"
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-lock"></i> Contraseña
                </label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="form-input @error('password') is-invalid @enderror" 
                        placeholder="••••••••"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="password-icon"></i>
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-lock-fill"></i> Confirmar Contraseña
                </label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="form-input" 
                        placeholder="••••••••"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="bi bi-eye" id="password_confirmation-icon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Crear Cuenta</button>
        </form>

        <a href="{{ route('home') }}" class="guest-link">Continuar como invitado →</a>
    </div>
</div>
@endsection

