<div>
    <div>
        <label for="sucursal">SELECCIONE UNA SUCURSAL:</label>
        <select id="sucursalSelect" wire:model="sucursal">
            <option value="">Seleccione una sucursal</option>
            @foreach ($areas as $empresa)
                <option value="{{ $empresa->area }}">{{ $empresa->area }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <textarea wire:model="textoCSV" rows="10" cols="50"></textarea>
        <div>
            <label for="">INTRODUCIR FECHA INICIAL:</label>
            <input type="date" wire:model="fecha">
        </div>
        <button class="btn btn-success" wire:click="procesarTextoCSV">PROCESAR TEXTO</button>
    </div>

</div>
