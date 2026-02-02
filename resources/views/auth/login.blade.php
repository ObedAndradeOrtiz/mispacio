<!DOCTYPE html>
<html lang="es">

<head>
    <title>Inicio de sesión</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* ✅ Elige tu imagen aquí */
        body {
            background-image: url('{{ asset('assets/media/auth/bg9.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* ✅ Para que en móvil se vea igual (sin “scroll raro” por fixed en algunos browsers) */
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }
        }

        /* ✅ Caja centrada */
        .auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        /* ✅ Para que no sea gigante en PC ni pequeño en móvil */
        .auth-card {
            width: 100%;
            max-width: 520px;
        }
    </style>
</head>

<body id="kt_body">
    <div class="auth-wrap">
        <div class="shadow-sm card auth-card rounded-4">
            <div class="p-8 card-body p-lg-12">

                <div class="mb-8 text-center">
                    <img class="rounded w-100px h-100px" src="{{ asset('logo-d.png') }}" alt="logo" />
                    <h1 class="mt-4 mb-1 text-dark fw-bolder">Inicio de sesión</h1>
                </div>

                <!-- Mostrar errores de validación -->
                @if ($errors->any())
                    <div class="mb-4">
                        <div id="login-errors" class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const translations = {
                            "These credentials do not match our records.": "Estas credenciales no coinciden con nuestros registros.",
                            "The email field is required.": "El campo correo electrónico es obligatorio.",
                            "The password field is required.": "El campo contraseña es obligatorio.",
                        };
                        const errorListItems = document.querySelectorAll("#login-errors li");
                        errorListItems.forEach(item => {
                            const original = item.textContent.trim();
                            if (translations[original]) item.textContent = translations[original];
                        });
                    });
                </script>

                <form class="form w-100" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label" for="email">Correo corporativo</label>
                        <input class="form-control" id="email" type="email" name="email"
                            value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="Ingrese su correo...">
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="password">Contraseña</label>
                        <input class="form-control" id="password" type="password" name="password" required
                            autocomplete="current-password" placeholder="Ingrese su contraseña...">
                    </div>

                    <div class="mb-6 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordar</label>
                        </div>

                        <button class="btn btn-primary" type="submit">Ingresar</button>
                    </div>
                </form>

                <div class="pt-4 text-center border-top">

                    <div class="text-gray-700">
                        Made in Bolivia,
                        <a target="_" href="https://www.facebook.com/profile.php?id=61575212400434&mibextid=ZbWKwL">
                            Created By DigitBol
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>
