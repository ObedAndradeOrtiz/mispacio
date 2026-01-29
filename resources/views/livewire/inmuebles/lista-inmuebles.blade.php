<div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.mini.min.js"></script>
    <div class="card">
        <div class="card-header d-flex justify-content-between ">
            <div class="header-title">
                <h4 class="card-title"></h4>
            </div>
            <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda">
            <div class="justify-end">
                @livewire('inmuebles.crear-inmuebles')
            </div>

            <br>
        </div>
        <style>

        </style>
        {{-- <div class="flex flex-row justify-end px-2 py-2 mt-4 mr-4">
            <button class="ml-2 mr-2 btn btn-success" onclick="exportToExcel()">Exportar a
                Excel</button>
            <div>
                @livewire('inventario.carga-masiva')
            </div>
        </div> --}}

        <style>
            .boton-container {
                display: flex;
                /* justify-content: space-between; */
            }

            .boton {
                padding: 10px 20px;
                font-size: 16px;
                /* Utilizamos unidades de medida relativas */
                width: 30%;
            }

            /* Aplicamos estilos diferentes para pantallas más pequeñas */
            @media screen and (max-width: 600px) {
                .boton {
                    font-size: 9px;
                    width: 22%;
                }
            }
        </style>
        <div class="flex flex-row justify-end px-2 py-2 mt-4 mr-4">
            {{-- <button class="ml-2 mr-2 btn btn-warning"id="descargarExcelSaldos">Exportar solo saldos</button> --}}
            <button class="ml-2 mr-2 btn btn-success"id="descargarExcel">Exportar a
                Excel</button>

            <div>
                @livewire('inventario.carga-masiva-activos')
            </div>
        </div>
        <div class="card-header d-flex justify-content-between ">
            <div class="form-group" style="margin-right: 5%;">
                <label>Sucursal: </label>
                <select wire:model="sucursal">
                    <option value="">Seleccione sucursal</option>
                    @foreach ($areas as $lista)
                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="px-4 card-body">
            <div class="table-responsive" wire:loading.lazy>

                <table id="mitabla-i" class="table table-striped" role="grid" data-bs-toggle="data-table">
                    <thead>
                        <tr class="ligth">
                            <th>SUCURSAL</th>
                            <th>AREA</th>
                            <th>TIPO</th>
                            <th>NOMBRE</th>
                            <th>DETALLE</th>
                            <th>ESTADO</th>
                            <th>CANTIDAD</th>
                            <th>PRECIO</th>
                            <th>FECHA ADQUIRIDO</th>
                            <th>ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <style>
                            td {
                                max-width: 200px;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;
                            }
                        </style>
                        @foreach ($productoslist as $lista)
                            <tr>
                                <td>{{ $lista->sucursal }}</td>
                                <td>{{ $lista->area }}</td>
                                <td>{{ $lista->tipo }}</td>
                                <td>{{ $lista->nombre }}</td>
                                <td>{{ $lista->descripcion }}</td>
                                <td>{{ $lista->estado }}</td>
                                <td>{{ $lista->cantidad }}</td>
                                <td>{{ $lista->precio }}</td>
                                <td>{{ $lista->fecha }}</td>
                                <td>
                                    <div class="flex align-items-center list-user-action">
                                        @livewire('inmuebles.editar-inmuebles', ['producto' => $lista], key($lista->id))
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $productoslist->links() }}
            </div>
        </div>
    </div>
    <div id="sucursalData" data-sucursal="<?php echo htmlspecialchars($sucursal); ?>"></div>
    <script>
        document.getElementById('descargarExcel').addEventListener('click', function() {
            var sucursal = document.getElementById('sucursalData').getAttribute('data-sucursal');
            var fecha = @json($fecha);
            var url = 'https://spamiora.ddns.net/api/inmuebles/' + encodeURIComponent(sucursal);
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tratamientos = data.map(item => ({
                        sucursal: item.sucursal,
                        area: item.area,
                        tipo: item.tipo,
                        nombre: item.nombre,
                        detalle: item.descripcion,
                        estado: item.estado,
                        cantidad: item.cantidad,
                        precio: item.precio,
                        fecha: item.fecha,
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
