<div>
    <form>
        <div class="form-group">
            <label class="form-label" for="">NOMBRE COMPLETO</label>
            <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                wire:model.defer="nombre">
        </div>
        <div class="form-group">
            <label class="form-label" for="">EMAIL</label>
            <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                wire:model.defer="email">
        </div>
        <div class="form-group">
            <label class="form-label" for="">TELEFONO</label>
            <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                wire:model.defer="telefono">
        </div>
        <div class="form-group">
            <label class="form-label" for="">CANTIDAD DE ENTRADAS</label>
            <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                wire:model="cantidad">
        </div>
        <div>
            @if (!is_null($cantidad) && $cantidad != '')
                <h1>EL MONTO A CANCELAR ES: <strong>{{ $cantidad * 70 }} Bs.</strong></h1>
            @else
                <h1>Por favor ingrese una cantidad válida.</h1>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label" for="">EVENTO A ASISTIR</label>
            <select name="" id="" wire:model="eventoseleccionado">
                <option value="">SELECCIONAR EVENTO</option>
                <option value="evento1">EVENTO 1 (27/08/2024)</option>
                <option value="evento2">EVENTO 2(OTRA FECHA)</option>
            </select>
        </div>
        <button class="btn btn-success" wire:click="comprar">Acceder a la venta</button>
    </form>
</div>
