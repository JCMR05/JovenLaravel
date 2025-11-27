@extends('web.layouts.app')

@section('titulo', 'Mi Perfil')

@section('contenido')
<div class="container my-5" style="padding-top: 80px;">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <h4 class="mb-0">🥖 Mi Perfil</h4>
        </div>
        <div class="card-body">
          @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('status') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('perfil.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label fw-bold" for="name">Nombre</label>
              <input id="name" type="text" name="name" 
                     class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $user->name) }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold" for="email">Email</label>
              <input id="email" type="email" name="email" 
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $user->email) }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Cambiar Contraseña (opcional)</h5>
            <p class="text-muted small">Deja en blanco si no deseas cambiar la contraseña</p>

            <div class="mb-3">
              <label class="form-label fw-bold" for="password">Nueva Contraseña</label>
              <input id="password" type="password" name="password" 
                     class="form-control @error('password') is-invalid @enderror">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold" for="password_confirmation">Confirmar Contraseña</label>
              <input id="password_confirmation" type="password" name="password_confirmation" 
                     class="form-control">
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
              <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
              <button type="submit" class="btn btn-dark">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection