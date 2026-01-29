<div class="mt-4 section-body">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="shadow-lg tab-pane active card" id="Student-all">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Título -->
                        <div class="w-100">
                            <h4 class="mb-3">Lista de clientes:</h4>
                        </div>
                    </div>
                    <div class="mt-4 card-options">
                        @livewire('clientes.crear-cliente')
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-3 shadow-sm card rounded-4">
                        <div class="mb-3 row g-3 align-items-center">
                            <!-- Icono de búsqueda y campo -->
                            <div class="col-auto">
                                <button class="p-2 btn btn-outline-secondary rounded-circle" id="search-icon"
                                    type="button" aria-label="Buscar">
                                    <i class="fas fa-search fs-5"></i>
                                </button>
                            </div>
                            <div class="col">
                                <input type="text" id="search-input" class="form-control form-control-lg rounded-3"
                                    style="transition: all 0.3s ease;"
                                    placeholder="Buscar cliente por nombre o teléfono..." wire:model="busqueda"
                                    aria-label="Campo de búsqueda de cliente">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="fecha-inicio" class="form-label fw-semibold">Rango</label>
                                <select name="" class="form-select" id="" wire:model='rango'>
                                    <option value="">Todos</option>
                                    <option value="fecha">Por fecha</option>
                                </select>
                            </div>
                            <!-- Filtro de fecha: Desde -->
                            <div class="col-md-4">
                                <label for="fecha-inicio" class="form-label fw-semibold">Desde</label>
                                <input type="date" id="fecha-inicio" class="form-control" wire:model="fechaInicioMes"
                                    style="font-size: 14px;" aria-label="Fecha de inicio">
                            </div>

                            <!-- Filtro de fecha: Hasta -->
                            <div class="col-md-4">
                                <label for="fecha-actual" class="form-label fw-semibold">Hasta</label>
                                <input type="date" id="fecha-actual" class="form-control" wire:model="fechaActual"
                                    style="font-size: 14px;" aria-label="Fecha final">
                            </div>
                        </div>
                    </div>

                    <div class="py-3 table-responsive ">
                        <!-- Tabla de clientes -->
                        <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>FECHA REGISTRO</th>
                                    <th>EDAD</th>
                                    <th>CI</th>
                                    <th>TELÉFONO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $lista)
                                    @if ($lista->estado == $actividad)
                                        <tr>
                                            <td>{{ $lista->id }}</td>

                                            <td>{{ $lista->name }}</td>
                                            <td>{{ $lista->created_at }}</td>
                                            <td>{{ $lista->edad }}</td>
                                            <td>{{ $lista->ci }}</td>
                                            <td>{{ $lista->telefono }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @livewire('clientes.editar-cliente', ['iduser' => $lista->id], key($lista->id))
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Paginación -->
                        <div class="py-2 d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
