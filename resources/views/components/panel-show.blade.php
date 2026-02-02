<div>
    @php
        $horaverificada = Auth::user()->horaverificada;
        $horas = $horaverificada ? \Carbon\Carbon::parse($horaverificada)->diffInHours(now()) : 9999;
    @endphp
    <?php
    $rolesderol = DB::table('roles_vistas')
        ->where('namerol', Auth::user()->rol)
        ->where('estado', 'Activo')
        ->get();
    ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />


    @if (Auth::user()->estado == 'Activo')
        <div id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-disabled">
            <style>
                :root {
                    --ms-nude: #D2B89F;
                    /* tu logo */
                    --ms-mauve: #B7A69A;
                    /* femenino sofisticado */
                    --ms-cream: #FAF7F2;
                    /* blanco cálido */
                    --ms-ink: #2B2B2B;
                    /* oscuro suave */
                    --ms-text: #4A4744;
                    /* texto menos duro que #333 */
                    --ms-line: rgba(0, 0, 0, .08);
                }

                /* Estilos del botón flotante */
                #floatingButton {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    padding: 10px 16px;
                    background-color: #1a2340;
                    color: white;
                    border-radius: 30px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: grab;
                    z-index: 1000;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    transition: background 0.3s;
                }

                /* Estilos del menú */
                #floatingMenu {
                    position: fixed;
                    bottom: 90px;
                    right: 20px;
                    background-color: white;
                    padding: 10px;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    display: none;
                    width: 250px;
                    z-index: 999;
                }

                #floatingMenu a {
                    display: block;
                    padding: 10px;
                    text-decoration: none;
                    color: #333;
                    border-bottom: 1px solid #ddd;
                }

                #floatingMenu a:last-child {
                    border-bottom: none;
                }

                #floatingMenu a:hover {
                    background-color: #f0f0f0;
                }
            </style>
            <div id="floatingButton">
                <i class="ki-outline ki-element-4 fs-2 me-2" style="color:whitesmoke;"></i> Menú
            </div>
            <div id="floatingMenu">
                <div class="my-2 hover-scroll-overlay-y my-lg-5 pe-lg-n1" id="kt_aside_menu_wrapper">

                    <div class="menu menu-column menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-200 fw-semibold"
                        id="#kt_aside_menu" data-kt-menu="true">

                        @if ($rolesderol->contains('vista', 'Clientes') || Auth::user()->rol == 'Asist. Administrativo')
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="right-start" class="py-2 menu-item">

                                <span class="menu-link menu-center d-flex">
                                    <span class="menu-icon me-0">
                                        <i class="ki-outline ki-calendar fs-2x"></i>
                                    </span>
                                    <span class="menu-title">Agenda</span>
                                </span>
                                <div
                                    class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                    <div class="menu-item ">

                                        <div class="menu-content">
                                            <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                                agenda</span>
                                        </div>
                                    </div>
                                    <a class="menu-link d-flex" href="/recepcion">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Agenda</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/flujo">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Flujo de Pacientes</span>
                                    </a>

                                    <a class="menu-link d-flex" href="/clientes">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Clientes</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/deudas">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Deudas de clientes</span>
                                    </a>

                                </div>
                            </div>
                        @endif
                        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                            class="py-2 menu-item">

                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="ki-outline ki-call fs-2x"></i>
                                </span>
                                <span class="menu-title">Call Center</span>
                            </span>


                            <div
                                class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                <div class="menu-item">

                                    <div class="menu-content">
                                        <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                            call center</span>
                                    </div>
                                </div>
                                <a class="menu-link d-flex" href="/call-center-llamadas">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Lista de llamadas</span>
                                </a>
                                <a class="menu-link d-flex" href="/call-center-rendimiento">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Rendimiento de llamadas</span>
                                </a>


                            </div>
                        </div>
                        {{-- @if ($rolesderol->contains('vista', 'Administrador'))
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="right-start" class="py-2 menu-item">

                                <span class="menu-link menu-center">
                                    <span class="menu-icon me-0">
                                        <i class="ki-outline ki-people fs-2x"></i>
                                    </span>
                                    <span class="menu-title">RRHH</span>
                                </span>


                                <div
                                    class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                    <div class="menu-item">

                                        <div class="menu-content">
                                            <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                                Recursos humanos</span>
                                        </div>
                                    </div>
                                    <a class="menu-link d-flex" href="/call-center-rendimiento">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Reportes Call Center</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/reportes">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Reportes personales</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/rh">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Panel de recursos humanos</span>
                                    </a>
                                </div>
                            </div>
                        @endif --}}
                        {{-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                            class="py-2 menu-item">

                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="ki-outline ki-chart fs-2x"></i>


                                </span>
                                <span class="menu-title">Marketing</span>
                            </span>


                            <div
                                class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                <div class="menu-item">

                                    <div class="menu-content">
                                        <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                            marketing</span>
                                    </div>
                                </div>
                                <a class="menu-link d-flex" href="/crm">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">CRM</span>
                                </a>
                                @if (Auth::user()->rol == 'Jefe Marketing y Publicidad' || Auth::user()->rol == 'Editor' || $rolesderol->contains('vista', 'Administrador') || Auth::user()->rol == 'Asist. Administrativo' || Auth::user()->rol == 'Contador')
                                    @if (Auth::user()->rol == 'Jefe Marketing y Publicidad' || Auth::user()->rol == 'Editor' || Auth::user()->tesoreria == 'Activo')
                                        <a class="menu-link d-flex" href="/marketing">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Marketing</span>
                                        </a>
                                    @endif



                                @endif


                            </div>
                        </div> --}}
                        @if ($rolesderol->contains('vista', 'Administrador'))
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="right-start" class="py-2 menu-item">
                                <span class="menu-link menu-center">
                                    <span class="menu-icon me-0">
                                        <i class="ki-outline ki-chart-line fs-2x"></i>

                                    </span>
                                    <span class="menu-title">Ventas</span>
                                </span>
                                <div
                                    class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">
                                    <div class="menu-item">
                                        <div class="menu-content">
                                            <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                                ventas</span>
                                        </div>
                                    </div>

                                    <a class="menu-link d-flex" href="/ventas-general">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Panel general de ventas</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/ventas">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Panel de ventas, agendados, enganches</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/estadistica-ventas">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Estadística Ventas</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/estadistica-agendados">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Estadística Agendados</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/estadistica-llamadas">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Estadística Llamadas</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/estadistica-tratamientos">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Estadística Tratamientos</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($rolesderol->contains('vista', 'Administrador'))
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="right-start" class="py-2 menu-item">

                                <span class="menu-link menu-center">
                                    <span class="menu-icon me-0">
                                        <i class="ki-outline ki-briefcase fs-2x"></i>


                                    </span>
                                    <span class="menu-title">Administración</span>
                                </span>


                                <div
                                    class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                    <div class="menu-item">

                                        <div class="menu-content">
                                            <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                                administración</span>
                                        </div>
                                    </div>
                                    <a class="menu-link d-flex" href="/usuarios">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Usuarios</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/administrador">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Roles de usuarios</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/registros">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Registros de sistema</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/tratamientos">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Tratamientos</span>
                                    </a>
                                </div>

                            </div>
                        @endif
                        @if ($rolesderol->contains('vista', 'Administrador') && Auth::user()->tesoreria == 'Activo')
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="right-start" class="py-2 menu-item">

                                <span class="menu-link menu-center">
                                    <span class="menu-icon me-0">
                                        <i class="ki-outline ki-bank fs-2x"></i>



                                    </span>
                                    <span class="menu-title">Finanzas</span>
                                </span>


                                <div
                                    class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                    <div class="menu-item">

                                        <div class="menu-content">
                                            <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                                finanzas</span>
                                        </div>
                                    </div>
                                    <a class="menu-link d-flex" href="/tesoreria">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Tesorería</span>
                                    </a>
                                    @if (Auth::user()->rol == 'Administrador' && Auth::user()->tesoreria == 'Activo')
                                        <a class="menu-link d-flex" href="/estadistica-ingresos">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Estadística Ingresos</span>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        @endif
                        @if (
                            $rolesderol->contains('vista', 'Inventario') ||
                                Auth::user()->rol == 'Contador' ||
                                Auth::user()->rol == 'Asist. Administrativo')
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-placement="right-start" class="py-2 menu-item">

                                <span class="menu-link menu-center">
                                    <span class="menu-icon me-0">
                                        <i class="ki-outline ki-archive fs-2x"></i>

                                    </span>
                                    <span class="menu-title">Inventario</span>
                                </span>


                                <div
                                    class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">

                                    <div class="menu-item">

                                        <div class="menu-content">
                                            <span class="py-1 menu-section fs-5 fw-bolder ps-1">Menú de
                                                inventarios</span>
                                        </div>
                                    </div>
                                    <a class="menu-link d-flex" href="/inventario">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Productos</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/inventario-inmueble">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Activos</span>
                                    </a>
                                    {{-- <a class="menu-link d-flex" href="/lista-productos">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Lista de produtos</span>
                                    </a> --}}
                                    <a class="menu-link d-flex" href="/lista-almacen">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Lista de almacén</span>
                                    </a>
                                    <a class="menu-link d-flex" href="/traspaso">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Trapaso de almacén</span>
                                    </a>
                                    {{-- <a class="menu-link d-flex" href="/traspaso-almacen">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Trapasos de almacén</span>
                                    </a> --}}
                                    {{-- <a class="menu-link d-flex" href="/">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Verificación de almacén</span>
                                </a>
                                <a class="menu-link d-flex" href="/">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Venta de productos</span>
                                </a>
                                <a class="menu-link d-flex" href="/">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Trapasos de alamcén</span>
                                </a> --}}
                                </div>
                            </div>
                        @endif
                        {{-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-2 menu-item">

                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="ki-outline ki-message-programming fs-2x"></i>
                            </span>
                            <span class="menu-title">IA</span>
                        </span>

                        <div
                            class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">
                            <div class="menu-item">
                                <div class="menu-content">
                                    <span class="py-1 menu-section fs-5 fw-bolder ps-1">Opciones IA</span>
                                </div>
                            </div>

                            <a class="menu-link d-flex" href="/nodos">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Controlador</span>
                            </a>
                        </div>
                    </div> --}}
                        {{-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="py-2 menu-item">

                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="ki-outline ki-user fs-2x"></i>
                            </span>
                            <span class="menu-title">Miss Beauty Queen</span>
                        </span>

                        <div
                            class="px-2 py-4 overflow-auto menu-sub menu-sub-dropdown menu-sub-indention w-250px mh-75">
                            <div class="menu-item">
                                <div class="menu-content">
                                    <span class="py-1 menu-section fs-5 fw-bolder ps-1">Opciones Mbq</span>
                                </div>
                            </div>

                            <a class="menu-link d-flex" href="/lista-mbq">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Lista de participantes</span>
                            </a>
                        </div>
                    </div> --}}
                        @livewire('administracion.soporte')
                    </div>
                </div>
            </div>
            <script>
                const button = document.getElementById('floatingButton');
                const menu = document.getElementById('floatingMenu');
                button.addEventListener('click', function(event) {
                    event.stopPropagation();
                    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
                });
                document.addEventListener('click', function(event) {
                    if (!menu.contains(event.target) && event.target !== button) {
                        menu.style.display = 'none';
                    }
                });
            </script>
            <style>
                .header-animated {
                    background: linear-gradient(90deg,
                            #1a2340,
                            /* azul noche */
                            #2f3f60,
                            /* azul pizarra */
                            #536a88,
                            /* azul gris */
                            #e7d0d9,
                            /* rosa/lila suave */
                            #f3e3ea
                            /* blanco rosado */
                        );
                    background-size: 300% 100%;
                    animation: headerMove 8s ease-in-out infinite;
                }

                @keyframes headerMove {
                    0% {
                        background-position: 0% 50%;
                    }

                    50% {
                        background-position: 100% 50%;
                    }

                    100% {
                        background-position: 0% 50%;
                    }
                }
            </style>
            <div class="d-flex flex-column flex-root">
                <div class="flex-row page d-flex flex-column-fluid">
                    <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                        <div id="kt_header" style="" class="header align-items-stretch">
                            <div
                                class="header-animated container-fluid d-flex align-items-stretch justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                                    <a href="/" class="d-lg-none">
                                        <img alt="Logo" src="misf.png" class="h-25px" />
                                    </a>
                                </div>
                                <div class="d-flex align-items-center" id="kt_header_wrapper">
                                    <div class="flex-wrap pb-5 page-title d-flex flex-column align-items-start justify-content-center me-lg-20 pb-lg-0"
                                        data-kt-swapper="true" data-kt-swapper-mode="prepend"
                                        data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_wrapper'}">

                                        <a href="/dashboard"
                                            class="gap-2 d-flex align-items-center text-decoration-none">
                                            <img alt="Logo" src="misf.png" class="h-50px" />
                                            <h1 class="mb-0 fw-bold fs-3 lh-1" style="color: #D2B89F;">Mi
                                                espacio</h1>
                                        </a>

                                    </div>
                                </div>

                                <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                                    <div class="d-flex align-items-stretch" id="kt_header_nav">
                                        <div class="header-menu align-items-stretch" data-kt-drawer="true"
                                            data-kt-drawer-name="header-menu"
                                            data-kt-drawer-activate="{default: true, lg: false}"
                                            data-kt-drawer-overlay="true"
                                            data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                                            data-kt-drawer-direction="end"
                                            data-kt-drawer-toggle="#kt_header_menu_mobile_toggle"
                                            data-kt-swapper="true" data-kt-swapper-mode="prepend"
                                            data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                                            <div class="px-2 my-5 menu menu-rounded menu-column menu-lg-row menu-active-bg menu-state-primary menu-title-gray-600 menu-arrow-gray-400 fw-semibold fs-6 my-lg-0 px-lg-0 align-items-stretch"
                                                id="#kt_header_menu" data-kt-menu="true">
                                                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                                    data-kt-menu-placement="bottom-start"
                                                    class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">

                                                    <span class="py-3 menu-link">
                                                        <span class="menu-title">Funciones</span>
                                                        <span class="menu-arrow d-lg-none"></span>
                                                    </span>
                                                    <div
                                                        class="p-0 menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown w-100 w-lg-850px">
                                                        <div class="overflow-hidden menu-state-bg menu-extended overflow-lg-visible"
                                                            data-kt-menu-dismiss="true">
                                                            <div class="row">
                                                                <div
                                                                    class="px-3 py-3 mb-3 col-lg-8 mb-lg-0 py-lg-6 px-lg-6">
                                                                    <div class="row">
                                                                        <div class="mb-3 col-lg-6">
                                                                            <div class="p-0 m-0 menu-item">
                                                                                <a href="/comprar"
                                                                                    class="menu-link active">
                                                                                    <span
                                                                                        class="flex-shrink-0 rounded menu-custom-icon d-flex flex-center w-40px h-40px me-3">
                                                                                        <i
                                                                                            class="ki-outline ki-basket text-primary fs-1"></i>
                                                                                    </span>
                                                                                    <span class="d-flex flex-column">
                                                                                        <span
                                                                                            class="text-gray-800 fs-6 fw-bold">Comprar
                                                                                            productos</span>
                                                                                        <span
                                                                                            class="fs-7 fw-semibold text-muted">Nuevos
                                                                                            productos para el
                                                                                            inventario</span>
                                                                                    </span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3 col-lg-6">
                                                                            <div class="p-0 m-0 menu-item">
                                                                                <a href="/vender"
                                                                                    class="menu-link d-flex">
                                                                                    <span
                                                                                        class="flex-shrink-0 rounded menu-custom-icon d-flex flex-center w-40px h-40px me-3">
                                                                                        <i
                                                                                            class="ki-outline ki-tag text-success fs-1"></i>
                                                                                    </span>
                                                                                    <span class="d-flex flex-column">
                                                                                        <span
                                                                                            class="text-gray-800 fs-6 fw-bold">Ventas</span>
                                                                                        <span
                                                                                            class="fs-7 fw-semibold text-muted">Registro
                                                                                            de venta y uso interno de
                                                                                            productos</span>
                                                                                    </span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        @if (Auth::user()->rol == 'Administrador' ||
                                                                                Auth::user()->sucursal == 'Suc. COCHABAMBA' ||
                                                                                Auth::user()->sucursal == 'CENTRAL URBARI')
                                                                            <div class="mb-3 col-lg-6">
                                                                                <div class="p-0 m-0 menu-item">
                                                                                    <a href="/traspaso"
                                                                                        class="menu-link d-flex">
                                                                                        <span
                                                                                            class="flex-shrink-0 rounded menu-custom-icon d-flex flex-center w-40px h-40px me-3">
                                                                                            <i
                                                                                                class="ki-outline ki-right-left text-warning fs-1"></i>
                                                                                        </span>
                                                                                        <span
                                                                                            class="d-flex flex-column">
                                                                                            <span
                                                                                                class="text-gray-800 fs-6 fw-bold">Traspasos</span>
                                                                                            <span
                                                                                                class="fs-7 fw-semibold text-muted">Movimientos
                                                                                                de productos entre
                                                                                                sucursales</span>
                                                                                        </span>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        <div class="mb-3 col-lg-6">
                                                                            <div class="p-0 m-0 menu-item">
                                                                                <a href="/gastos"
                                                                                    class="menu-link d-flex">
                                                                                    <span
                                                                                        class="flex-shrink-0 rounded menu-custom-icon d-flex flex-center w-40px h-40px me-3">
                                                                                        <i
                                                                                            class="ki-outline ki-wallet text-danger fs-1"></i>
                                                                                    </span>
                                                                                    <span class="d-flex flex-column">
                                                                                        <span
                                                                                            class="text-gray-800 fs-6 fw-bold">Gastos</span>
                                                                                        <span
                                                                                            class="fs-7 fw-semibold text-muted">Registro
                                                                                            de gastos en general</span>
                                                                                    </span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mx-5 my-5 separator separator-dashed">
                                                                    </div>
                                                                    <div
                                                                        class="flex-wrap gap-2 mx-5 d-flex flex-stack flex-lg-nowrap">
                                                                        <div class="d-flex flex-column me-5">
                                                                            <div class="text-gray-800 fs-6 fw-bold">
                                                                                Para recordar...</div>
                                                                            <div class="fs-7 fw-semibold text-muted">✨
                                                                                "Convierte cada experiencia en
                                                                                bienestar:
                                                                                nuestros insumos de spa elevan la
                                                                                calidad de
                                                                                los servicios y la satisfacción de los
                                                                                clientes. ¡Vende relajación, vende
                                                                                excelencia!"🌿🛁💆‍♀️
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="px-3 py-3 menu-more bg-light col-lg-4 py-lg-6 px-lg-6 rounded-end">
                                                                    <h4
                                                                        class="mt-3 mb-3 text-gray-800 fs-6 fs-lg-4 fw-bold ms-4">
                                                                        Más funcionalidades</h4>
                                                                    <div class="p-0 m-0 menu-item">
                                                                        <a href="/reportes" class="py-2 menu-link">
                                                                            <span class="menu-title">Reportes
                                                                                personales</span>
                                                                        </a>
                                                                    </div>
                                                                    {{-- @if (Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Asist. Administrativo')
                                                                    <div class="p-0 m-0 menu-item">
                                                                        <a href="/ventas-general"
                                                                            class="py-2 menu-link">
                                                                            <span class="menu-title">Gestión de
                                                                                ventas</span>
                                                                        </a>
                                                                    </div>
                                                                @endif --}}


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Asist. Administrativo')
                                                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                                        data-kt-menu-placement="bottom-start"
                                                        class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                                        <span class="py-3 menu-link">
                                                            <span class="menu-title">Sucursales</span>
                                                            <span class="menu-arrow d-lg-none"></span>
                                                        </span>
                                                        <div
                                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
                                                            @foreach ($areas->where('area', '!=', 'ALMACEN CENTRAL') as $area)
                                                                <span class="py-3 menu-link">
                                                                    <span class="menu-bullet">
                                                                        <span class="bullet bullet-dot"></span>
                                                                    </span>
                                                                    <span class="menu-title"><a class="dropdown-item"
                                                                            href="/dashboard/{{ $area->id }}">
                                                                            {{ $area->area }}
                                                                        </a></span>
                                                                </span>
                                                            @endforeach
                                                            @foreach ($rolesderol as $item)
                                                                @if ($item->vista == 'Administrador')
                                                                    <span class="py-3 menu-link">
                                                                        <span class="menu-icon">
                                                                            <i class="ki-outline ki-setting fs-2"></i>

                                                                        </span>
                                                                        <span class="menu-title"><a
                                                                                class="dropdown-item" href="/areas">
                                                                                Configuración
                                                                            </a></span>

                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                @endif
                                                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                                    data-kt-menu-placement="bottom-start"
                                                    class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                                    <span class="py-3 menu-link">
                                                        <span class="menu-title">Ayuda</span>
                                                        <span class="menu-arrow d-lg-none"></span>
                                                    </span>
                                                    <div
                                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                                                        <div class="menu-item">
                                                            <a class="py-3 menu-link" href="" target="_blank"
                                                                title="Soluciones informáticas que puedes aumentar"
                                                                data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                data-bs-dismiss="click" data-bs-placement="right">
                                                                <span class="menu-icon">
                                                                    <i class="ki-outline ki-rocket fs-2"></i>
                                                                </span>
                                                                <span class="menu-title">Componentes</span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="py-3 menu-link" href="" target="_blank"
                                                                title="Documentación de uso" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-dismiss="click"
                                                                data-bs-placement="right">
                                                                <span class="menu-icon">
                                                                    <i class="ki-outline ki-abstract-26 fs-2"></i>
                                                                </span>
                                                                <span class="menu-title">Documentación</span>
                                                            </a>
                                                        </div>

                                                        <div class="menu-item">
                                                            <a class="py-3 menu-link" href="" target="_blank">
                                                                <span class="menu-icon">
                                                                    <i class="ki-outline ki-code fs-2"></i>
                                                                </span>
                                                                <span class="menu-title">Versión v1.0.0.0</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 d-flex align-items-stretch justify-self-end">
                                        <div class="d-flex align-items-center ms-1 ms-lg-3">
                                            <small class="my-1 text-muted fs-7 fw-semibold ms-1">
                                                {{ Auth::user()->sucursal }}
                                            </small>
                                        </div>
                                        <div class="d-flex align-items-center ms-1 ms-lg-3">
                                            <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px position-relative"
                                                data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                                                data-kt-menu-placement="bottom-end">
                                                <i class="ki-outline ki-wallet text-success fs-1"></i>

                                            </div>

                                            <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px"
                                                data-kt-menu="true" id="kt_menu_notifications">

                                                @livewire('tesoreria.micaja')



                                            </div>
                                        </div>
                                        {{-- <div class="d-flex align-items-center ms-1 ms-lg-3">
                                            <div class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px"
                                                id="kt_drawer_chat_toggle">
                                                <i class="ki-outline ki-message-text-2 fs-1"></i>
                                                <span
                                                    class="top-0 bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle start-50 animation-blink"></span>
                                            </div>
                                        </div> --}}
                                        <div class="d-flex align-items-center ms-1 ms-lg-3"
                                            id="kt_header_user_menu_toggle">

                                            <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
                                                data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                                                data-kt-menu-placement="bottom-end">
                                                @if (Auth::user()->path)
                                                    <img src="{{ asset('storage/' . Auth::user()->path) }}"
                                                        alt="Foto de perfil" width="150" class="rounded-circle">
                                                @else
                                                    <img alt="Logo" src="assets/media/avatars/blank.png" />
                                                @endif
                                            </div>

                                            <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px"
                                                data-kt-menu="true">

                                                <div class="px-3 menu-item">
                                                    <div class="px-3 menu-content d-flex align-items-center">

                                                        <div class="symbol symbol-50px me-5">
                                                            @if (Auth::user()->path)
                                                                <img src="{{ asset('storage/' . Auth::user()->path) }}"
                                                                    alt="Foto de perfil" width="150"
                                                                    class="rounded-circle">
                                                            @else
                                                                <img alt="Logo"
                                                                    src="assets/media/avatars/blank.png" />
                                                            @endif

                                                        </div>


                                                        <div class="d-flex flex-column">
                                                            <div class="fw-bold d-flex align-items-center fs-5">
                                                                {{ Auth::user()->name }}
                                                                <span
                                                                    class="px-2 py-1 badge badge-light-success fw-bold fs-8 ms-2">Pro</span>
                                                            </div>
                                                            <a href="#"
                                                                class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                                                            <a href="#"
                                                                class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->rol }}</a>
                                                        </div>

                                                    </div>
                                                </div>
                                                {{-- <div class="my-2 separator"></div> --}}
                                                {{-- <div class="px-5 menu-item">
                                                    <a href="{{ route('profile.show') }}" class="px-5 menu-link">Mi
                                                        perfil</a>
                                                </div> --}}
                                                <div class="px-5 menu-item">
                                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                                        @csrf
                                                        <a href="{{ route('logout') }}"
                                                            @click.prevent="$root.submit();" class="px-5 menu-link">
                                                            Cerran sesión
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center d-lg-none ms-2"
                                            title="Show header menu">
                                            <div class="btn btn-icon btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                                                id="kt_header_menu_mobile_toggle">
                                                <i class="ki-outline ki-burger-menu-2 fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            @if ($presionado == 33)
                                @livewire('crm.panel-mensajes')
                            @else
                                @switch($presionado)
                                    @case(0)
                                        @livewire('panel-inicio.ver-panel')
                                    @break

                                    @case(2)
                                        @livewire('roles.lista-roles')
                                    @break

                                    @case(3)
                                        @livewire('calls-center.lista-call')
                                    @break

                                    @case(4)
                                        @livewire('operativos.lista-operativo')
                                    @break

                                    @case(5)
                                        @livewire('calendario')
                                    @break

                                    @case(6)
                                        @livewire('empresas.lista-empresas')
                                    @break

                                    @case(7)
                                        @livewire('clientes.lista-clientes')
                                    @break

                                    @case(8)
                                        @livewire('users.lista-user')
                                    @break

                                    @case(9)
                                        @livewire('area.list-area')
                                    @break

                                    @case(10)
                                        @livewire('tesoreria.lista-tesoreria')
                                    @break

                                    @case(11)
                                        @livewire('cobranza.lista-cobranza')
                                    @break

                                    @case(12)
                                        @livewire('tratamientos.lista-tratamientos')
                                    @break

                                    @case(13)
                                        @livewire('recepcionista.lista-recepcion')
                                    @break

                                    @case(15)
                                        @livewire('inventario.lista-inventario')
                                    @break

                                    @case(16)
                                        @livewire('mensajeria.ver-chats')
                                    @break

                                    @case(17)
                                        @livewire('tesoreria.lista-tesoreria')
                                    @break

                                    @case(18)
                                        @livewire('registros.lista-registros')
                                    @break

                                    @case(19)
                                        @livewire('estadistica.nueva-estadistica')
                                    @break

                                    @case(20)
                                        @livewire('inmuebles.lista-inmuebles')
                                    @break

                                    @case(21)
                                        @livewire('marketing.marketing')
                                    @break

                                    @case(25)
                                        @livewire('rh.listarh')
                                    @break

                                    @case(26)
                                        @livewire('inventario.comprar-secundario')
                                    @break

                                    @case(27)
                                        @livewire('tesoreria.egreso')
                                    @break

                                    @case(28)
                                        @livewire('inventario.compra-productos')
                                    @break

                                    @case(29)
                                        @livewire('reportes.mi-registro')
                                    @break

                                    @case(30)
                                        @livewire('operativos.pagos-cliente', ['idoperativo' => $idoperativo])
                                    @break

                                    @case(31)
                                        @livewire('operativos.informacion-cliente', ['idoperativo' => $idoperativo])
                                    @break

                                    @case(32)
                                        @livewire('tutoriales.lista-tutoriales')
                                    @break

                                    @case(33)
                                        @livewire('crm.panel-mensajes')
                                    @break

                                    @case(34)
                                        @livewire('calls-center.lista-llamadas')
                                    @break

                                    @case(35)
                                        @livewire('recepcionista.lista-agendados')
                                    @break

                                    @case(36)
                                        @livewire('administracion.panel-ventas')
                                    @break

                                    @case(37)
                                        @livewire('administracion.deudas')
                                    @break

                                    @case(38)
                                        @livewire('i-a.nodos')
                                    @break

                                    @case(39)
                                        @livewire('almacen.inventario')
                                    @break

                                    @case(40)
                                        @livewire('almacen.lista-general')
                                    @break

                                    @case(41)
                                        @livewire('mbq.lista-mbq')
                                    @break

                                    @case(50)
                                        @livewire('recepcionista.gestion-citas')
                                    @break

                                    @case(55)
                                        @livewire('inventario.crear-traspaso')
                                    @break

                                    @case(70)
                                        @livewire('mensajeria.llamadas-internas')
                                    @break

                                    @case(109)
                                        @livewire('estadistica.estadistica-citas')
                                    @break

                                    @case(120)
                                        @livewire('estadistica.estadistica-llamadas')
                                    @break

                                    @case(121)
                                        @livewire('estadistica.estadistica-ventas')
                                    @break

                                    @case(122)
                                        @livewire('estadistica.estadistica-tratamientos')
                                    @break

                                    @case(123)
                                        @livewire('almacen.traspaso-productos')
                                    @break

                                    @case(125)
                                        @livewire('administracion.panel-anterior')
                                    @break

                                    @case(126)
                                        @livewire('calls-center.lista-rendimiento')
                                    @break

                                    @default
                                        <div class="alert alert-info">Seleccione una opción válida del menú.</div>
                                @endswitch
                            @endif

                        </div>
                        <div class="py-4 footer d-flex justify-content-center align-items-center" id="kt_footer">
                            <div class="text-center">
                                <div class="text-dark">
                                    <span class="text-gray-400 fw-semibold me-1">Created by</span>
                                    <a href="https://www.facebook.com/profile.php?id=61575212400434&mibextid=ZbWKwL"
                                        target="_blank"
                                        class="text-muted text-hover-primary fw-semibold me-2 fs-6">DigitBol</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat"
                data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
                data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
                data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
                @livewire('i-a.conversation')
            </div>
        </div>
    @else
        @if (Auth::user()->estado == 'Activo')
            @livewire('i-a.nodos')
        @else
            <style>
                .card {
                    background: #f8bbd0;
                    border: none;
                    border-radius: 20px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                    padding: 2rem 3rem;
                    text-align: center;
                }
            </style>
            <div class="card">
                <h3 class="mb-3 fw-bold text-dark">Página o sistema no disponible</h3>
                <p class="mb-0 text-dark">Contacte con el administrador de la empresa.</p>
            </div>
        @endif

    @endif
</div>
