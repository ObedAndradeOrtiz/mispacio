<div>
    @php
        $registrotraspasototal = DB::table('registroinventarios')
            ->where('motivo', 'Creacion')
            ->where('sucursal', 'ilike', '%' . $areaseleccionada . '%')
            ->where('iduser', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('nombreproducto', 'ilike', '%' . $busqueda . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->get();
        $registrotraspaso = DB::table('registroinventarios')
            ->where('motivo', 'Creacion')
            ->where('sucursal', 'ilike', '%' . $areaseleccionada . '%')
            ->where('iduser', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('nombreproducto', 'ilike', '%' . $busqueda . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();
    @endphp
    <div class="container-fluid">
        <!-- Filtros de búsqueda y selección -->
        <div class="mb-4 row">
            <div class="col-md-12">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h5 class="card-title">Filtros de búsqueda</h5>
                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <label for="sucursal">Sucursal:</label>
                                <select wire:model="areaseleccionada" class="form-select">
                                    <option value="">Todas</option>
                                    @foreach ($areas as $lista)
                                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="fecha-inicio">Desde:</label>
                                <input type="date" id="fecha-inicio" class="form-control"
                                    wire:model="fechaInicioMes">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="fecha-actual">Hasta:</label>
                                <input type="date" id="fecha-actual" class="form-control" wire:model="fechaActual">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="responsable">Responsable:</label>
                                <select wire:model="usuarioseleccionado" class="form-select">
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
        </div>

        <!-- Título y tabla de registros -->
        <div class="row">
            <div class="col-md-12">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h3 class="card-title">REGISTRO DE PRODUCTOS CREADOS</h3>
                        <div class="mb-2">
                            <div class="input-group" style="border-radius: 5px;">
                                <input type="text" class="form-control" wire:model="busqueda"
                                    placeholder="Buscar Producto...">
                            </div>
                        </div>
                        <div class="mb-2">
                            <span>Se están mostrando: {{ $registrotraspaso }} productos creados.</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" role="grid" data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th>PRECIO</th>
                                        <th>CANTIDAD ASIGNADA</th>
                                        <th>SUCURSAL</th>
                                        <th>FECHA</th>
                                        <th>RESPONSABLE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registrotraspasototal as $item)
                                        <tr>
                                            <td>{{ $item->nombreproducto }}</td>
                                            <td>{{ $item->precio }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>{{ $item->sucursal }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            @php
                                                $name = DB::table('users')->where('id', $item->iduser)->value('name');
                                            @endphp
                                            <td>{{ $name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
