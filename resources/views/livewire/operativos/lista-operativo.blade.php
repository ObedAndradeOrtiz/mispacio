<div>
    <div class="conatiner-fluid content-inner mt-n5 py-0 fadeIn third">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between ">
                            <div class="header-title">
                                <h4 class="card-title"></h4>
                            </div>
                            <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda">
                            <br>
                        </div>
                        <style>
                            .boton-container {
                                display: flex;
                                /* justify-content: space-between; */
                            }

                            .boton {
                                padding: 10px 20px;
                                font-size: 16px;
                                /* Utilizamos unidades de medida relativas */
                                width: 30%;
                            }

                            /* Aplicamos estilos diferentes para pantallas más pequeñas */
                            @media screen and (max-width: 600px) {
                                .boton {
                                    font-size: 9px;
                                    width: 22%;
                                }
                            }
                        </style>
                        <div class="card-header d-flex  boton-container ">
                            @if ($actividad == 'Activo')
                                <button type="button" class=" boton btn btn-outline-warning mr-4"
                                    wire:click="$set('actividad','Pendiente')">Pendientes</button>
                                <button type="button" class=" boton btn btn-primary mr-4"
                                    wire:click="$set('actividad','Activo')">Activos</button>
                                <button type="button" class="boton btn btn-outline-success mr-4"
                                    wire:click="$set('actividad','Confirmado')">Confirmados</button>
                                <button type="button" class=" boton btn btn-outline-danger"
                                    wire:click="$set('actividad','Inactivo')">Desactivados</button>
                            @endif
                            @if ($actividad == 'Pendiente')
                                <button type="button" class=" boton btn btn-warning mr-4"
                                    wire:click="$set('actividad','Pendiente')">Pendientes</button>
                                <button type="button" class=" boton btn btn-outline-primary mr-4"
                                    wire:click="$set('actividad','Activo')">Activos</button>
                                <button type="button" class=" boton btn btn-outline-success mr-4"
                                    wire:click="$set('actividad','Confirmado')">Confirmados</button>
                                <button type="button" class=" boton btn btn-outline-danger"
                                    wire:click="$set('actividad','Inactivo')">Desactivados</button>
                            @endif
                            @if ($actividad == 'Confirmado')
                                <button type="button" class=" boton btn btn-outline-warning mr-4"
                                    wire:click="$set('actividad','Pendiente')">Pendientes</button>
                                <button type="button" class=" boton btn btn-outline-primary mr-4"
                                    wire:click="$set('actividad','Activo')">Activos</button>
                                <button type="button" class=" boton btn btn-success mr-4"
                                    wire:click="$set('actividad','Confirmado')">Confirmados</button>
                                <button type="button" class=" boton btn btn-outline-danger"
                                    wire:click="$set('actividad','Inactivo')">Desactivados</button>
                            @endif
                            @if ($actividad == 'Inactivo')
                                <button type="button" class=" boton btn btn-outline-warning mr-4"
                                    wire:click="$set('actividad','Pendiente')">Pendientes</button>
                                <button type="button" class=" boton btn btn-outline-primary mr-4"
                                    wire:click="$set('actividad','Activo')">Activos</button>
                                <button type="button" class=" boton btn btn-outline-success mr-4"
                                    wire:click="$set('actividad','Confirmado')">Confirmados</button>
                                <button type="button" class=" boton btn btn-danger"
                                    wire:click="$set('actividad','Inactivo')">Desactivados</button>
                            @endif
                        </div>
                        <div class="card-body px-4">
                            <div class="table-responsive">

                                <table id="user-list-table" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead>
                                        <tr class="ligth">

                                            <th>Area</th>
                                            <th>Empresa</th>
                                            <th>Telefono</th>
                                            <th>COMENTARIO</th>
                                            @if ($actividad != 'Activo')
                                                <th>FECHA</th>
                                                <th>HORA</th>
                                            @endif
                                            <th>Estado</th>
                                            <th>Encargado</th>
                                            <th style="min-width: 100px">ACCIÓN</th>
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
                                        @foreach ($operativos as $lista)
                                            <tr>

                                                <td>{{ $lista->area }}</td>
                                                <td>{{ $lista->empresa }}</td>
                                                <td>{{ $lista->telefono }}</td>
                                                <td>{{ $lista->comentario }}</td>
                                                @if ($actividad != 'Activo')
                                                    @php
                                                        setlocale(LC_TIME, 'es_ES'); // Establece la configuración regional para mostrar la fecha en español
                                                        $fecha = $lista->fecha; // la cadena de fecha en formato oro

                                                        $marca_tiempo = strtotime($fecha); // convierte la cadena en una marca de tiempo Unix

                                                        $fecha_formateada = strftime(
                                                            '%A %e de %B del %Y',
                                                            $marca_tiempo,
                                                        ); // formatea la fecha como "Día de la semana D de Mes de Año"
                                                    @endphp
                                                    <td>{{ $lista->fecha }}</td>
                                                    <td>{{ $lista->hora }}</td>
                                                @endif
                                                @if ($lista->estado == 'Pendiente')
                                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                                @endif
                                                @if ($lista->estado == 'Activo')
                                                    <td><span class="badge bg-primary">Activo</span></td>
                                                @endif
                                                @if ($lista->estado == 'Confirmado')
                                                    <td><span class="badge bg-success">Confirmado</span></td>
                                                @endif
                                                @if ($lista->estado == 'Inactivo')
                                                    <td><span class="badge bg-danger">Inactivo</span></td>
                                                @endif
                                                <td>{{ $lista->encargado }}</td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        @livewire('operativos.editar-operativo', ['operativo' => $lista], key('3'))
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="px-6 py-3">
                                    {{ $operativos->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
