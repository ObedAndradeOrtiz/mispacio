<div>
    @php
        $registrotraspasototal = DB::table('registroinventarios')
            ->where('motivo', 'traspaso')
            ->where('sucursal', 'ilike', '%' . $areaseleccionada . '%')
            ->where('iduser', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('nombreproducto', 'ilike', '%' . $busqueda . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->get();
        $registrotraspaso = DB::table('registroinventarios')
            ->where('motivo', 'traspaso')
            ->where('sucursal', 'ilike', '%' . $areaseleccionada . '%')
            ->where('iduser', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('nombreproducto', 'ilike', '%' . $busqueda . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();
    @endphp
    <div class="container-fluid">
        <div class="row">
            <!-- Filtros con diseño responsivo -->
            <div class="col-md-3 ">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h5 class="card-title">Filtros</h5>
                        <div class="row">
                            <!-- Filtro de Sucursal -->
                            <div class="mb-3 ">
                                <label for="sucursal">Sucursal:</label>
                                <select wire:model="areaseleccionada" class="form-select form-control-sm">
                                    <option value="">Todas</option>
                                    @foreach ($areas as $lista)
                                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro Fecha Desde -->
                            <div class="mb-3 ">
                                <label for="fecha-inicio">Desde:</label>
                                <input type="date" id="fecha-inicio" wire:model="fechaInicioMes"
                                    class="form-control form-control-sm">
                            </div>

                            <!-- Filtro Fecha Hasta -->
                            <div class="mb-3 ">
                                <label for="fecha-actual">Hasta:</label>
                                <input type="date" id="fecha-actual" wire:model="fechaActual"
                                    class="form-control form-control-sm">
                            </div>

                            <!-- Filtro de Responsable -->
                            <div class="mb-3 ">
                                <label for="responsable">Responsable:</label>
                                <select wire:model="usuarioseleccionado" class="form-select form-control-sm">
                                    <option value="">Todos</option>
                                    @foreach ($users as $lista)
                                        <option value="{{ $lista->id }}">{{ $lista->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <div class="mb-2 ml-4">
                            <h3>REGISTRO DE PRODUCTOS TRASPASADOS</h3>
                        </div>
                        <div class="mt-2" style="border-radius: 5px;">
                            <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda"
                                placeholder="Buscar Producto...">
                        </div>
                        <div class="mb-2 ml-4">
                            <label for="">Se estan mostrando: {{ $registrotraspaso }} traspasos.</label>
                        </div>
                        <div class="py-3 table-responsive ">
                            <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th>PRECIO</th>
                                        <th>CANTIDAD</th>
                                        <th>SUCURSAL ENVIADO</th>
                                        <th>SUCURSAL RECIBIDO</th>
                                        <th>FECHA</th>
                                        <th>RESPONSABLE</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registrotraspasototal as $item)
                                        <tr>
                                            <td>{{ $item->nombreproducto }}</td>
                                            <td>{{ $item->precio }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>{{ $item->sucursal }}</td>
                                            <td>{{ $item->modo }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            @php
                                                $name = DB::table('users')->where('id', $item->iduser)->value('name');
                                            @endphp
                                            <td>{{ $name }}</td>
                                            <td> <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="ELIMINAR"
                                                    data-original-title="Edit"
                                                    wire:click="eliminarTrapaso({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-4">
                            <div class="py-3 table-responsive ">
                                <h3>IMPRESIONES DE PRODUCTOS TRASPASADOS</h3>
                                <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ORIGEN</th>
                                            <th>DESTINO</th>
                                            <th>FECHA</th>
                                            <th>ACCION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                        @foreach ($registroimpresiones as $item)
                                            <tr>
                                                <td>{{ $item->sucursal_origen }}</td>
                                                <td>{{ $item->sucursal_destino }}</td>
                                                <td>{{ $item->fecha }}</td>


                                                <td>
                                                    <label class="btn btn-success"
                                                        wire:click="verTraspaso({{ $item->id }})">Ver</label>

                                                    <a class="mt-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="IMPRIMIR" data-original-title="Edit"
                                                        wire:click="imprimirTrapaso({{ $item->id }})">

                                                        <span class="ms-1" style="font-size: 8px;">Imprimir</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{ $registroimpresiones->links() }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>



    <x-modal wire:model="verbau">
        @php
            $impresiontras = DB::table('traspasostexts')->where('id', $this->impresionticket)->first();
        @endphp
        <div class="card">
            <div class="card-header">
                <label class="form-label" for="">VISTA PREVIA DE IMPRESION</label>
                <div class="card-options">
                    @if ($impresiontras)
                        <a class="mt-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="IMPRIMIR" data-original-title="Edit"
                            wire:click="imprimirTrapaso({{ $impresiontras->id }})">

                            <span class="ms-1" style="font-size: 8px;">Imprimir</span>
                        </a>
                    @endif

                </div>
            </div>
            <div class="card-body">
                @if ($impresiontras)
                    <textarea name="" id="" cols="100" rows="30">{{ $impresiontras->texto }}</textarea>
                @endif


            </div>
        </div>
    </x-modal>
</div>
