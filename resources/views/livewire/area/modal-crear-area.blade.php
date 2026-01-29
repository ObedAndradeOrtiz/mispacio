<div>
    <a class="nav-link" href="#" style="" wire:click="$set('crear',true)">
       
        <span class="item-name">Nueva sucursal</span>
    </a>

    <x-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nueva sucursal
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de sucursal:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="area">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Direccion de sucursal:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="direccion">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Telefono principal:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="telefono">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            value="{{Auth::user()->name}}">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove wire:target="guardartodo">Registrar</label>
                <span class="" wire:loading wire:target="guardartodo">Guardando...</span>

        </div>
    </x-modal>
</div>
