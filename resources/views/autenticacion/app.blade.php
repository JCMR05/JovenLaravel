{{-- filepath: c:\Users\judar\OneDrive\Documentos\Proyecto Sena\JovenLaravel\resources\views\autenticacion\app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'El Parche de Pan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Fondo con imagen */
        .auth-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .auth-background img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.5);
        }

        .auth-background::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.2) 0%, rgba(253, 230, 138, 0.2) 50%, rgba(252, 211, 77, 0.2) 100%);
        }

        /* Header */
        .auth-header {
            text-align: center;
            padding: 1.5rem;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .auth-logo-img {
            height: 50px;
            width: auto;
        }

        .auth-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .auth-logo-text span:first-child {
            color: #78350f;
        }

        .auth-logo-text span:last-child {
            color: #d97706;
        }

        /* Main Container */
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-wrapper {
            display: flex;
            max-width: 1000px;
            width: 100%;
            gap: 2rem;
            align-items: stretch;
        }

        /* Panel Izquierdo - Benefits */
        .auth-benefits {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            border-radius: 1.5rem;
            padding: 3rem;
            color: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .auth-benefits h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .auth-benefits > p {
            opacity: 0.9;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .benefit-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .benefit-icon {
            width: 2rem;
            height: 2rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .benefit-icon i {
            font-size: 0.875rem;
        }

        .benefit-text h4 {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .benefit-text p {
            font-size: 0.875rem;
            opacity: 0.85;
            margin: 0;
        }

        /* Panel Derecho - Form */
        .auth-form-panel {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            flex: 1;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Tabs */
        .auth-tabs {
            display: flex;
            margin-bottom: 1.5rem;
            background: #f3f4f6;
            border-radius: 2rem;
            padding: 0.25rem;
        }

        .auth-tab {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border: none;
            background: transparent;
            border-radius: 2rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            font-size: 0.95rem;
        }

        .auth-tab:hover {
            color: #d97706;
        }

        .auth-tab.active {
            background: #d97706;
            color: white;
        }

        /* Form Title */
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #78350f;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #d97706;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #78350f;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-label i {
            color: #d97706;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #fde68a;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s;
            background: #fffbeb;
        }

        .form-input:focus {
            outline: none;
            border-color: #d97706;
            background: white;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
            display: block;
        }

        /* Password Input */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-input {
            padding-right: 3rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #d97706;
            cursor: pointer;
            padding: 0;
        }

        /* Checkbox & Links Row */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .form-checkbox input {
            width: 1rem;
            height: 1rem;
            accent-color: #d97706;
        }

        .form-checkbox span {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .form-link {
            color: #d97706;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .form-link:hover {
            color: #b45309;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: #d97706;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }

        .btn-submit:hover {
            background: #b45309;
            transform: translateY(-1px);
        }

        /* Guest Link */
        .guest-link {
            display: block;
            text-align: center;
            color: #d97706;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .guest-link:hover {
            color: #b45309;
        }

        /* Alerts */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success, .alert-info {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #d97706;
        }

        /* Simple Layout (para recuperación) */
        .auth-simple {
            max-width: 450px;
            width: 100%;
        }

        .auth-simple .auth-form-panel {
            flex: none;
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .auth-wrapper:not(.auth-simple) {
                flex-direction: column;
                gap: 1.5rem;
            }

            .auth-benefits {
                padding: 2rem;
            }

            .auth-benefits h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 1rem;
            }

            .auth-benefits {
                padding: 1.5rem;
            }

            .auth-form-panel {
                padding: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Fondo con imagen -->
    <div class="auth-background">
        <img src="{{ asset('uploads/productos/fondo.jpg') }}" alt="Fondo panadería">
    </div>

    <!-- Header -->
    <header class="auth-header">
        <a href="{{ route('home') }}" class="auth-logo">
            <img src="{{ asset('uploads/productos/ok7.png') }}" alt="Logo El Parche de Pan" class="auth-logo-img">
            <div class="auth-logo-text">
                <span>El Parche</span> <span>de Pan</span>
            </div>
        </a>
    </header>

    <!-- Main Content -->
    <main class="auth-container">
        @yield('auth-content')
    </main>

    <script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
    </script>
</body>
</html>
