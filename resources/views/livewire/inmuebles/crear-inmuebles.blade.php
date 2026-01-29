<div>
    <button class="ml-4 btn btn-primary" wire:click="$set('crear',true)"><span style="color: white; font-size: 24px;">+
        </span></button>
    <x-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nuevo inmueble
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="nombre">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Descripcion:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="descripcion">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Cantidad:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="cantidad">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Precio (Bs.):</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1" wire:model.defer="precio">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Sucursal perteneciente:</label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="sucursal">
                            <option>Seleccionar sucursal</option>
                            @foreach ($empresas as $empresa)
                                <option>{{ $empresa->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Imagen del producto:</label>
                        <input class="form-control" type="file" wire:model.defer="image">
                    </div>

                    @if ($image)
                        @if ($image->getClientOriginalExtension() === 'jpg' || $image->getClientOriginalExtension() === 'png')
                            <img class="mt-4" src="{{ $image->temporaryUrl() }}" alt="">
                        @endif
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            value="{{ Auth::user()->name }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                wire:target="guardartodo">Registrar</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>

        </div>
    </x-modal>
</div>
