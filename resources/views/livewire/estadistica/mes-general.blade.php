<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">INGRESOS DE CAJA DINAMICO</h4>
    <div>
        <div class="" style="display: flex; font-size: 0.8vw;">
            <select wire:model="mesSeleccionado" wire:change="obtenerDiasDelMes">

                @foreach ($meses as $numeroMes => $nombreMes)
                    <option value="{{ $numeroMes }}">{{ $nombreMes }}</option>
                @endforeach
            </select>

            {{-- <button class="ml-2 btn btn-success" wire:click="actualizarGrafico">Actualizar Grafico</button> --}}
        </div>

    </div>
    <div wire:loading>
        Cargando...
    </div>
    <div>
        <canvas id="ingresodinamico2"></canvas>
    </div>
</div>
