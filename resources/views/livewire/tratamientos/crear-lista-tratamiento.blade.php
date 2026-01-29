<div>
    <button class="btn btn-primary ml-4" wire:click="$set('crear',true)"><span style="color: white; font-size: 24px;">+
        </span></button>
        <x-modal wire:model.defer="crear">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900 py-2">
                    Registrar nuevo paquete
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="">Nombre de paquete:</label>
                            <input type="text" class="form-control" wire:model.defer="nombre">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Descripcion:</label>
                            <input type="text" class="form-control" wire:model.defer="descripcion">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Costo:</label>
                            <input type="number" class="form-control" wire:model.defer="costo">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Selecciona tratamientos:</label>
                            @foreach ($tratamientos as $tratamiento)
                                <div>
                                    <input type="checkbox" wire:model.defer="tratamientosSeleccionados.{{ $tratamiento->id }}" value="{{ $tratamiento->id }}">
                                    <label for="">{{ $tratamiento->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="exampleInputDisabled1">Registrado por:</label>
                            <input type="text" class="form-control" disabled value="{{ Auth::user()->name }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
                <button type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.attr="disabled"
                    wire:target="guardartodo">
                    Guardar
                </button>
                <span wire:loading wire:target="guardartodo">Guardando...</span>
            </div>
        </x-modal>

</div>
