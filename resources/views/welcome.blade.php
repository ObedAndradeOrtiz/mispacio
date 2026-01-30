<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Sistema Spa Miora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link href="{{ asset('logos/LOGOSINFONDO.png') }}" rel="icon">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="a{{ asset('ssets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="my-5 account-pages pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="overflow-hidden card">
                        <div class="pt-0 card-body">

                            <h3 class="mt-5 mb-4 text-center">
                                <a href="index.html" class="d-block auth-logo">
                                    <img src="assets/images/logo-dark.png" alt="" height="30"
                                        class="auth-logo-dark">
                                    <img src="assets/images/logo-light.png" alt="" height="30"
                                        class="auth-logo-light">
                                </a>
                            </h3>
                            @if (Route::has('login'))
                                <div class="mt-4 d-flex justify-content-center">
                                    <a href="{{ url('/dashboard') }}" class="btn"
                                        style="background-color:#52b1e5; color: aliceblue;">IR AL PANEL
                                    </a>
                                </div>
                                @auth
                                @else
                                    <form method="POST" action="{{ route('login') }}">
                                        <div class="p-3">
                                            <h4 class="mb-1 text-center text-muted font-size-18">Bienvenido !</h4>
                                            <p class="text-center text-muted">Inicia sesión en SPA MIORA.</p>
                                            <form class="mt-4 form-horizontal" action="index.html">
                                                <div class="mb-3">
                                                    <label for="username">Email:</label>
                                                    <input type="text" class="form-control" id="username"
                                                        placeholder="Ingresa tu correo..">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="userpassword">Contraseña:</label>
                                                    <input type="password" class="form-control" id="userpassword"
                                                        placeholder="Ingresa tu contraseña//">
                                                </div>
                                                <div class="mt-4 mb-3 row">
                                                    <div class="col-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="customControlInline">
                                                            <label class="form-check-label"
                                                                for="customControlInline">Recordar
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <button class="btn btn-primary w-md waves-effect waves-light"
                                                            type="submit">Iniciar sesión</button>
                                                    </div>
                                                </div>
                                                <div class="mb-0 form-group row">
                                                    {{-- <div class="mt-4 col-12">
                                            <a href="pages-recoverpw.html" class="text-muted"><i
                                                    class="mdi mdi-lock"></i> Forgot your password?</a>
                                        </div> --}}
                                                </div>
                                            </form>
                                        </div>
                                    </form>
                                @endauth
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Don't have an account ? <a href="pages-register.html" class="text-primary"> Signup Now </a>
                        </p>
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Lexa <span class="d-none d-sm-inline-block"> - Crafted with <i
                                class="mdi mdi-heart text-danger"></i>
                            by Themesdesign.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js"></script>
</body>

</html>
