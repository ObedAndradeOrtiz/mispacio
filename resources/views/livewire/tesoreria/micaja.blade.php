<div>

    <div class="d-flex flex-column bgi-no-repeat rounded-top"
        style="background-image:url('{{ asset('assets/media/misc/auth-bg.png') }}')">
        <h3 class="mt-10 mb-6 text-white fw-semibold px-9">Ingresos {{ Auth::user()->sucursal }}
            {{-- <span class="opacity-75 fs-8 ps-3">24 reports</span> --}}
        </h3>
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="pb-4 text-white opacity-75 nav-link opacity-state-100 active" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_1">Ingresos</a>
            </li>
            <li class="nav-item">
                <a class="pb-4 text-white opacity-75 nav-link opacity-state-100 " data-bs-toggle="tab"
                    href="#kt_topbar_notifications_2">Detalles</a>
            </li>
            <li class="nav-item">
                <a class="pb-4 text-white opacity-75 nav-link opacity-state-100" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_3">Imprimir</a>
            </li>
            <li class="nav-item">
                <a class="pb-4 text-white opacity-75 nav-link opacity-state-100" data-bs-toggle="tab"
                    href="#kt_topbar_notifications_4">Miss BTQ</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
            <div class="card">
                <div class="gap-2 card-body d-flex justify-content-center align-items-center">
                    <div class="fw-semibold">Ingreso total:</div>
                    <div class="mb-0 h1 text-success">
                        Bs. {{ $total_inventario_g + $total_monto_g + $total_inventario_qr }}
                    </div>
                </div>


                <div class="card-footer">
                    <ul class=" list-unstyled">
                        <li class="">
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs. {{ $total_monto_g }}</strong></div>
                                <div class="float-right"><span class="text-muted">Tratamientos</span>
                                </div>
                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs.
                                        {{ $total_inventario_g + $total_inventario_qr }}</strong></div>
                                <div class="float-right"><span class="text-muted">
                                        Productos</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs. {{ $gastoarea_g }}</strong></div>
                                <div class="float-right"><span class="text-muted">Gastos</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs.
                                        {{ $total_inventario_g + $total_monto_g + $total_inventario_qr - $gastoarea_g }}</strong>
                                </div>
                                <div class="float-right"><span class="text-muted">Ingreso total</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs.
                                        {{ $total_monto_cita_efectivo + $total_inventario_g - $gastoarea_g }}</strong>
                                </div>
                                <div class="float-right"><span class="text-muted">Efectivo en caja</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                    </ul>
                    <div class="mt-2">
                        @if ($existecaja == false)
                            <button style="width: 100%"wire:click="registrarcaja" class="btn btn-primary">Cerrar
                                caja</button>
                        @else
                            <label for="" style="font-size:15px;"><strong>CAJA
                                    CERRADA</strong></label>
                        @endif
                    </div>


                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-center row">
                        <div class="col-6 border-right d-flex flex-column align-items-center">
                            <label class="mb-0">Desde</label>
                            <div class="font-weight-bold">
                                <input style="width: 100%;font-size:10px;" type="date" id="fecha-inicio"
                                    wire:model="fechaInicioMes" class="text-start" onkeydown="return false">
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-column align-items-center">
                            <label class="mb-0">Hasta</label>
                            <div class="font-weight-bold">
                                <input style="width: 100%;font-size:10px;" type="date" id="fecha-actual"
                                    wire:model="fechaActual" class="text-start" onkeydown="return false">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="mb-2 btn btn-warning" style="width: 100%;" wire:click='actualizar'>Actualizar</button>
        </div>
        <div class="tab-pane fade" id="kt_topbar_notifications_2" role="tabpanel">
            <div class="p-3 bg-white border rounded shadow-sm">
                <h5 class="mb-3 text-center text-primary fw-bold">
                    Resumen de Ingresos del Día
                </h5>

                <div class="mb-3 text-center row">
                    <div class="col-md-4">
                        <div class="mb-1 fw-semibold text-muted">Tratamientos QR</div>
                        <div class="text-success fw-bold">Bs. {{ $total_monto_cita_qr }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-1 fw-semibold text-muted">Productos QR</div>
                        <div class="text-success fw-bold">Bs. {{ $total_inventario_qr_g }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-1 fw-semibold text-muted">Total QR</div>
                        <div class="text-success fw-bold">
                            Bs. {{ $total_inventario_qr_g + $total_monto_cita_qr }}
                        </div>
                    </div>
                </div>

                <div class="mb-3 text-center row">
                    <div class="col-md-4">
                        <div class="mb-1 fw-semibold text-muted">Tratamientos Efectivo</div>
                        <div class="text-success fw-bold">Bs. {{ $total_monto_cita_efectivo }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-1 fw-semibold text-muted">Productos Efectivo</div>
                        <div class="text-success fw-bold">Bs. {{ $total_inventario_g }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-1 fw-semibold text-muted">Total Efectivo</div>
                        <div class="text-success fw-bold">
                            Bs. {{ $total_inventario_g + $total_monto_cita_efectivo }}
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <div class="mb-3 text-center row">
                    <div class="col-md-6">
                        <div class="mb-1 fw-semibold text-muted">Turno Mañana</div>
                        <div class="text-dark fw-bold">{{ $turnomanana }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-1 fw-semibold text-muted">Turno Tarde</div>
                        <div class="text-dark fw-bold">{{ $turnotarde }}</div>
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <h6 class="fw-bold text-dark">Ingreso Total General</h6>
                    <div class="h4 text-success fw-bold">
                        Bs.
                        {{ $total_inventario_qr_g + $total_monto_cita_qr + $total_inventario_g + $total_monto_cita_efectivo }}
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Impresión de registro de ingresos</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center row">
                        <div class="col-6 border-right d-flex flex-column align-items-center">
                            <label class="mb-0">Desde</label>
                            <div class="font-weight-bold">
                                <input style="width: 100%;font-size:10px;" type="date" id="fecha-inicio"
                                    wire:model="fechaInicioMes" class="text-start">
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-column align-items-center">
                            <label class="mb-0">Hasta</label>
                            <div class="font-weight-bold">
                                <input style="width: 100%;font-size:10px;" type="date" id="fecha-actual"
                                    wire:model="fechaActual" class="text-start">
                            </div>
                        </div>
                    </div>
                    <button style="width: 100%;" class="mt-4 btn btn-warning" wire:click="imprimirResultado">Imprimir
                        registro actual</button>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="kt_topbar_notifications_4" role="tabpanel">


            <div class="card">
                <div class="card-body">
                    <ul class=" list-unstyled">
                        @php
                            $pagoqr = DB::table('registroinventarios')
                                ->where('sucursal', Auth::user()->sucursal)
                                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                                ->where('modo', 'qr')
                                ->where('motivo', 'mbq')
                                ->sum('cantidad');
                            $pagoefec = DB::table('registroinventarios')
                                ->where('sucursal', Auth::user()->sucursal)
                                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                                ->where('modo', 'efectivo')
                                ->where('motivo', 'mbq')
                                ->sum('cantidad');
                        @endphp
                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs.{{ $pagoqr }}
                                    </strong></div>
                                <div class="float-right"><span class="text-muted">
                                        QR</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs.{{ $pagoefec }} </strong></div>
                                <div class="float-right"><span class="text-muted">Efectivo</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>

                        <li>
                            <div class="clearfix">
                                <div class="float-left"><strong>Bs. {{ $pagoqr + $pagoefec }}
                                    </strong>
                                </div>
                                <div class="float-right"><span class="text-muted">Total</span>
                                </div>

                            </div>
                            <hr style="border: 1px solid #ccc; margin: 10px 0; opacity: 0.7;">

                        </li>
                    </ul>
                    <div class="text-center row">
                        <div class="col-6 border-right d-flex flex-column align-items-center">
                            <label class="mb-0">Desde</label>
                            <div class="font-weight-bold">
                                <input style="width: 100%;font-size:10px;" type="date" id="fecha-inicio"
                                    wire:model="fechaInicioMes" class="text-start" onkeydown="return false">
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-column align-items-center">
                            <label class="mb-0">Hasta</label>
                            <div class="font-weight-bold">
                                <input style="width: 100%;font-size:10px;" type="date" id="fecha-actual"
                                    wire:model="fechaActual" class="text-start" onkeydown="return false">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
