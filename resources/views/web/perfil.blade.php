
@extends('web.layouts.app')

@section('titulo', 'Mi Perfil')

@section('contenido')
<div class="perfil-page-wrapper">
    <div class="perfil-container">
        <!-- Header -->
        <div class="perfil-header">
            <h1>Mi Perfil</h1>
            <p class="subtitle">Gestiona tu información personal y preferencias</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="perfil-grid">
            <!-- Sidebar -->
            <div>
                <!-- Avatar Card -->
                <div class="perfil-card">
                    <div class="avatar-section">
                        <div class="avatar-container">
                            <div class="perfil-avatar" id="userAvatar">🍞</div>
                            <button class="avatar-change-btn" onclick="openAvatarSelector()">
                                <i class="bi bi-camera-fill"></i>
                            </button>
                        </div>
                        <h2 class="perfil-user-name">{{ $user->name }}</h2>
                        <p class="perfil-user-email">{{ $user->email }}</p>
                        <div class="member-since">
                            <i class="bi bi-calendar3"></i>
                            <span>Miembro desde {{ $user->created_at->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="stats-grid mt-3">
                    <div class="stat-card favorites">
                        <div>
                            <p class="stat-label">Favoritos</p>
                            <p class="stat-value">{{ $favoritos ?? 0 }}</p>
                        </div>
                        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ef4444" stroke="#ef4444" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>

                    <div class="stat-card orders">
                        <div>
                            <p class="stat-label">Pedidos</p>
                            <p class="stat-value">{{ $pedidos ?? 0 }}</p>
                        </div>
                        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>

                    <div class="stat-card points">
                        <div>
                            <p class="stat-label">Puntos</p>
                            <p class="stat-value">{{ $puntos ?? 0 }}</p>
                        </div>
                        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2">
                            <circle cx="12" cy="8" r="7"/>
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                        </svg>
                    </div>
                </div>

                <!-- Action Buttons -->
                <a href="{{ route('web.tienda') }}" class="perfil-btn perfil-btn-favorites mt-3">
                    <i class="bi bi-heart-fill"></i>
                    Ver Tienda
                </a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="perfil-btn perfil-btn-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar Sesión
                    </button>
                </form>
            </div>

            <!-- Main Content -->
            <div>
                <!-- Profile Info -->
                <div class="perfil-card">
                    <form method="POST" action="{{ route('web.perfil.update') }}" id="perfilForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="section-header">
                            <h3>Información Personal</h3>
                            <div id="editButtons">
                                <button type="button" class="btn-edit-perfil" onclick="toggleEdit(true)">
                                    <i class="bi bi-pencil"></i>
                                    Editar
                                </button>
                            </div>
                            <div id="saveButtons" style="display: none;">
                                <button type="submit" class="btn-save-perfil">
                                    <i class="bi bi-check-lg"></i>
                                    Guardar
                                </button>
                                <button type="button" class="btn-cancel-perfil" onclick="toggleEdit(false)">
                                    <i class="bi bi-x-lg"></i>
                                    Cancelar
                                </button>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="perfil-form-field">
                            <label class="perfil-field-label">
                                <i class="bi bi-person perfil-field-icon"></i>
                                Nombre Completo
                            </label>
                            <div id="nameDisplay" class="perfil-field-value">{{ $user->name }}</div>
                            <input type="text" id="nameInput" name="name" 
                                   class="perfil-field-input @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" 
                                   style="display: none;" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="perfil-form-field">
                            <label class="perfil-field-label">
                                <i class="bi bi-envelope perfil-field-icon"></i>
                                Email
                            </label>
                            <div id="emailDisplay" class="perfil-field-value">{{ $user->email }}</div>
                            <input type="email" id="emailInput" name="email" 
                                   class="perfil-field-input @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" 
                                   style="display: none;" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Section -->
                        <div class="password-section" id="passwordSection" style="display: none;">
                            <h4>Cambiar Contraseña</h4>
                            <p class="hint">Deja en blanco si no deseas cambiar la contraseña</p>

                            <div class="perfil-form-field">
                                <label class="perfil-field-label">
                                    <i class="bi bi-lock perfil-field-icon"></i>
                                    Nueva Contraseña
                                </label>
                                <input type="password" name="password" 
                                       class="perfil-field-input @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="perfil-form-field">
                                <label class="perfil-field-label">
                                    <i class="bi bi-lock-fill perfil-field-icon"></i>
                                    Confirmar Contraseña
                                </label>
                                <input type="password" name="password_confirmation" class="perfil-field-input">
                            </div>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="perfil-info-box">
                        <h4><i class="bi bi-shield-check me-2"></i>Información de Seguridad</h4>
                        <p>Tus datos están seguros y solo se utilizan para mejorar tu experiencia de compra. Nunca compartiremos tu información con terceros sin tu consentimiento.</p>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="perfil-card activity-section">
                    <h3>Actividad Reciente</h3>
                    <div class="activity-item">
                        <svg class="activity-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ef4444" stroke="#ef4444" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <div>
                            <p class="activity-text">Tienes <strong>{{ $favoritos ?? 0 }}</strong> productos en favoritos</p>
                            <p class="activity-time">Explora más productos en nuestra tienda</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <svg class="activity-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <div>
                            <p class="activity-text">Total de pedidos realizados: <strong>{{ $pedidos ?? 0 }}</strong></p>
                            <p class="activity-time">¡Gracias por tu preferencia!</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <svg class="activity-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2">
                            <circle cx="12" cy="8" r="7"/>
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                        </svg>
                        <div>
                            <p class="activity-text">Has acumulado <strong>{{ $puntos ?? 0 }}</strong> puntos</p>
                            <p class="activity-time">¡Sigue comprando para más beneficios!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Selector Modal -->
<div id="avatarModal" class="avatar-modal">
    <div class="avatar-modal-content">
        <h3>Selecciona tu Avatar</h3>
        <div class="avatar-grid" id="avatarGrid"></div>
        <div class="modal-buttons">
            <button class="btn-modal-save" onclick="saveAvatar()">Guardar</button>
            <button class="btn-modal-cancel" onclick="closeAvatarSelector()">Cancelar</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="perfilToast" class="perfil-toast">
    <p class="perfil-toast-title" id="toastTitle">Éxito</p>
    <p class="perfil-toast-description" id="toastDescription">Operación completada</p>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof toggleEdit === 'function') {
            toggleEdit(true);
        }
    });
</script>
@endif
@endsection

