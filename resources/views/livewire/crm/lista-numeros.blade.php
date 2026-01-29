<div>
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Inicio</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Miora</a></li>
                        <li class="breadcrumb-item active" aria-current="page">CRM</li>
                    </ol>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 0) active @endif" data-toggle="tab"
                            href="#admin-Dashboard" wire:click="setOpcion(0)">CRM WhatsApp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 1) active @endif" data-toggle="tab"
                            href="#admin-Activity" wire:click="setOpcion(1)">Números</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mt-4 section-body">
        <div class="container-fluid">
            @if ($opcion == 0)
                <div class="row">
                    <div class="col-md-3">
                        <div class="card ">
                            <div class="row">
                                <div class="flex-wrap align-items-center">
                                    <div class="mt-2 ml-2 mr-4">
                                        <div class="d-flex">
                                            <strong>
                                                <h3 style="font-size: 24px;" class="ml-3">Crm Miora</h3>
                                            </strong>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <strong><h3>Sucursales</h3></strong>
                                
                                {{-- <button class="btn btn-success" style="width: 100%;" wire:click="startSession()">Agregar
                                    Nro. WhatsApp</button>

                                <div id="qr-container" style="margin-top: 20px;">
                                    @if ($qrCode)
                                        <img src="{{ $qrCode }}" alt="Escanea este código QR para iniciar sesión"
                                            style="width:300px;height:300px;">
                                    @else
                                        <div id="loading" style="display: none;">
                                            <p>Cargando QR...</p>
                                        </div>
                                    @endif
                                </div>
                                @if (session()->has('error'))
                                    <div style="color: red;">{{ session('error') }}</div>
                                @endif --}}
                            </div>
                            @push('scripts')
                                <script>
                                    Livewire.on('session-started', (event) => {
                                        // Mostrar el mensaje de carga cuando la sesión comienza
                                        document.getElementById('loading').style.display = 'block';
                                    });

                                    Livewire.on('qr-received', (event) => {
                                        // Ocultar el mensaje de carga cuando el QR se recibe
                                        document.getElementById('loading').style.display = 'none';
                                    });
                                </script>
                            @endpush
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="text-sm text-gray-600">
                                <form>

                                    <h2 style="font-size: 18px;" class="mt-2 ml-1"> <strong>Mensajería</strong> </h2>

                                    <div class="table-responsive">
                                        <table id="mitablaregistros1" class="table table-striped" role="grid"
                                            data-bs-toggle="data-table">
                                            <thead>
                                                <tr>
                                                    <th>CATEGORIA</th>
                                                    <th>NUMERO</th>
                                                    <th>MENSAJE</th>
                                                    <th>ACCIÓN</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
