<div>
    @if ($loadComponent)
        @livewire('calls-center.editar-call', ['idllamada' => $idllamada], key($idllamada))
    @else
        <button class="btn btn-success" wire:click="loadComponent">Cargar botónes</button>
    @endif
</div>
