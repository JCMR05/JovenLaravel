@extends('plantilla.app')

@section('contenido')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h3 class="card-title">Usuarios</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ isset($registro)?route('usuarios.update', $registro->id) : route('usuarios.store') }}" method="POST" id="formRegistroUsuario">
                                @csrf
                                @if (isset($registro))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $registro->name ??'') }}" required>
                                        @error('name')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $registro->email ??'') }}" required>
                                        @error('email')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="activo" class="form-label">Estado</label>
                                        <select class="form-select" name="activo" id="activo">
                                            <option value="1" {{ old('activo', $registro->activo ?? '1') == '1' ? 'selected' : '' }} >Activo</option>
                                            <option value="0" {{ old('activo', $registro->activo ?? '0') == '0' ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                         @error('activo')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}">
                                        @error('password')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirme la contraseña</label>
                                        <input type="text" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                        @error('password_confirmation')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="role" class="form-label">Rol</label>
                                        <select name="role" id="role" class="form-control">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ isset($registro) && $registro->hasRole($role->name) ? 'selected' : 'No tiene Rol' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary me-md-2"
                                        onclick="window.location.href='{{ route('usuarios.index') }}'">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-footer clearfix">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
