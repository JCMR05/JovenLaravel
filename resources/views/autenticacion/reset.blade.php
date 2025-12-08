@extends('autenticacion.app')
@section('titulo', 'Nueva Contraseña - El Parche de Pan')

@section('auth-content')
<div class="auth-wrapper auth-simple">
    <div class="auth-form-panel">
        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
        </a>

        <h2 class="form-title">Nueva Contraseña</h2>
        <p class="form-subtitle">Ingresa tu nueva contraseña</p>

        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form -->
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
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
                    <i class="bi bi-lock"></i> Nueva Contraseña
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

            <button type="submit" class="btn-submit">Actualizar Contraseña</button>
        </form>
    </div>
</div>
@endsection

