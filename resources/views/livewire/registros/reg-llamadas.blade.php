<div>
    @php
        $misllamadalista = DB::table('calls')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->get();
        $cantidad = DB::table('calls')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('estado', '!=', 'llamadas')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->get();
        $misagendadoslista = DB::table('calls')
            ->where('telefono', 'ilike', '%' . $busquedaag . '%')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('estado', '!=', 'llamadas')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->get();
        $llamadastotal = DB::table('calls')
            ->where('telefono', 'ilike', '%' . $busquedatot . '%')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $usuarioseleccionado . '%')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->get();
        $fechaInicioMes = date('Y-m-d', strtotime($fechaInicioMes));
        $fechaActual = date('Y-m-d', strtotime($fechaActual));
        $miscitaslista = DB::table('operativos')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('fecha', '<=', $fechaActual)
            ->get();
        $miscitaslistacount = DB::table('operativos')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('fecha', '<=', $fechaActual)
            ->count();

    @endphp
    @php
        $misllamada = DB::table('calls')
            ->where('telefono', 'ilike', '%' . $busquedatot . '%')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $usuarioseleccionado . '%')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->count();
        $remarketing = DB::table('registrollamadas')

            ->where('sucursal', 'ilike', '%' . $areaseleccionada . '%')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->count();
        $misagendados = DB::table('calls')
            ->where('telefono', 'ilike', '%' . $busquedaag . '%')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('estado', '!=', 'llamadas')
            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->count();
        $fechaInicioMes = date('Y-m-d', strtotime($fechaInicioMes));
        $fechaActual = date('Y-m-d', strtotime($fechaActual));
        $miscitas = DB::table('operativos')
            ->where('area', 'ilike', '%' . $areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('fecha', '<=', $fechaActual)
            ->count();

    @endphp
    <div class="flex-wrap mt-2 ml-4 mr-4 d-flex" style="display: flex;">
        <div class="form-group" style="margin-right: 5%;">
            <label>Sucursal: </label>
            <select wire:model="areaseleccionada">
                <option value="">Todas</option>
                @foreach ($areas as $lista)
                    <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                @endforeach
            </select>
        </div>
        <div class="mr-4">
            <label for="fecha-inicio">Desde:</label>
            <input type="date" id="fecha-inicio" wire:model="fechaInicioMes">
        </div>

        <div class="ml-4 mr-4">
            <label for="fecha-actual">Hasta:</label>
            <input type="date" id="fecha-actual" wire:model="fechaActual">
        </div>
        <div class="form-group" style="margin-right: 5%;">
            <label>Responsable: </label>
            <select wire:model="usuarioseleccionado">
                <option value="">Todos</option>
                @foreach ($users as $lista)
                    <option value="{{ $lista->name }}">{{ $lista->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mb-2 ml-4">
        <h3>REGISTRO DE LLAMADAS AGENDADAS</h3>
    </div>
    <div class="mt-2" style=" border-radius: 5px;">
        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busquedaag"
            placeholder="Buscar numero...">
    </div>
    <div class="mb-2 ml-4">
        <label for="">Se estan mostrando: {{ $misagendados }} llamadas agendadas.</label>
    </div>
    <div class="table-responsive">
        <table id="mitablaregistros1" class="table table-striped" role="grid" data-bs-toggle="data-table">
            <thead style="font-size: 1vw;">
                <tr>
                    <th>ID</th>
                    <th>CLIENTE</th>
                    <th>NUMERO</th>
                    <th>FECHA Y HORA</th>
                    <th>RESPONSABLE</th>
                    <th>SUCURSAL</th>
                </tr>
            </thead>
            <tbody style="font-size: 1vw;">

                @foreach ($misagendadoslista as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->empresa }}</td>
                        <td>{{ $item->telefono }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->responsable }}</td>
                        <td>{{ $item->area }}</td>
                    </tr>
                @endforeach




            </tbody>
        </table>
    </div>
    <div class="mb-2 ml-4">
        <h3>REGISTRO DE LLAMADAS CREADAS</h3>
    </div>
    <div class="mt-2" style=" border-radius: 5px;">
        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busquedatot"
            placeholder="Buscar numero...">
    </div>
    <div class="mb-2 ml-4">
        <label for="">Se estan mostrando: {{ $misllamada }} llamadas nuevas creadas.</label>
    </div>
    <div class="table-responsive">
        <table id="mitablaregistros1" class="table table-striped" role="grid" data-bs-toggle="data-table">
            <thead style="font-size: 1vw;">
                <tr>
                    <th>ID</th>
                    <th>CLIENTE</th>
                    <th>NUMERO</th>
                    <th>FECHA Y HORA</th>
                    <th>RESPONSABLE</th>
                    <th>SUCURSAL</th>
                </tr>
            </thead>
            <tbody style="font-size: 1vw;">

                @foreach ($llamadastotal as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->empresa }}</td>
                        <td>{{ $item->telefono }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->responsable }}</td>
                        <td>{{ $item->area }}</td>
                    </tr>
                @endforeach




            </tbody>
        </table>
    </div>
</div>
