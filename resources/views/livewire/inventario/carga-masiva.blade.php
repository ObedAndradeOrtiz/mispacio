<div>
    <label class="mr-1 btn btn-success" wire:click="$set('crear',true)"> <i class="fas fa-file-excel"></i></label>
    <x-sm-modal wire:model.defer="crear">
        <!-- Encabezado del Modal -->
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Subir inventario desde un archivo Excel
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <p>
                    Utiliza este formulario para cargar productos en el sistema mediante un archivo Excel.
                    Asegúrate de seleccionar la sucursal y una fecha para guardar el inventario antes de subir el
                    archivo.
                </p>

                <!-- Formulario de Carga de Excel -->
                <form id="formularioExcel">
                    <!-- Selección de Sucursal -->
                    <div class="mb-4 form-group">
                        <label for="sucursalSelect" class="block font-medium text-gray-700">Sucursal:</label>
                        <select id="sucursalSelect"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-green-500 focus:border-green-500">
                            <option value="">Selecciona una sucursal</option>
                            @foreach ($areas as $empresa)
                                <option value="{{ $empresa->area }}">{{ $empresa->area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selección de Fecha -->
                    <div class="mb-4 form-group">
                        <label for="fechaSeleccionada" class="block font-medium text-gray-700">Fecha de
                            inventario:</label>
                        <input type="date" id="fechaSeleccionada" wire:model="fechaseleccionada"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Subida de Archivo -->
                    <div class="mb-4 form-group">
                        <label for="inputArchivo" class="block font-medium text-gray-700">Archivo Excel:</label>
                        <input type="file" id="inputArchivo" accept=".xls,.xlsx"
                            class="w-full text-gray-600 border-gray-300 rounded shadow-sm focus:ring-green-500 focus:border-green-500">
                        <p class="mt-1 text-xs text-gray-500">Acepta formatos .xls y .xlsx</p>
                    </div>
                </form>
            </div>
        </div>
        <div>
            @if ($cantidadactualizada != 0)
                <p>
                    Se han actualizado un total de : {{ $cantidadactualizada }} productos.
                </p>
            @endif
        </div>
        <!-- Pie de Modal con Botón de Acción -->
        <div class="flex justify-end px-6 py-4 bg-gray-100">
            <button id="btnEnviar" style="background-color: green;"
                class="px-4 py-2 text-white bg-green-600 rounded shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Registrar Excel
                <i class="ml-2 fas fa-file-excel"></i>
            </button>
        </div>
    </x-sm-modal>
    <!-- Select en HTML -->
    {{-- <script>
        document.getElementById('btnEnviar').addEventListener('click', function() {
            var archivo = document.getElementById('inputArchivo').files[0];
            var select = document.getElementById("sucursalSelect");
            var sucu = select.options[select.selectedIndex].text;
            var reader = new FileReader();
            reader.onload = function(event) {
                var data = new Uint8Array(event.target.result);
                var workbook = XLSX.read(data, {
                    type: 'array'
                });
                var sheetName = workbook.SheetNames[0];
                var sheet = workbook.Sheets[sheetName];
                var htmlTable = XLSX.utils.sheet_to_html(sheet);
                // document.getElementById('tablaDatos').innerHTML = htmlTable;
                console.log("Valor de la sucursal:", sucu);
                Livewire.emit('datosRecibidos', {
                    tablaHTML: htmlTable,
                    sucursal: sucu
                });
            };
            reader.readAsArrayBuffer(archivo);
        });
    </script> --}}
    <script>
        document.getElementById('btnEnviar').addEventListener('click', function() {
            const archivo = document.getElementById('inputArchivo').files[0];
            if (!archivo) return;

            const select = document.getElementById("sucursalSelect");
            const sucu = (select.options[select.selectedIndex].text || '').trim();

            const reader = new FileReader();
            reader.onload = function(event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                const sheetName = workbook.SheetNames[0];
                const sheet = workbook.Sheets[sheetName];

                // Array de filas: [ [nombre, cantidad, precio], ... ]
                const rows = XLSX.utils.sheet_to_json(sheet, {
                    header: 1,
                    blankrows: false,
                    defval: ''
                });

                // opcional: quitar header si existe
                // rows.shift();

                // CHUNK para no reventar Livewire (200 filas por envío)
                const chunkSize = 200;
                for (let i = 0; i < rows.length; i += chunkSize) {
                    Livewire.emit('datosRecibidos', {
                        rows: rows.slice(i, i + chunkSize),
                        sucursal: sucu,
                        isLast: (i + chunkSize) >= rows.length
                    });
                }
            };

            reader.readAsArrayBuffer(archivo);
        });
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Agrega un atributo data-sucursal al botón de enviar -->

</div>
