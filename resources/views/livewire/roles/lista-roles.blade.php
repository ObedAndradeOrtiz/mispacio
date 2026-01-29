<div>
    <div class="card">
        <div class="flex-wrap card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="mb-0 card-title">Permiso de Roles</h4>
            </div>
            <div class="">
                @livewire('roles.crear-rol')
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Administrador</th>
                            <th>Call Center</th>
                            <th>Clientes</th>
                            <th>Empleados</th>
                            <th>Tratamientos</th>
                            <th>Recepcion</th>
                            <th>Inventario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $item)
                            <tr>
                                <td>
                                    {{ $item->rol }}
                                </td>
                                <?php
                                $rolesderol = DB::table('roles_vistas')
                                    ->where('idrol', $item->id)
                                    ->orderBy('id', 'asc')
                                    ->get();
                                ?>
                                @foreach ($rolesderol as $rol)
                                    <td class="text-center">
                                        @if ($rol->estado == 'Activo')
                                            <input type="checkbox" wire:click="guardartodo({{ $rol->id, }})" checked>
                                        @else
                                            <input type="checkbox" wire:click="guardartodo({{ $rol->id }})">
                                        @endif

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center">

                </div>
            </div>
        </div>
    </div>
</div>
