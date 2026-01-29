<!doctype html>
<html lang="es">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BBC | Bolivian Business Center</title>
    <link rel="shortcut icon" href="../assets/images/bbc.png" />
    <link rel="stylesheet" href="../../assets/css/hope-ui.min.css?v=2.0.0" />
    <script src="../../assets/js/core/libs.min.js"></script>
    <script src="../../assets/js/core/external.min.js"></script>
    <script src="../../assets/js/plugins/fslightbox.js"></script>
    <script src="../../assets/js/hope-ui.js" defer></script>
    <style>
        .gradient-section {
            width: 100%;

            background: linear-gradient(to left, #061E5C, #061e5cca, #9b70ff);
            background-size: 200% 100%;
            /* Doble del ancho para permitir el movimiento */
            animation: gradientMove 4s linear infinite alternate;
        }

        @keyframes gradientMove {
            0% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <div class="wrapper">
        <section class="login-content gradient-section">
            <div class="row m-0 align-items-center vh-100">
                <div class="col-md-6 d-md-block d-none p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="../../assets/images/auth/ini.png" class="img-fluid gradient-main" alt="images">
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body">
                                    <h4 class="logo-title text-center"><img src="../assets/images/bbc.png"
                                            class="logo-title text-center" alt="" width="200"></h4>
                                    <br>
                                    <h2 class="text-center" style="color:rgb(14, 14, 100);"><strong>Bienvenido al registro</strong> </h2>
                                    <h2 class="text-center" style="color:rgb(14, 14, 100);"><strong>de</strong> </h2>
                                    <h2 class="text-center" style="color:rgb(14, 14, 100);"><strong>Bolivian Business Center</strong> </h2>
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <x-validation-errors class="mb-4" />
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingText"
                                                placeholder="jhondoe" name="name" :value="old('name')" required
                                                autofocus autocomplete="name">
                                            <label for="floatingText">Nombre completo</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="floatingInput"
                                                placeholder="name@example.com" name="email" :value="old('email')"
                                                required autocomplete="username">
                                            <label for="floatingInput">Email</label>
                                        </div>

                                        <div class="form-floating mb-4">
                                            <input type="password" class="form-control" id="floatingPassword"
                                                placeholder="Repetir contraseña" name="password" required
                                                autocomplete="new-password">
                                            <label for="floatingPassword">Contraseña</label>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <input type="password" class="form-control" id="floatingPassword"
                                                placeholder="Contraseña" name="password_confirmation" required
                                                autocomplete="new-password">
                                            <label for="floatingPassword">Repetir Contraseña</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1">Recordar</label>
                                            </div>

                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary py-3 w-100 mb-4">Registrarme</button>
                                    </form>
                                    <p class="text-center mb-0">Tienes una cuenta? <a
                                            href="{{ route('login') }}">Ingresar</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sign-bg">
                    </div>
                </div>

            </div>
        </section>
    </div>
</body>


</html>
