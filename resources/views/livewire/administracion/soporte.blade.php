<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start" class="py-2 menu-item">
    <span class="menu-link menu-center" wire:click="$set('crear',true)">
        <span class="menu-icon me-0">
            <i class="ki-outline ki-briefcase fs-2x"></i>
        </span>
        <span class="menu-title">Soporte</span>
    </span>
    <x-sm-modal wire:model.defer="crear">
        <div>


            @if ($mostrarOpciones)
                <div class="p-3 mt-3 border">
                    <div style="display: flex; justify-content:center;">
                        <p><strong>¿Hola en qué puedo ayudarte? 😉</strong></p>
                    </div>

                    <div class="mb-4">

                    </div>
                    <button class="mb-2 btn btn-info w-100" wire:click="seleccionarOpcion('sucursal')">📍 Cambiar de
                        Sucursal</button>
                    {{-- <button class="btn btn-danger w-100" wire:click="seleccionarOpcion('venta')">🗑️ Eliminar
                        Venta</button> --}}
                </div>
            @endif

            @if ($opcionSeleccionada === 'sucursal')
                <div class="p-3 mt-3 border">
                    <p><strong>Selecciona una sucursal (MAX 2 por día):</strong></p>
                    @foreach ($sucursales as $sucursal)
                        <button class="mb-2 btn btn-secondary w-100" wire:click="cambiarSucursal({{ $sucursal->id }})">
                            {{ $sucursal->area }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if ($opcionSeleccionada === 'venta')
                <div class="p-3 mt-3 border">
                    <p><strong>Ingresa el ID de la venta a eliminar:</strong></p>
                    <input type="text" class="mb-2 form-control" wire:model="ventaId">
                    <button class="btn btn-danger w-100" wire:click="eliminarVenta">Eliminar Venta</button>
                </div>
            @endif

            @if ($mensaje)
                <div class="mt-3 alert alert-success">{{ $mensaje }}</div>
            @endif
        </div>

    </x-sm-modal>
</div>
