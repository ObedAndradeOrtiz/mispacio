<div>
    <div class="mt-2 container-fluid">
        <div class="border-0 shadow-sm card">
            <div class="card-body">
                <div class="mr-2 header-title">
                    <h4 class="card-title">Tratamientos</h4>
                </div>
                <div class="d-flex">
                    <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda"
                        placeholder="Busque el tratamiento...">
                    @livewire('tratamientos.crear-tratamiento')
                </div>

                <div class="px-4">
                    <div class="py-3 table-responsive ">
                        <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                            <thead class="thead-dark">
                                <tr class="ligth">
                                    <th>Pacientes</th>
                                    <th>Tratamiento</th>
                                    <th>Descripcion</th>
                                    <th>Costo Bs.</th>
                                    <th>Estado</th>
                                    <th>Tiempo estimado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <style>
                                    td {
                                        max-width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                        white-space: nowrap;
                                    }
                                </style>
                                @foreach ($tratamientos as $lista)
                                    <tr>
                                        @php
                                            $pacientes = DB::table('historial_clientes')
                                                ->where('idtratamiento', $lista->id)
                                                ->count();
                                        @endphp
                                        <td>{{ $pacientes }}</td>
                                        <td>{{ $lista->nombre }}</td>
                                        <td>{{ $lista->descripcion }}</td>
                                        <td>{{ $lista->costo }}</td>
                                        <td>{{ $lista->estado }}</td>
                                        <td>{{ $lista->hora }}</td>
                                        <td>
                                            @livewire('tratamientos.vista-tratamiento', ['idtratamiento' => $lista->id], key($lista->id))
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{ $tratamientos->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
