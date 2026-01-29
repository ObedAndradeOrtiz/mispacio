<div>
    <button class="ml-1 btn btn-primary" wire:click="$set('creartransaccion',true)"><span style="color: white;"
            wire:click.prevent.stop>NUEVA
            TRANSACCIÓN</span></button>
    <x-modal wire:model.defer="creartransaccion">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Registrar transacción
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>

                </form>
            </div>
        </div>
        {{-- <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                wire:target="guardartodo">GUARDAR</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div> --}}
    </x-modal>
</div>
