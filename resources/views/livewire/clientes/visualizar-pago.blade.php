<div>
    <title>Pagos {{$usuario->name}}</title>
    @php
        $total_pagos = DB::table('pagos')
            ->where('iduser', $idu)
            ->orderBy('fechainicio')
            ->get();
    @endphp
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div style="margin:5%;">
        <div style="display: flex; justify-content:center;" class="font-medium text-gray-900">
            Comprobante de pagos: <label for="" style="margin-left:1%; color: rgb(82, 82, 82);">  {{$usuario->name}}</label>
        </div>
        <div>
         
        </div>
        <div class="mt-4 text-sm text-gray-600">
            <form>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                            <thead>
                                <tr class="ligth">
                                    <th>Cantidad de Pago</th>
                                    <td>Archivo</td>
                                    <th>Fecha de pago</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                               

                                @foreach ($total_pagos as $item)
                                    <tr>
                                        <td>{{ $item->cantidad }}</td>
                                        @if ($item->rutaarchivo)
                                            <td><label class="btn btn-success"
                                                    wire:click="descargarArchivo('{{ $item->rutaarchivo }}')"
                                                    class="btn btn-success">Ver archivo</label>
                                            </td>
                                        @else
                                            <td><label class="btn btn-danger">No existe</label></td>
                                        @endif
                                        @if ($item->estado == 'Activo')
                                            <td>{{ $item->fechainicio }}</td>
                                        @else
                                            <td>{{ $item->fechapagado }}</td>
                                        @endif
                                        @if ($item->estado == 'Activo')
                                            <td><span class="badge bg-warning">Pendiente</span></td>
                                        @else
                                            <td><span class="badge bg-success">Pagado</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    <div style="display: flex; justify-content:center;">
    <img src="{{asset('assets/images/bbc.png')}}" width="10%" alt="">
    </div>
    </div>
    <x-modal wire:model.defer="openAreaImage"> 
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900">
            Imagen seleccionada:
        </div>
        <div class="mt-4 text-sm text-gray-600">
            <img src="{{asset($rutaImagen)}}" alt="">
        </div>
        <div class="flex flex-row justify-center px-1 py-1 bg-gray-100 text-right">
            <label type="submit" class="btn btn-light mr-2" wire:click="$set('openAreaImage',false)">Volver</label>
        </div>
    </div>
    </x-modal>
</div>