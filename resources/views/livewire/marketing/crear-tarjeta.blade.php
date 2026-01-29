<div>

    <button class="mb-2 mr-4 btn btn-primary justify-content-end" wire:click="$set('creartarjeta',true)"
        wire:click.prevent.stop><span style="color: white;">NUEVA TARJETA</span></button>
    <x-modal wire:model.defer="creartarjeta" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVA TARJETA
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">NOMBRE DE TARJETA</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="name">
                    </div>
                    @error('name')
                        <label style="color:firebrick"> No coloco nombre</label>
                        <br>
                    @enderror
                    <div class="form-group">
                        <label class="form-label" for="">NUMERO DE TARJETA</label>
                        <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="numero">
                    </div>
                    @error('numero')
                        <label style="color:firebrick"> No se coloco numero</label>
                        <br>
                    @enderror
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">DUEÑO DE TARJETA:</label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="elegido">
                            <option value="">Seleccionar operario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('elegido')
                        <label style="color:firebrick"> No selecciono ningun dueño</label>
                        <br>
                    @enderror
                    <div class="form-group">
                        <label class="form-label" for="">SALDO INCIAL</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="saldo">
                    </div>
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">BANCO:</label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="banco">
                            <option value="" disabled selected>Selecciona un banco</option>
                            <option value="BMSC">(BMSC) Banco Mercantil Santa Cruz</option>
                            <option value="BCB">(BCB) Banco de Crédito de Bolivia</option>
                            <option value="BU">(BU) Banco Unión</option>
                            <option value="BNB">(BNB) Banco Nacional de Bolivia</option>
                            <option value="BG">(BG) Banco Ganadero</option>
                            <option value="BCP">(BCP) Banco Central del Peru</option>
                            <option value="BEC">(BEC) Banco Economico</option>
                            <option value="BS">(BS) Banco Sol</option>
                            <option value="BVS">(BVS) Banco Visa</option>
                            <option value="BF">(BF)Banco Fie</option>
                        </select>

                    </div>
                    @error('banco')
                        <label style="color:firebrick"> No selecciono ningun banco</label>
                        <br>
                    @enderror
                    <div class="form-group">
                        <label class="form-label" for="">COMENTARIO</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="comentario">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                wire:target="guardartodo">Crear</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
</div>
