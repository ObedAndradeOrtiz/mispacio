<div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <div class="card">

        <div class="mt-2 ml-4 mr-4 d-flex">
            <button type="button"
                class="mr-4 {{ $botonRecepcion === 'planillasemanal' ? 'btn btn-primary' : 'btn btn-outline-primary' }}"
                wire:click="$set('botonRecepcion','planillasemanal')" style="flex:1;">
                <div style="display: flex;">
                    PLANILLA DE PERSONAL
                </div>
            </button>
            <button type="button"
                class="mr-4 {{ $botonRecepcion === 'usuarios' ? 'btn btn-primary' : 'btn btn-outline-primary' }}"
                wire:click="$set('botonRecepcion','usuarios')" style="flex:1;">
                <div style="display: flex;">
                    USUARIOS
                </div>
            </button>
            <button type="button"
                class="mr-4 {{ $botonRecepcion === 'planillas' ? 'btn btn-primary' : 'btn btn-outline-primary' }}"
                wire:click="$set('botonRecepcion','planillas')" style="flex:1;">
                <div style="display: flex;">
                    PLANILLA DE SUELDOS
                </div>
            </button>

        </div>
        @if ($botonRecepcion == 'citas')
            <div style="display: flex;">
                <div style="flex: 1;">
                    @livewire('estadistica.citas-diario')
                </div>
                <div style="flex: 1;">
                    @livewire('estadistica.citas-semanal')
                </div>
            </div>
            <div style="display: flex;">
                <div style="flex: 1;">
                    @livewire('estadistica.citas-semanal-agendados')
                </div>
                <div style="flex: 1;">
                    @livewire('estadistica.citas-mensual')
                </div>
            </div>
        @endif
        @if ($botonRecepcion == 'llamadas')
            @livewire('estadistica.llamadas-diario')
            @livewire('estadistica.llamadas-semanal')
            @livewire('estadistica.llamadas-semanal-agendados')
            @livewire('estadistica.llamadas-semanal-asistidos')
        @endif
        @if ($botonRecepcion == 'usuarios')
            @livewire('users.lista-user')
        @endif
        @if ($botonRecepcion == 'planillas')
            @livewire('planilla.lista-planilla')
        @endif
        @if ($botonRecepcion == 'planillasemanal')
            @livewire('planilla-semanal')
        @endif

    </div>
</div>
