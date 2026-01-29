<div>
    @if ($loadComponent)
        @livewire('operativos.editar-operativo', ['operativo' => $operativo], key($operativo->id))
    @else
        <button class="btn btn-success d-flex align-items-center" style="font-size: 7px;" wire:click="loadComponent">
            <i class="fas fa-cogs me-2"></i> Opciones
        </button>
    @endif
</div>
