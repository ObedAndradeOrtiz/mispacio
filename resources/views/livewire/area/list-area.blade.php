<div class="py-0 conatiner-fluid content-inner mt-n5 fadeIn third">
    <link rel="stylesheet" href="../../assets/css/hope-ui.min.css?v=2.0.0" />
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between ">
                        <div class="header-title">
                            <h4 class="card-title"></h4>
                        </div>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda">
                        @livewire('area.crear-area')
                        <br>
                    </div>
                    <div class="card-header d-flex">
                        @if ($actividad == 'Activo')
                            <button type="button" class="mr-4 btn btn-primary"
                                wire:click="$set('actividad','Activo')">Activos</button>
                            <button type="button" class="btn btn-outline-danger"
                                wire:click="$set('actividad','Inactivo')">Desactivados</button>
                        @endif
                        @if ($actividad == 'Inactivo')
                            <button type="button" class="mr-4 btn btn-outline-primary"
                                wire:click="$set('actividad','Activo')">Activos</button>
                            <button type="button" class="btn btn-danger"
                                wire:click="$set('actividad','Inactivo')">Desactivados</button>
                        @endif
                    </div>
                    <div class="px-0 card-body">
                        <div class="table-responsive">

                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>Area</th>
                                        <th>Telefono</th>
                                        <th>#Ticket</th>
                                        <th>Estado</th>
                                        <th>Creador</th>
                                        <th style="min-width: 100px">ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($areas as $lista)
                                        <tr>
                                            <td>{{ $lista->area }}</td>
                                            <td>{{ $lista->telefono }}</td>


                                            <td>{{ $lista->ticket }}</td>
                                            @if ($lista->estado == 'Activo')
                                                <td><span class="badge bg-primary">Activo</span></td>
                                            @else
                                                <td><span class="badge bg-danger">Inactivo</span></td>
                                            @endif
                                            <td>{{ $lista->responsable }}</td>
                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    @livewire('area.editar-area', ['area' => $lista], key($lista->id))
                                                </div>
                                            </td>
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
