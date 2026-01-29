<div>
    <div class="py-2 card" style="margin: 12px;">
        <div class="col-12">
            <div class="flex-wrap d-flex justify-content-between align-items-center">
                <div class="mb-3" style="flex: 1 0 20%;">
                    @if ($opcion == 0)
                        <button class="btn w-100 btn-primary" wire:click="setOpcion(0)">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Lista de productos
                        </button>
                    @else
                        <button class="btn btn-outline-primary w-100" wire:click="setOpcion(0)">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Lista de productos
                        </button>
                    @endif
                </div>
                <div class="mb-3" style="flex: 1 0 20%; margin-right: 10px;">
                    <button class="btn w-100" wire:click="setOpcion(2)"
                        :class="{
                            'btn-outline-primary': {{ $opcion }} != 2,
                            'btn-primary': {{ $opcion }} == 2
                        }">
                        <i class="fas fa-tags me-2"></i> Categorías
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if ($opcion == 0)
        <div class="card" style="margin: 15px;">
            <div class="d-flex justify-content-end">
                <button style="margin-left: 15px;" class="btn btn-success d-flex align-items-center"
                    wire:click="$set('crearproducto',true)">
                    <i class="fas fa-plus-circle"></i>
                    Crear producto
                </button>
            </div>
            <div class="px-4 row">
                <div class="col-md-6">
                    <label class="mb-0 form-label">Buscar:</label>
                    <input type="text" class="form-control" wire:model="busqueda" placeholder="Buscar productos...">
                </div>
                <div class="col-auto">
                    <label class="mb-0 form-label">Categoría:</label>
                    <select class="form-control" wire:model="categoria_busqueda">
                        <option value="">Todas las categorías</option>
                        @foreach ($this->categorias as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body">
                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listaproductos as $producto)
                                <tr>
                                    <td>
                                        {{ $producto->idproducto }}
                                    </td>
                                    <td>
                                        {{ $producto->nombre }}
                                    </td>
                                    <td>
                                        @if ($producto->categoria)
                                            @php
                                                $nombre_categoria = DB::table('categoria_inventarios')
                                                    ->where('id', $producto->categoria)
                                                    ->value('nombre');
                                            @endphp

                                            {{ $nombre_categoria }}
                                        @endif

                                    </td>
                                    <td>
                                        {{ $producto->descripcion }}
                                    </td>
                                    <td>
                                        {{ $producto->precio }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <a class="btn btn-sm btn-warning"
                                                wire:click="$emit('editarProducto', {{ $producto->id }})">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $listaproductos->links() }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card" style="margin: 15px;">
            <div class="d-flex justify-content-end">
                <!-- Botón con ícono -->
                <button style="margin-left: 15px;" class="btn btn-success d-flex align-items-center"
                    wire:click="$set('crearcategoria',true)">
                    <i class="fas fa-plus-circle"></i>
                    Crear categoría
                </button>
            </div>

            <div class="card-body">
                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorias as $categoria)
                                <tr>
                                    <td>
                                        {{ $categoria->nombre }}
                                    </td>

                                    <td>
                                        {{ $categoria->descripcion }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="btn btn-sm btn-warning"
                                                wire:click="$emit('editarCategoria', {{ $categoria->id }})">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    <x-modal wire:model.defer="crearproducto">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nuevo producto
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="mb-3 form-group">
                        <label class="form-label">Nombre del producto:</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                            wire:model.defer="nombre">
                        @error('nombre')
                            <div class="invalid-feedback">El nombre es inválido</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Categoría:</label>
                        <select class="form-control @error('categoria') is-invalid @enderror"
                            wire:model.defer="categoria">
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <div class="invalid-feedback">La categoría es inválida</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label">Descripción:</label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                            wire:model.defer="descripcion">
                        @error('descripcion')
                            <div class="invalid-feedback">La descripción es inválida</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label">Precio (Bs.):</label>
                        <input type="number" class="form-control @error('precio') is-invalid @enderror"
                            wire:model.defer="precio">
                        @error('precio')
                            <div class="invalid-feedback">El precio es inválido</div>
                        @enderror
                    </div>

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarProducto"
                wire:loading.remove>Registrar</label>
        </div>
    </x-modal>
    <x-modal wire:model.defer="editar_producto">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Editar producto {{ $nombre }}
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="mb-3 form-group">
                        <label class="form-label">Nombre del producto:</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                            wire:model.defer="nombre">
                        @error('nombre')
                            <div class="invalid-feedback">El nombre es inválido</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Categoría:</label>
                        <select class="form-control @error('categoria') is-invalid @enderror"
                            wire:model.defer="categoria">
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <div class="invalid-feedback">La categoría es inválida</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label class="form-label">Descripción:</label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                            wire:model.defer="descripcion">
                        @error('descripcion')
                            <div class="invalid-feedback">La descripción es inválida</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label">Precio (Bs.):</label>
                        <input type="number" class="form-control @error('precio') is-invalid @enderror"
                            wire:model.defer="precio">
                        @error('precio')
                            <div class="invalid-feedback">El precio es inválido</div>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarProducto"
                wire:loading.remove>Registrar</label>
        </div>
    </x-modal>
    <x-modal wire:model.defer="crearcategoria">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nueva categoría
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de la categoría:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="nombre_categoria">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Descripción:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="descripcion_categoria">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarCategoria"
                wire:loading.remove>Registrar</label>
        </div>
    </x-modal>
    <x-modal wire:model="editar_categoria">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Editar categoría
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de la categoría:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="nombre_categoria">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Descripción:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="descripcion_categoria">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="GuardarCategoria"
                wire:loading.remove>Registrar</label>
        </div>
    </x-modal>
</div>
