<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <div class="py-2 card" style="margin: 12px;">
        <div class="col-12">
            <div class="flex-wrap d-flex justify-content-between align-items-center">


                <div class="mb-3" style="flex: 1 0 20%; margin-right: 10px;">
                    @if ($opcion == 1)
                        <button class="btn w-100 btn-primary" wire:click="setOpcion(1)">
                            <i class="fas fa-list-alt"></i>Lista de producción
                        </button>
                    @else
                        <button class="btn btn-outline-primary w-100" wire:click="setOpcion(1)">
                            <i class="fas fa-list-alt"></i>Lista de producción
                        </button>
                    @endif

                </div>
                {{-- <div class="mb-3" style="flex: 1 0 20%;">
                    @if ($opcion == 0)
                        <button class="btn w-100 btn-primary" wire:click="setOpcion(0)">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Lista de almacén
                        </button>
                    @else
                        <button class="btn btn-outline-primary w-100" wire:click="setOpcion(0)">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Lista de almacén
                        </button>
                    @endif
                </div> --}}
                {{-- <div class="mb-3" style="flex: 1 0 20%; margin-right: 10px;">
                    <button class="btn w-100" wire:click="setOpcion(3)"
                        :class="{
                            'btn-outline-primary': {{ $opcion }} != 3,
                            'btn-primary': {{ $opcion }} == 3
                        }">
                        <i class="fas fa-warehouse" style="color: #3498db;"></i> Almacén Central
                    </button>
                </div> --}}
                <div class="mb-3" style="flex: 1 0 20%; margin-right: 10px;">
                    <button class="btn w-100" wire:click="setOpcion(2)"
                        :class="{
                            'btn-outline-primary': {{ $opcion }} != 2,
                            'btn-primary': {{ $opcion }} == 2
                        }">
                        <i class="fas fa-truck"></i>Lista de traspasos
                    </button>
                </div>
            </div>
        </div>

    </div>
    @if ($opcion == 0)
        @livewire('inventario-mensual')
        {{-- <div class="card" style="margin: 15px;">
            <div class="d-flex justify-content-end">
                <button class="btn btn-success d-flex align-items-center"
                    wire:click="$set('crear_transaccion_almacen',true)">
                    <i class="fas fa-plus-circle me-2"></i>
                    Crear nueva producción
                </button>
            </div>

            <div class="card-header">
                <h5 class="mb-0">Lista de productos en almacén por lotes</h5>
            </div>

            @foreach ($lotes as $lote)
                <div class="my-2 card">
                    <div class="card-header">
                        <button class="btn btn-link" type="button" onclick="toggleLote('lote-{{ $lote->id }}')">
                            {{ $lote->nombre . ' - ' }}{{ $lote->fecha }}
                        </button>
                    </div>
                    <div class="card-body" id="lote-{{ $lote->id }}" style="display: none;">
                        <div class="py-3 table-responsive">
                            <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                <thead class="thead-dark">
                                    <tr class="ligth">
                                        <th>Producto</th>
                                        <th>Cant. Creada</th>
                                        <th>Fecha Creada</th>
                                        <th>Cant. Expo</th>
                                        <th>En almacén</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_produccion->where('idlote', $lote->id) as $item)
                                        <tr>
                                            <td>{{ $item->nombreproducto }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>{{ $item->fechacreacion }}</td>
                                            <td>{{ $item->exportado }}</td>
                                            <td>{{ $item->cantidad_actual }}</td>

                                            <td> <a class="btn btn-sm btn-danger"
                                                    wire:click="$emit('eliminarProduccion',{{ $item->id }})">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a></td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach


        </div> --}}
    @endif
    @if ($opcion == 1)
        @livewire('inventario-mensual')
        {{-- <div class="card" style="margin: 15px;">
            <div class="d-flex justify-content-end">
                <!-- Botón con ícono -->
                <button style="margin-left: 15px;" class="btn btn-success d-flex align-items-center"
                    wire:click="$set('crear_lote',true)">
                    <i class="fas fa-plus-circle"></i>
                    Crear nuevo lote
                </button>
            </div>

            <div class="card-body">
                <h2>Lotes de producción</h2>
                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Lote</th>
                                <th>F. Creación</th>
                                <th>Descripción</th>
                                <th>Acción</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_lotes->where('tipo', 'produccion') as $item)
                                <tr>
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->fecha }}</td>
                                    <td>{{ $item->descripcion }}</td>
                                    <td> <a class="btn btn-sm btn-danger"
                                            wire:click="$emit('eliminarLote',{{ $item->id }})">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h2>Lotes de compra local</h2>
                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Lote</th>
                                <th>F. Creación</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_lotes->where('tipo', 'local') as $item)
                                <tr>
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->fecha }}</td>
                                    <td>{{ $item->descripcion }}</td>
                                    <td> <a class="btn btn-sm btn-danger"
                                            wire:click="$emit('eliminarLote',{{ $item->id }})">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
    @endif
    @if ($opcion == 2)
        <div class="card" style="margin: 15px;">
            <div class="d-flex justify-content-end">
                <button onclick="exportarExcel()" class="mb-3 btn btn-success">Exportar a Excel</button>
            </div>

            <div class="card-body">
                <div class="mb-3 row align-items-end">
                    <!-- Buscar producto -->
                    <div class="col-md-4">
                        <label for="busqueda" class="form-label">Buscar producto:</label>
                        <input type="text" id="busqueda" class="form-control" wire:model.debounce.300ms="busqueda"
                            placeholder="Buscar productos...">
                    </div>

                    <!-- Fecha desde -->
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label">Fecha desde:</label>
                        <input type="date" id="fecha_desde" class="form-control" wire:model="fecha_desde">
                    </div>

                    <!-- Fecha hasta -->
                    <div class="col-md-3">
                        <label for="fecha_hasta" class="form-label">Fecha hasta:</label>
                        <input type="date" id="fecha_hasta" class="form-control" wire:model="fecha_hasta">
                    </div>
                </div>


                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Producto</th>
                                <th>Sucursal destino</th>
                                <th>Cantidad</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $suma = 0;
                            @endphp
                            @foreach ($distribucion_realizada as $item)
                                <tr>
                                    <td>{{ $item->nombreproducto }}</td>
                                    <td>{{ $item->modo }}</td>
                                    <td>{{ $item->total_cantidad }}</td>
                                    @php
                                        $suma = $suma + $item->total_cantidad;
                                    @endphp
                                    <td>{{ $item->fecha }}</td>
                                    {{-- <td>{{ $item->descripcion }}</td> --}}

                                </tr>
                            @endforeach
                            <tr>
                                <td>TOTAL</td>

                                <td></td>
                                <td>{{ $suma }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif
    @if ($opcion == 3)
        <div class="card" style="margin: 15px;">
            <div class="d-flex justify-content-end">
                <button onclick="exportarExcel2()" class="mb-3 btn btn-success">Exportar a Excel</button>
            </div>

            <div class="card-body">
                <div class="mb-3 row align-items-end">
                    <!-- Buscar producto -->
                    <div class="">
                        <label for="busqueda" class="form-label">Buscar producto:</label>
                        <input type="text" id="busqueda" class="form-control"
                            wire:model.debounce.300ms="busqueda_lote" placeholder="Buscar productos...">
                    </div>
                </div>
                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap table2">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos_lote as $item)
                                <tr>
                                    <td>{{ $item->nombreproducto }}</td>

                                    <td>{{ $item->total_cantidad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <script>
        function exportarExcel() {
            // Obtener la tabla
            var tabla = document.querySelector("table");

            // Convertir la tabla a un libro de Excel
            var wb = XLSX.utils.table_to_book(tabla, {
                sheet: "Distribución"
            });

            // Guardar el archivo
            XLSX.writeFile(wb, "distribucion.xlsx");
        }
    </script>
    <script>
        function exportarExcel2() {
            // Obtener la tabla usando el selector correcto (por clase)
            var tabla = document.querySelector(".table2");

            if (!tabla) {
                alert("No se encontró la tabla.");
                return;
            }

            // Convertir la tabla a un libro de Excel
            var wb = XLSX.utils.table_to_book(tabla, {
                sheet: "Lista Almacen"
            });

            // Guardar el archivo como Excel
            XLSX.writeFile(wb, "almacen.xlsx");
        }
    </script>

    <script>
        function toggleLote(id) {
            const elemento = document.getElementById(id);
            if (elemento.style.display === "none") {
                elemento.style.display = "block";
            } else {
                elemento.style.display = "none";
            }
        }
    </script>


    <x-modal wire:model.defer="crear_distribucion_almacen">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nueva distribución
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="row">
                        <div class="mb-3 col-md-6 form-group">
                            <label class="form-label">Tipo de lote:</label>
                            <select class="form-control" wire:model="tipo_lote">
                                <option value="">Todas</option>
                                <option value="produccion">Producción</option>
                                <option value="local">Local</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6 form-group">
                            <label class="form-label">Lotes vigentes:</label>
                            <select class="form-control" wire:model="lote_seleccionado">
                                <option value="">Todas</option>
                                @foreach ($lotes as $item)
                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-12 form-group">
                            <label class="form-label">Producto distribuido:</label>
                            <select class="form-control" wire:model.defer="nombre_producto_seleccionado">
                                <option value="">Seleccione un producto</option>
                                @foreach ($productos_almacen as $item)
                                    <option value="{{ $item->id }}">{{ $item->nombreproducto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6 form-group">
                            <label class="form-label">Sucursal a distribuir:</label>
                            <select class="form-control" wire:model.defer="sucursal_seleccionada">
                                <option value="">Seleccione una sucursal</option>
                                @foreach ($areas as $item)
                                    <option value="{{ $item->id }}">{{ $item->area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6 form-group">
                            <label class="form-label">Cantidad a distribuir:</label>
                            <input type="number" class="form-control" wire:model='cantidad_distribuir'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarDistribucion"
                wire:loading.remove>Distribuir</label>
        </div>
    </x-modal>
    <x-modal wire:model.defer="crear_transaccion_almacen">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nueva producción
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="row">
                        <div class="mb-3 col-md-6 form-group">
                            <label class="form-label">Categoría:</label>
                            <select class="form-control" wire:model="nombre_categoria_seleccionado">
                                <option value="">Todas</option>
                                @foreach ($categorias as $item)
                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6 form-group">
                            <label class="form-label">Producto creado:</label>
                            <select class="form-control" wire:model.defer="nombre_producto_seleccionado">
                                <option value="">Seleccione un producto</option>
                                @foreach ($productosdistribucion as $item)
                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="mb-3 form-group">
                        <label class="form-label">Nro de lote:</label>
                        <select class="form-control" wire:model.defer="nombre_lotes_seleccionado">
                            <option value="">Seleccione un lote</option>
                            @foreach ($lotes as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label">Cantidad a almacenar:</label>
                        <input type="number" class="form-control" wire:model.defer="cantidad_creada">

                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label">Fecha de creación:</label>
                        <input type="date" class="form-control" wire:model.defer="f_creacion">

                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Fecha de vencimiento:</label>
                        <input type="date" class="form-control" wire:model.defer="f_vencimiento">

                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Descripción:</label>
                        <input type="text" class="form-control" wire:model.defer="p_descripcion">
                    </div>

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarProduccion"
                wire:loading.remove>Registrar</label>
        </div>
    </x-modal>
    <x-modal wire:model.defer="crear_lote">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nuevo lote
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre o nro de lote:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="nombre_lote">
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Tipo de lote:</label>
                        <select class="form-control" wire:model="tipo_lote">
                            <option value="">Seleccione el tipo de lote</option>
                            <option value="produccion">Producción</option>
                            <option value="local">Local</option>

                        </select>
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Fecha de creación:</label>
                        <input type="date" class="form-control" wire:model.defer="f_creacion_lote">

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Descripción del lote:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="descripcion_lote">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarLote"
                wire:loading.remove>Registrar</label>
        </div>
    </x-modal>
</div>
