<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">INGRESOS Y GASTOS DE CAJA DINAMICO</h4>
    <div>
        <div class="" style="display: flex; font-size: 0.8vw;">
            <label for="fecha-inicio mr-2">Desde:</label>
            <input style="font-size: 0.8vw;" class="mr-2" type="date" id="fecha-inicio" wire:model="fechaInicioMes"
                wire:change="actualizarGrafico">
            <label for="fecha-actual mr-2">Hasta:</label>
            <input style="font-size: 0.8vw;" class="mr2" type="date" id="fecha-actual" wire:model="fechaActual"
                wire:change="actualizarGrafico">
            <button class="ml-2 btn btn-success" wire:click="actualizarGrafico">Actualizar Grafico</button>
        </div>

    </div>

    <div wire:loading>
        Cargando... <!-- Puedes mostrar una animación de carga aquí -->
    </div>

    <div>
        <canvas id="ingresodinamico"></canvas>
    </div>
</div>
