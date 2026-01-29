<div class="mt-4 section-body">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane active" id="Student-all">
                <div class="card">

                    <div class="card-body">
                        <div>
                            <div class="input-group d-flex">
                                <div class="form-group">
                                    <label for="">Búsqueda por número del cliente:</label>
                                    <input type="number" class="form-control" placeholder="Número del cliente..."
                                        wire:model="busqueda">
                                </div>

                                <div class="ml-4 form-group">
                                    <label for="sucursal">Sucursal:</label>
                                    <select name="sucursal" id="sucursal" wire:model="sucursalseleccionada">
                                        <option value="">Todas</option>
                                        @foreach ($sucursales as $item)
                                            <option value="{{ $item->area }}">{{ $item->area }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="ml-4 form-group">
                                    <label for="ano">Año:</label>
                                    <select name="ano" id="ano" wire:model="anio" style="width: 100px;">

                                        @for ($year = 2023; $year <= 2030; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>

                                </div>

                                <div class="ml-4 form-group">
                                    <label for="mes">Mes:</label>
                                    <select name="mes" id="mes" wire:model="mes">

                                        @php
                                            $meses = [
                                                '01' => 'Enero',
                                                '02' => 'Febrero',
                                                '03' => 'Marzo',
                                                '04' => 'Abril',
                                                '05' => 'Mayo',
                                                '06' => 'Junio',
                                                '07' => 'Julio',
                                                '08' => 'Agosto',
                                                '09' => 'Septiembre',
                                                '10' => 'Octubre',
                                                '11' => 'Noviembre',
                                                '12' => 'Diciembre',
                                            ];
                                        @endphp
                                        @foreach ($meses as $num => $nombre)
                                            <option value="{{ $num }}">{{ $nombre }}</option>
                                        @endforeach
                                    </select>

                                </div>



                                <style>
                                    .form-group {
                                        display: grid;
                                        gap: 8px;
                                        /* Espacio entre el label y el select */
                                    }

                                    .form-group label {
                                        font-weight: bold;
                                        /* Opcional: para resaltar el label */
                                    }

                                    .form-group select {
                                        padding: 8px;
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                    }
                                </style>

                            </div>
                        </div>

                        <div class="float-left">

                        </div>

                    </div>
                </div>
                <div class="px-4 py-2 table-responsive card">
                    <div class="card-options">

                        <button class="ml-2 btn btn-warning d-flex" wire:click='copiarConsultaAlPortapapeles'
                            style="font-size: 1vw;">
                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14.7379 2.76175H8.08493C6.00493 2.75375 4.29993 4.41175 4.25093 6.49075V17.2037C4.20493 19.3167 5.87993 21.0677 7.99293 21.1147C8.02393 21.1147 8.05393 21.1157 8.08493 21.1147H16.0739C18.1679 21.0297 19.8179 19.2997 19.8029 17.2037V8.03775L14.7379 2.76175Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path d="M14.4751 2.75V5.659C14.4751 7.079 15.6231 8.23 17.0431 8.234H19.7981"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path d="M14.2882 15.3584H8.88818" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12.2432 11.606H8.88721" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            Copiar números
                        </button>

                    </div>
                    <div class="card-body">
                        <table class="table mb-0 table-striped text-nowrap">
                            <thead>
                                <tr class="ligth">
                                    <th>SUCURSAL</th>
                                    <th>NOMBRE</th>
                                    <th>TELÉFONO</th>
                                    <th>TRATAMIENTO</th>
                                    <th>TOTAL A PAGAR </th>
                                    <th>ULTIMO PAGO</th>
                                    <th>TOTAL PAGADO</th>
                                    <th>FALTANTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $lista)
                                    <tr>
                                        <td>{{ $lista->sucursal }}</td>
                                        <td>{{ $lista->nombre_cliente }}</td>
                                        <td>{{ $lista->telefono }}</td>
                                        <td>{{ $lista->nombretratamiento }}</td>
                                        <td>{{ $lista->costo }}</td>
                                        <td>{{ $lista->ultima_fecha_pago }}</td>
                                        <td>{{ $lista->total_monto_pagado }}</td>

                                        <td>{{ $lista->deuda }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="py-2 ml-2">
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('myButton').click();
            }, 1);
        });
    </script>
</div>
