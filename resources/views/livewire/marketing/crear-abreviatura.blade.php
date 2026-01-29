<div>
    <div>
        <button class="ml-4 mr-4 btn btn-primary" wire:click="$set('crear',true)" wire:click.prevent.stop><span
                style="color: white;">REGISTRAR ABREVIATURA</span></button>
        <x-modal wire:model.defer="crear" wire:click.prevent.stop>
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    NUEVA ABREVIATURA
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        <div class="row">

                            <div class="cold-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="">ABREVIATURA</label>
                                    <input type="text" class="form-control" id="texto"
                                        oninput="convertirAMayusculas()" wire:model.defer="abreviatura">
                                </div>
                            </div>
                            <div class="ml-4 cold-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="">SELECCION DE SUCURSAL PERTENECIENTE</label>
                                    <select class="form-select" id="" wiere:model="areaseleccionada">
                                        @foreach ($areas as $item)
                                            <option value="{{ $item->id }}">{{ $item->area }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
            <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
                <label type="submit" style="background-color: green;" class="btn btn-success" wire:click="guardartodo"
                    wire:loading.remove wire:target="guardartodo">GUARDAR</label>
                <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
            </div>
        </x-modal>
    </div>

</div>
