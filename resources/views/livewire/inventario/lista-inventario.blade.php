<div class="col-md-12">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.mini.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <div class="card" style="margin: 2%;">
        <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
            <div class="mt-2 mb-2 header-title">
                <h4 class="card-title">Lista de Inventario {{ $sucursal }}</h4>
            </div>

            @if (Auth::user()->rol != 'Recepcion')
                <div class="flex-wrap gap-2 mt-2 d-flex align-items-center">
                    @livewire('inventario.carga-masiva')
                    @livewire('inventario.crear-producto')
                </div>
            @endif

            <div class="flex-wrap gap-2 mt-3 d-flex align-items-end w-100">
                <div class="form-group me-2">
                    <label>Sucursal:</label>
                    <select wire:model="sucursal" class="form-control">
                        <option value="">Todas</option>
                        @foreach ($areas as $lista)
                            <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group me-2">
                    <label>Vista de inventarios:</label>
                    <select wire:model="vistaproductos" class="form-control">
                        <option value="">Todos</option>
                        <option value="saldo">Solo con saldo</option>
                        <option value="negativo">Con negativo</option>
                        <option value="comparar">Faltantes</option>
                    </select>
                </div>

                <div class="form-group flex-grow-1 me-2">
                    <label>Buscar:</label>
                    <input type="text" class="form-control" wire:model="busqueda" placeholder="Buscar productos...">
                </div>

                <div class="gap-2 d-flex align-items-end">
                    <button class="btn btn-danger" wire:click="vaciar">
                        <i class="fas fa-trash"></i> Vaciar {{ $sucursal }}
                    </button>
                    <button class="btn btn-success" id="descargarExcelSaldos">
                        <i class="fas fa-file-excel"></i> Exportar
                    </button>
                    @if ($sucursal != '')
                        <button class="btn btn-info" wire:click="reindexar">Reindexar</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @if ($vistaproductos != 'comparar')
                <div class="table-responsive" wire:loading.lazy>
                    <table id="mitabla-i" class="table table-striped">
                        <thead>
                            <tr>


                                <th>SUC.</th>
                                <th>ID</th>
                                <th>NOM</th>
                                <th>EXP.</th>
                                <th>D.FALT</th>
                                <th>F.INICIO</th>
                                <th>INICIO</th>
                                <th>COMPRA</th>
                                <th>RECIB</th>
                                <th>TRASP. ENV</th>
                                <th>VENTA</th>
                                <th>GBNT</th>
                                <th>TOTAL</th>
                                <th>SISTEMA</th>
                                <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $inicial = 0;
                                $compras = 0;
                                $recibidos = 0;
                                $enviados = 0;
                                $vendidos = 0;
                                $usados = 0;
                                $sumatotal = 0;
                                $sistema = 0;
                            @endphp
                            @foreach ($productoslist as $lista)
                                <tr>

                                    <td>{{ $lista->sucursal }}</td>
                                    <td>{{ $lista->id }}</td>
                                    <td>{{ $lista->nombre }}</td>
                                    <td>{{ $lista->expiracion }}</td>
                                    @php
                                        $fechaActual = new DateTime();
                                        $fechaFutura = new DateTime($lista->expiracion);
                                        $diferencia = $fechaActual->diff($fechaFutura);

                                    @endphp

                                    <td>{{ $diferencia->days }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lista->fechainicio)->format('d/m/Y') }}</td>
                                    <td>{{ $lista->inicio }}</td>
                                    @php
                                        $inicial = $inicial + $lista->inicio;

                                        $traspaso = DB::table('registroinventarios')
                                            ->where('motivo', 'traspaso')
                                            ->where('idproducto', $lista->id)
                                            ->where('sucursal', $lista->sucursal)
                                            ->whereBetween('fecha', [$lista->fechainicio, $this->fechafinal])
                                            ->sum('cantidad');
                                        $traspasorecibido = DB::table('registroinventarios')
                                            ->where('motivo', 'traspaso')
                                            ->where('nombreproducto', $lista->nombre)
                                            ->where('modo', $lista->sucursal)
                                            ->whereBetween('fecha', [$lista->fechainicio, $this->fechafinal])
                                            ->sum('cantidad');
                                        $compra = DB::table('registroinventarios')
                                            ->where('motivo', 'nuevacompra')
                                            ->where('nombreproducto', $lista->nombre)
                                            ->where('sucursal', $lista->sucursal)
                                            ->whereBetween('fecha', [$lista->fechainicio, $this->fechafinal])
                                            ->sum('cantidad');
                                        $venta = DB::table('registroinventarios')
                                            ->where('motivo', 'compra')
                                            ->where('nombreproducto', $lista->nombre)
                                            ->where('sucursal', $lista->sucursal)
                                            ->whereBetween('fecha', [$lista->fechainicio, $this->fechafinal])
                                            ->sum('cantidad');
                                        $venta2 = DB::table('registroinventarios')
                                            ->where('motivo', 'farmacia')
                                            ->where('nombreproducto', $lista->nombre)
                                            ->where('sucursal', $lista->sucursal)
                                            ->whereBetween('fecha', [$lista->fechainicio, $this->fechafinal])
                                            ->sum('cantidad');

                                        $gabinete = DB::table('registroinventarios')
                                            ->where('motivo', 'personal')
                                            ->where('nombreproducto', $lista->nombre)
                                            ->where('sucursal', $lista->sucursal)
                                            ->whereBetween('fecha', [$lista->fechainicio, $this->fechafinal])
                                            ->sum('cantidad');
                                        $numero = $venta2 + $venta + $gabinete;
                                        $compras = $compras + $compra;
                                        $recibidos = $recibidos + $traspasorecibido;
                                        $enviados = $enviados + $traspaso;
                                        $vendidos = $venta2 + $venta;
                                        $usados = $usados + $gabinete;
                                        $sumatotal =
                                            $sumatotal +
                                            $lista->inicio +
                                            $traspasorecibido +
                                            $compra -
                                            ($traspaso + $venta + $venta2 + $gabinete);
                                        $sistema = $sistema + $lista->cantidad;
                                    @endphp


                                    <td>{{ $compra }}</td>
                                    <td>{{ $traspasorecibido }}</td>
                                    <td>{{ $traspaso }}</td>
                                    <td>{{ $venta2 + $venta }}</td>
                                    <td>{{ $gabinete }}</td>
                                    <td>{{ $lista->inicio + $traspasorecibido + $compra - ($traspaso + $venta + $venta2 + $gabinete) }}
                                    </td>
                                    <td>{{ $lista->cantidad }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @livewire('inventario.editar-producto', ['producto' => $lista], key($lista->id))
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>SUMA TOTAL</td>

                                <td>NOMBRE</td>
                                <td>EXPIRA</td>
                                <td>D.FALT</td>
                                <td>F.INICIO</td>
                                <td>{{ $inicial }}</td>
                                <td>{{ $compras }}</td>
                                <td>{{ $recibidos }}</td>
                                <td>{{ $enviados }}</td>
                                <td>{{ $vendidos }}</td>
                                <td>{{ $usados }}</td>
                                <td>{{ $sumatotal }}</td>
                                <td>{{ $sistema }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    {{ $productoslist->links() }}
                </div>
            @else
                <div class="table-responsive" wire:loading.lazy>
                    <table id="mitabla-i" class="table table-striped">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                {{-- <th>COD.</th> --}}
                                <th>SUC.</th>
                                <th>NOM</th>
                                <th>F.VENC</th>
                                <th>D.FALT</th>
                                <th>F.ANTERIOR</th>
                                <th>F.INICIO</th>
                                <th>INICIO ANT.</th>
                                <th>COMPRA</th>
                                <th>RECIB</th>
                                <th>TRASP. ENV</th>
                                <th>VENTA</th>
                                <th>GBNT</th>
                                <th>SALDO SISTEMA</th>
                                <th>SALDO EXCEL</th>
                                <th>FALT(+)/SOBR(-)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productoslist as $lista)
                                @if (isset($lista->fechaanterior))
                                    <tr>
                                        {{-- <td>
                                {{ $lista->id }}
                            </td> --}}

                                        {{-- <td>{{ $lista->idinventario }}</td> --}}
                                        <td>{{ $lista->sucursal }}</td>
                                        <td>{{ $lista->nombre }}</td>

                                        <td>{{ \Carbon\Carbon::parse($lista->fechaanterior)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lista->fechainicio)->format('d/m/Y') }}</td>
                                        <td>{{ $lista->cantidadanterior }}</td>
                                        @php
                                            $traspaso = DB::table('registroinventarios')
                                                ->where('motivo', 'traspaso')
                                                ->where('nombreproducto', $lista->nombre)
                                                ->where('sucursal', $lista->sucursal)
                                                ->whereBetween('fecha', [$lista->fechaanterior, $lista->fechainicio])
                                                ->sum('cantidad');
                                            $traspasorecibido = DB::table('registroinventarios')
                                                ->where('motivo', 'traspaso')
                                                ->where('nombreproducto', $lista->nombre)
                                                ->where('modo', $lista->sucursal)
                                                ->whereBetween('fecha', [$lista->fechaanterior, $lista->fechainicio])
                                                ->sum('cantidad');
                                            $compra = DB::table('registroinventarios')
                                                ->where('motivo', 'nuevacompra')
                                                ->where('idproducto', $lista->id)
                                                ->whereBetween('fecha', [$lista->fechaanterior, $lista->fechainicio])
                                                ->sum('cantidad');
                                            $venta = DB::table('registroinventarios')
                                                ->where('motivo', 'compra')
                                                ->where('idproducto', $lista->id)
                                                ->where('sucursal', $lista->sucursal)
                                                ->whereBetween('fecha', [$lista->fechaanterior, $lista->fechainicio])
                                                ->sum('cantidad');
                                            $venta2 = DB::table('registroinventarios')
                                                ->where('motivo', 'farmacia')
                                                ->where('nombreproducto', $lista->nombre)
                                                ->where('sucursal', $lista->sucursal)
                                                ->whereBetween('fecha', [$lista->fechaanterior, $lista->fechainicio])
                                                ->sum('cantidad');
                                            $gabinete = DB::table('registroinventarios')
                                                ->where('motivo', 'personal')
                                                ->where('nombreproducto', $lista->nombre)
                                                ->where('sucursal', $lista->sucursal)
                                                ->whereBetween('fecha', [$lista->fechaanterior, $lista->fechainicio])
                                                ->sum('cantidad');
                                            $numero = $venta2 + $venta + $gabinete;
                                        @endphp
                                        <td>{{ $compra }}</td>
                                        <td>{{ $traspasorecibido }}</td>
                                        <td>{{ $traspaso }}</td>
                                        <td>{{ $venta2 + $venta }}</td>
                                        <td>{{ $gabinete }}</td>
                                        <td>{{ $lista->cantidadanterior + $traspasorecibido + $compra - ($traspaso + $venta + $venta2 + $gabinete) }}
                                        </td>
                                        <td>{{ $lista->inicio }}</td>
                                        <td>{{ $lista->cantidadanterior + $traspasorecibido + $compra - ($traspaso + $venta + $venta2 + $gabinete) - $lista->inicio }}
                                        </td>

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    {{ $productoslist->links() }}
                </div>
            @endif
        </div>
    </div>
    <div id="sucursalData" data-sucursal="<?php echo htmlspecialchars($sucursal); ?>"></div>
    <x-sm-modal wire:model.defer="selectfecha">
        <div>
            <label for="">Selecionar fecha para guardar inventario:</label>
            <input type="date" wire:model='fechaseleccionada'>
        </div>
        <div style="text-align: center;">
            <label for="" class="btn btn-success" wire:click='guardarInventario'>Guardar fecha de
                inventario</label>
        </div>
    </x-sm-modal>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('myButton').click();
            }, 1);
        });
    </script>
    <!-- Asegúrate de incluir la librería XLSX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('descargarDiferencias').addEventListener('click', function() {
                // Obtén los datos necesarios
                debugger;
                var sucursal = document.getElementById('sucursalData').getAttribute('data-sucursal');
                var fecha =
                    @json($fecha); // Asegúrate de que $fecha esté correctamente definido en tu backend

                // Construir la URL para la API
                var url = 'https://spamiora.ddns.net/api/porsucursal/' + encodeURIComponent(sucursal);

                // Llamada a la API
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Mapear los datos para crear un array de objetos, incluyendo todas las columnas de la consulta
                        const tratamientos = data.map(item => ({
                            nombre: item.nombre,
                            cantidad: item.cantidad,
                            precio: item.precio,
                            total: item.total,
                            resta: item.resta
                        }));

                        // Crear un nuevo libro de Excel
                        const workbook = XLSX.utils.book_new();
                        const worksheet = XLSX.utils.json_to_sheet(tratamientos);
                        XLSX.utils.book_append_sheet(workbook, worksheet, 'INVENTARIO_' + sucursal);

                        // Generar el archivo Excel
                        const excelBuffer = XLSX.write(workbook, {
                            bookType: 'xlsx',
                            type: 'array'
                        });

                        // Crear un Blob para el archivo Excel
                        const blob = new Blob([excelBuffer], {
                            type: 'application/octet-stream'
                        });

                        // Crear la URL del Blob
                        const url = URL.createObjectURL(blob);

                        // Crear un enlace de descarga
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'DIFERENCIASINVENTARIO_' + sucursal + '-' + fecha + '.xlsx';
                        document.body.appendChild(a);

                        // Simular el clic en el enlace para iniciar la descarga
                        a.click();

                        // Eliminar el enlace y liberar la URL del Blob
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>

    <script>
        document.getElementById('descargarExcelSaldos').addEventListener('click', function() {
            debugger;
            var sucursal = document.getElementById('sucursalData').getAttribute('data-sucursal');
            var fecha = @json($fecha);
            var url = 'https://spamiora.ddns.net/api/saldos/' + encodeURIComponent(sucursal);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tratamientos = data.map(item => ({
                        PRODUCTO: item.nombre,
                        CANTIDAD: item.cantidad,
                        PRECIO: item.precio
                    }));

                    const workbook = XLSX.utils.book_new();
                    const worksheet = XLSX.utils.json_to_sheet(tratamientos);
                    XLSX.utils.book_append_sheet(workbook, worksheet, 'INVENTARIO' + sucursal);
                    const excelBuffer = XLSX.write(workbook, {
                        bookType: 'xlsx',
                        type: 'array'
                    });
                    const blob = new Blob([excelBuffer], {
                        type: 'application/octet-stream'
                    });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    var cad = 'INVENTARIO' + sucursal + '-' + fecha + '.xlsx';
                    a.download = cad;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <script>
        document.getElementById('descargarExcel').addEventListener('click', function() {
            var sucursal = document.getElementById('sucursalData').getAttribute('data-sucursal');
            var fecha = @json($fecha);
            var url = 'https://spamiora.ddns.net/api/tratamientos/' + encodeURIComponent(sucursal);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tratamientos = data.map(item => ({
                        CODIGO: item.idinventario,
                        PRODUCTO: item.nombre,
                        INICIAL: item.inicio,
                        TRASPASOS: item.traspaso,
                        COMPRAS: item.compra,
                        GABINETE: item.gabinete,
                        VENTA: item.venta,
                        TOTAL: item.total,
                        SISTEMA: item.cantidad,
                    }));

                    const workbook = XLSX.utils.book_new();
                    const worksheet = XLSX.utils.json_to_sheet(tratamientos);
                    XLSX.utils.book_append_sheet(workbook, worksheet, 'INVENTARIO' + sucursal);
                    const excelBuffer = XLSX.write(workbook, {
                        bookType: 'xlsx',
                        type: 'array'
                    });
                    const blob = new Blob([excelBuffer], {
                        type: 'application/octet-stream'
                    });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    var cad = 'INVENTARIO-' + sucursal + '-' + fecha + '.xlsx';
                    a.download = cad;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    {{-- <script>
        function exportToExcel() {
            const table = document.getElementById('mitabla-i');
            const data = [];
            // Recorrer las filas del cuerpo de la tabla
            for (let i = 0; i < table.rows.length; i++) {
                const row = [];
                const cells = table.rows[i].querySelectorAll(
                    'td:nth-child(n+3):not(:last-child)'); // Excluir las primeras dos columnas y la última columna
                // Recorrer las celdas de datos de la fila y agregar los textos a la matriz de datos
                for (let j = 0; j < cells.length; j++) {
                    row.push(cells[j].innerText);
                }
                data.push(row);
            }
            // Agregar encabezados personalizados
            const headers = ["Nombre", "Cantidad", "Precio"];
            data.unshift(headers);
            // Eliminar la primera fila si está en blanco
            if (data.length > 0 && data[0].length === 0) {
                data.shift();
            }
            // Crear un libro de Excel utilizando SheetJS
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            // Convertir el libro de Excel en un archivo binario
            const wbout = XLSX.write(wb, {
                bookType: 'xlsx',
                type: 'binary'
            });
            const buf = new ArrayBuffer(wbout.length);
            const view = new Uint8Array(buf);
            for (let i = 0; i < wbout.length; i++) view[i] = wbout.charCodeAt(i) & 0xFF;
            // Crear una instancia de Blob para almacenar los datos del XLSX
            const blob = new Blob([buf], {
                type: 'application/octet-stream'
            });
            // Utilizar FileSaver.js para guardar el archivo
            saveAs(blob, 'tabla_excel.xlsx');
        }
    </script> --}}
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
        }

        #preloader .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #52b1e5;

            border-radius: 50%;

            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script>
        // resources/js/app.js (o cualquier otro archivo JavaScript principal)

        document.addEventListener("livewire:load", function() {
            Livewire.hook('message.sent', function() {
                document.getElementById('preloader').style.display = 'flex';
            });

            Livewire.hook('message.processed', function() {
                document.getElementById('preloader').style.display = 'none';
            });
        });
    </script>
</div>
