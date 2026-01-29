<div>

    <button class="btn btn-primary" wire:click="$set('crear',true)" style="display:flex;"><span
            style="color: white; font-size: 24px;">Carga
            masiva
        </span><svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M14.1213 11.2331H16.8891C17.3088 11.2331 17.6386 10.8861 17.6386 10.4677C17.6386 10.0391 17.3088 9.70236 16.8891 9.70236H14.1213C13.7016 9.70236 13.3719 10.0391 13.3719 10.4677C13.3719 10.8861 13.7016 11.2331 14.1213 11.2331ZM20.1766 5.92749C20.7861 5.92749 21.1858 6.1418 21.5855 6.61123C21.9852 7.08067 22.0551 7.7542 21.9652 8.36549L21.0159 15.06C20.8361 16.3469 19.7569 17.2949 18.4879 17.2949H7.58639C6.25742 17.2949 5.15828 16.255 5.04837 14.908L4.12908 3.7834L2.62026 3.51807C2.22057 3.44664 1.94079 3.04864 2.01073 2.64043C2.08068 2.22305 2.47038 1.94649 2.88006 2.00874L5.2632 2.3751C5.60293 2.43735 5.85274 2.72207 5.88272 3.06905L6.07257 5.35499C6.10254 5.68257 6.36234 5.92749 6.68209 5.92749H20.1766ZM7.42631 18.9079C6.58697 18.9079 5.9075 19.6018 5.9075 20.459C5.9075 21.3061 6.58697 22 7.42631 22C8.25567 22 8.93514 21.3061 8.93514 20.459C8.93514 19.6018 8.25567 18.9079 7.42631 18.9079ZM18.6676 18.9079C17.8282 18.9079 17.1487 19.6018 17.1487 20.459C17.1487 21.3061 17.8282 22 18.6676 22C19.4969 22 20.1764 21.3061 20.1764 20.459C20.1764 19.6018 19.4969 18.9079 18.6676 18.9079Z"
                fill="currentColor"></path>
        </svg></button>
    <div id="tablaDatos"></div>
    <x-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nuevo o actualizar activos {{ $sucursal }}
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form id="formularioExcel"> <!-- Agrega un ID al formulario -->
                    {{-- <div class="form-group">
                        <div>
                            <label for="sucursalSelect">Selecciona una sucursal:</label>
                            <select id="sucursalSelect">
                                <option value="">Selecciona una sucursal</option>
                                @foreach ($areas as $empresa)
                                    <option value="{{ $empresa->area }}">{{ $empresa->area }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div> --}}
                    <input type="file" id="inputArchivo" accept=".xls,.xlsx">
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <button id="btnEnviar">Enviar</button> <!-- Elimina el atributo data-sucursal -->
        </div>
    </x-modal>
    <!-- Select en HTML -->
    <script>
        document.getElementById('btnEnviar').addEventListener('click', function() {
            var archivo = document.getElementById('inputArchivo').files[0];
            var reader = new FileReader();
            reader.onload = function(event) {
                var data = new Uint8Array(event.target.result);
                var workbook = XLSX.read(data, {
                    type: 'array'
                });
                var sheetName = workbook.SheetNames[0];
                var sheet = workbook.Sheets[sheetName];
                var htmlTable = XLSX.utils.sheet_to_html(sheet);
                document.getElementById('tablaDatos').innerHTML = htmlTable;
                Livewire.emit('datosRecibidosInm', {
                    tablaHTML: htmlTable,
                });

            };
            reader.readAsArrayBuffer(archivo);
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Agrega un atributo data-sucursal al botón de enviar -->

</div>
