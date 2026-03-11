<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPS Alma Vida – Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; }
        .login-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 480px;
        }
        .panel-left {
            background: linear-gradient(145deg, #0f2942 0%, #0d9488 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .panel-left::before {
            content: '';
            position: absolute;
            width: 450px; height: 450px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.08);
            top: -80px; right: -80px;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.05);
            bottom: 40px; left: 40px;
        }
        .panel-tagline {
            font-size: 11px; letter-spacing: .15em; text-transform: uppercase;
            color: rgba(255,255,255,.5); margin-bottom: 18px;
        }
        .panel-headline {
            font-size: 42px; font-weight: 700; line-height: 1.15; color: #fff; margin-bottom: 16px;
        }
        .panel-desc { color: rgba(255,255,255,.55); font-size: 15px; line-height: 1.65; max-width: 360px; }
        .feature-item { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,.7); font-size: 14px; margin-top: 14px; }
        .feature-item i { color: #5eead4; font-size: 18px; }

        .panel-right {
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 48px;
        }
        .login-logo {
            width: 52px; height: 52px;
            background: #0d9488;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 700; color: #fff;
            margin-bottom: 28px;
        }
        .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 .2rem rgba(13,148,136,.15);
        }
        .btn-teal {
            background: #0d9488; color: #fff;
            border: none; font-weight: 600; font-size: 15px;
            padding: 12px;
            border-radius: 8px;
            transition: background .18s;
        }
        .btn-teal:hover { background: #0f766e; color: #fff; }
        .demo-box {
            background: #f0fdf9;
            border: 1px solid #99f6e4;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 13px;
            color: #134e4a;
        }
        @media (max-width: 860px) {
            .login-wrapper { grid-template-columns: 1fr; }
            .panel-left { display: none; }
        }
    </style>
</head>
<body>
<div class="login-wrapper">

    <!-- Panel izquierdo informativo -->
    <div class="panel-left">
        <div class="panel-tagline">IPS · Sistema Integrado de Gestión</div>
        <h1 class="panel-headline">Gestión de<br>Pacientes<br>&amp; Exámenes</h1>
        <p class="panel-desc">Plataforma digital para el registro, consulta y seguimiento de pacientes y exámenes laborales.</p>
        <div class="feature-item"><i class="bi bi-shield-check"></i> Acceso seguro y autenticado</div>
        <div class="feature-item"><i class="bi bi-people-fill"></i> CRUD completo de pacientes</div>
        <div class="feature-item"><i class="bi bi-lightning-charge-fill"></i> Búsqueda en tiempo real (AJAX)</div>
    </div>

    <!-- Panel derecho – formulario -->
    <div class="panel-right">
        <div class="login-logo">A</div>
        <h2 class="fw-bold mb-1" style="font-size:26px;color:#1a2332">Bienvenido</h2>
        <p class="text-muted mb-4" style="font-size:14px">Ingrese sus credenciales para continuar</p>

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm" novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-medium" style="font-size:13px">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" id="email" name="email"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="usuario@almavida.com"
                           autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-medium" style="font-size:13px">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="••••••••"
                           autocomplete="current-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted" for="remember" style="font-size:13px">Recordarme</label>
                </div>
            </div>

            <button type="submit" class="btn btn-teal w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
            </button>
        </form>

        <div class="demo-box mt-4">
            <div class="fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i>Credenciales de prueba</div>
            Email: <strong>admin@almavida.com</strong><br>
            Contraseña: <strong>password</strong>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validación JavaScript del lado del cliente (criterio rúbrica)
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email    = document.getElementById('email');
    const password = document.getElementById('password');
    let valid = true;

    email.classList.remove('is-invalid');
    password.classList.remove('is-invalid');

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim() || !emailRegex.test(email.value)) {
        email.classList.add('is-invalid');
        let fb = email.closest('.input-group').querySelector('.invalid-feedback');
        if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; email.closest('.input-group').append(fb); }
        fb.textContent = 'Ingrese un correo electrónico válido.';
        valid = false;
    }
    if (!password.value || password.value.length < 6) {
        password.classList.add('is-invalid');
        let fb = password.closest('.input-group').querySelector('.invalid-feedback');
        if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; password.closest('.input-group').append(fb); }
        fb.textContent = 'La contraseña debe tener al menos 6 caracteres.';
        valid = false;
    }
    if (!valid) e.preventDefault();
});
</script>
</body>
</html>
