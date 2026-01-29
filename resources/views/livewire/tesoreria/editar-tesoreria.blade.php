<div>
    <a wire:click="$set('openArea',true)" class="mt-4 btn btn-danger d-block rounded">Agregar gasto</a>
        <x-modal wire:model.defer="openArea">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    Añadir gasto a: {{$areapri->area}}
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="exampleInputdate">Fecha de gasto:</label>
                            <input type="date" class="form-control" id="exampleInputdate" value="2000-01-01"
                                wire:model.defer="fechainicio">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Monto del gasto:</label>
                            <input type="number" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="monto">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Especifique el gasto (Opcional):</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="comentario">
                        </div>
        
                        <div class="form-group">
                            <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                                value="{{ Auth::user()->name }}">
                        </div>
                    </form>
                </div>
            </div>
    
            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
                <label type="submit" class="btn btn-success" wire:click="confirmar">Guardar</label>
            </div>
        </x-modal>
</div>
