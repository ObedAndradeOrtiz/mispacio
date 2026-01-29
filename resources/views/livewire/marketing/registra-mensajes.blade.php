<div>
    <div class="" > <label for="" class="btn btn-success" wire:click="$set('crear',true)">Registra
            mensajes</label>
    </div>
    <x-modal wire:model.defer="crear" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Agregar conteo de mensajes
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="">Seleccione la cuenta Comercial:</label>
                                <select class="form-select" id="" wire:model="cuentacomercial"
                                    style="width:100%;">
                                    @foreach ($cuentascomerciales as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombrecuenta }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="">Coloque la fecha del conteo:</label>
                                <input type="date" wire:model='fecha' style="width:100%;">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="">Copiar texto de CSV de facebook aqui:</label>
                    </div>
                    <div class="">

                        <textarea name="" id="" cols="50" rows="10" wire:model='texto' style="width:100%;"></textarea>
                    </div>

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" style="background-color: green;" class="btn btn-success" wire:click="guardartodo"
                wire:loading.remove wire:target="guardartodo">Cargar datos</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
</div>
