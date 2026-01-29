<div>
    <button class="ml-4 btn btn-primary" wire:click="$set('creartransaccion',true)" wire:click.prevent.stop><span
            style="color: white; ">
            <h6>AUMENTO A TARJETA</h6>
        </span></button>
    <x-modal wire:model.defer="creartransaccion" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVO AUMENTO
            </div>
            <div class="table-responsive">
                <table id="mitablaregistros1" class="table table-striped" role="grid">
                    <thead>
                        <tr>


                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>
                                <label class="form-label">TARJETA RECEPTORA:</label>

                            </td>
                            <td>
                                <select wire:model="tarjetap" style="font-size: 0.7vw;">
                                    <option value="">NINGUNA</option>
                                    @foreach ($tarjetas as $lista)
                                        <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">CANTIDAD DE AUMENTO</label>

                            </td>
                            <td>
                                <input type="number" style="font-size: 0.7vw;" wire:model="cantidaaumnento">
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green" wire:click="guardartodo"
                wire:loading.remove wire:target="guardartodo">Crear</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
</div>
