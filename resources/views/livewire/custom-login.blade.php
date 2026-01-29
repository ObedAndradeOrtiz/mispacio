<div>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Sistema Spa Miora</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        <link href="{{ asset('logos/LOGOSINFONDO.png') }}" rel="icon">
        <link rel="stylesheet" href="../../assets/css/hope-ui.min.css?v=2.0.0" />
        <script src="../../assets/js/core/libs.min.js"></script>
        <script src="../../assets/js/core/external.min.js"></script>
        <script src="../../assets/js/plugins/fslightbox.js"></script>
        <script src="../../assets/js/hope-ui.js" defer></script>
        <style>
            .gradient-section {
                width: 100%;

                background: linear-gradient(to left, #52b1e5, #52b1e5, #ff7bac);
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

    <body class="bg-dark" data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
        <div class="wrapper">
            <section class="login-content gradient-section">
                <div class="m-0 row align-items-center vh-100">
                    <div class="p-0 overflow-hidden col-md-6 d-md-block d-none mt-n1 vh-100">
                        <img src="{{ asset('assets/images/auth/miora.png') }}" class="img-fluid gradient-main"
                            alt="images">
                    </div>
                    <div class="col-md-6">
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <div
                                    class="mb-0 shadow-none card card-transparent d-flex justify-content-center auth-card">
                                    <div class="card-body">
                                        <h4 class="text-center logo-title"><img
                                                src="{{ asset('logos/LOGOSINFONDO.png') }}"
                                                class="text-center logo-title" alt="" width="200"></h4>
                                        <br>
                                        <h2 class="text-center" style="color:#52b1e5;"><strong> Inicia sesión con
                                                tu</strong></h2>
                                        <h2 class="text-center" style="color:#52b1e5;"><strong> cuenta de</strong></h2>
                                        <h2 class="text-center" style="color:#ff7bac;"><strong> SPA - MIORA -
                                                APP</strong>
                                        </h2>


                                        <!-- resources/views/livewire/custom-login.blade.php -->

                                        <div>
                                            <form wire:submit.prevent="login">
                                                <div>
                                                    <div>
                                                        <label for="email">Email:</label>
                                                        <input class="form-control" type="email" wire:model="email"
                                                            id="email">
                                                    </div>

                                                    <div>
                                                        <label for="password">Contraseña:</label>
                                                        <input class="form-control" type="password"
                                                            wire:model="password" id="password">
                                                    </div>
                                                    <div class="col-lg-12 d-flex justify-content-between">
                                                        <div
                                                            class="mb-4 d-flex align-items-center justify-content-between">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="exampleCheck1">
                                                                <label class="form-check-label"
                                                                    for="exampleCheck1">Recordar</label>
                                                            </div>

                                                        </div>
                                                        <a href="">Olvidaste tu contraseña?</a>
                                                    </div>
                                                </div>


                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn"
                                                        style="background-color:#52b1e5; color: aliceblue;">Iniciar
                                                        sesión
                                                    </button>
                                                </div>
                                            </form>

                                            @if (session()->has('message'))
                                                <div>{{ session('message') }}</div>
                                            @endif
                                        </div>

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
</div>
