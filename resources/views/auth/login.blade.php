<!DOCTYPE html>
<html lang="es">

<head>
    <title>Inicio de sesión</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_ES" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
</head>

<body id="kt_body" class="auth-bg bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = defaultThemeMode;
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "light" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root">
        <style>
            body {
                background-image: url('assets/media/auth/bg7.jpg');
            }

            [data-bs-theme="dark"] body {
                background-image: url('assets/media/auth/bg7.jpg');
            }
        </style>
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <div class="px-10 d-flex flex-center w-lg-50 pt-15 pt-lg-0">
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <center>
                        <a href="/jr" class="mb-7">
                            <img data-kt-element="current-lang-flag" class="rounded w-100px h-100px me-3"
                                src="logo-d.png" alt="" />
                            <h1 style="color:white;">DigitBol</h1>
                            <h3 style="color:white;">¡Impulsando el desarrollo tecnológico de Bolivia!</h3>
                        </a>
                    </center>
                </div>
            </div>
            <div
                class="p-12 d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-lg-20">
                <div class="p-20 bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px">
                    <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
                        <form class="form w-100" method="POST" action="{{ route('login') }}">
                            <center> <img data-kt-element="current-lang-flag" class="rounded w-100px h-100px me-3"
                                    src="logos/LOGOMIORA.jpg" alt="" />
                                <br>
                            </center>
                            <div class="text-center mb-11">
                                <h1 class="mb-3 text-dark fw-bolder">Inicio de sesión</h1>
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
                                        // Agrega más traducciones aquí...
                                    };
                                    const errorListItems = document.querySelectorAll("#login-errors li");

                                    errorListItems.forEach(item => {
                                        const original = item.textContent.trim();
                                        if (translations[original]) {
                                            item.textContent = translations[original];
                                        }
                                    });
                                });
                            </script>
                            @csrf
                            <div class="mb-3">
                                <label for="username">Correo corporativo:</label>
                                <input class="form-control" id="email" type="email" name="email"
                                    :value="old('email')" required autofocus autocomplete="username"
                                    placeholder="Ingrese su correo...">
                            </div>
                            <div class="mb-3">
                                <label for="userpassword">Contraseña:</label>
                                <input class="form-control" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Ingrese su contraseña...">
                            </div>
                            <div class="mt-4 mb-3 row">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customControlInline"
                                            name="remember">
                                        <label class="form-check-label" for="customControlInline">Recordar
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                        type="submit">Ingresar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex flex-stack px-lg-10">
                        <div class="me-0">
                            <button
                                class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                                data-kt-menu-offset="0px, 0px">
                                <img data-kt-element="current-lang-flag" class="rounded w-60px h-60px me-3"
                                    src="logo-d.png" alt="" />
                                <span data-kt-element="current-lang-name" class="me-1">Made in Bolivia, <a
                                        target="_"
                                        href="https://www.facebook.com/profile.php?id=61575212400434&mibextid=ZbWKwL">Created
                                        By DigitBol</a>
                                </span>
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="assets/js/custom/authentication/sign-in/general.js"></script>
</body>

</html>
