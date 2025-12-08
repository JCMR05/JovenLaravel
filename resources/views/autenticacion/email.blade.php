@extends('autenticacion.app')
@section('titulo', 'Recuperar Contraseña - El Parche de Pan')

@section('auth-content')
<div class="auth-wrapper auth-simple">
    <div class="auth-form-panel">
        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
        </a>

        <h2 class="form-title">Recuperar Contraseña</h2>
        <p class="form-subtitle">Ingresa tu email para recuperar tu contraseña</p>

        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(Session::has('mensaje'))
            <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
        @endif

        <!-- Form -->
        <form action="{{ route('password.send-link') }}" method="POST">
            @csrf
            
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

            <button type="submit" class="btn-submit">Enviar enlace de recuperación</button>
        </form>

        <a href="{{ route('home') }}" class="guest-link">Continuar como invitado →</a>
    </div>
</div>
@endsection