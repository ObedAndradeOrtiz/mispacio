<div>
    <div class="card">

        <div class="mt-2 ml-4 mr-4 d-flex">
            <button type="button"
                class="mr-4 {{ $botonRecepcion === 'citas' ? 'btn btn-primary' : 'btn btn-outline-primary' }}"
                wire:click="$set('botonRecepcion','citas')" style="flex:1;">
                <div style="display: flex;">
                    CITAS
                </div>
            </button>
            <button type="button"
                class="mr-4 {{ $botonRecepcion === 'llamadas' ? 'btn btn-primary' : 'btn btn-outline-primary' }}"
                wire:click="$set('botonRecepcion','llamadas')" style="flex:1;">
                <div style="display: flex;">
                    LLAMADAS
                </div>
            </button>
            {{-- <button type="button"
                class="mr-4 {{ $botonRecepcion === 'usuarios' ? 'btn btn-primary' : 'btn btn-outline-primary' }}"
                wire:click="$set('botonRecepcion','usuarios')" style="flex:1;">
                <div style="display: flex;">
                    USUARIOS
                </div>
            </button> --}}
        </div>
        @if ($botonRecepcion == 'citas')
            @livewire('marketing.estadisticas-citas')
        @endif
        @if ($botonRecepcion == 'llamadas')
            @livewire('marketing.estadisticas-l-lamadas');
        @endif

    </div>
</div>
