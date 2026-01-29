<div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('myButton').click();
            }, 1);
        });
    </script>
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900">
            Compra de productos:
        </div>
        <div class="mt-4 text-sm text-gray-600">
            <div style="align-content: center; display:flex; font-size: 1vw;" class="mb-4">
                <div class="flex flex-row justify-start" style="align-items: center; flex:1;">

                </div>
                <div class="ml-2">
                    <label style="font-size: 1vw;">Sucursal: </label>
                    <select class="ml-4 mr-2 form-select-lg" style="font-size: 1vw;" wire:model="sucursalseleccionada">
                        @foreach ($areas as $item)
                            <option value="{{ $item->id }}">{{ $item->area }}</option>
                        @endforeach



                    </select>
                </div>
                <div class="flex flex-row justify-end" style="align-items: center;">
                    <button wire:click="realizarfarmacia" class="btn btn-warning">Comprar</button>
                </div>
            </div>
            <label class="form-label" for="">Cartera de egreso:</label>
            <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="cartera">
                <option value="Caja">Caja central</option>
                <option value="Externo">Externo</option>
            </select>
            <div class="mb-4">
                <label for="">Cantidad de gasto: </label>
                <div style="display: flex;">
                    <input type="number" wire:model="nombre" placeholder="Escriba la cantidad..." style="width: 100%;">
                </div>
                <div class="flex flex-row justify-end mt-2 mr-2" style="align-items: center;">
                    <label style="font-size: 24px;">Modo de pago: </label>
                    <select class="ml-4 form-select-lg" wire:model="modo">
                        <option value="efectivo">Efectivo</option>
                        <option value="qr">QR</option>
                    </select>
                </div>
                {{-- <div class="flex flex-row justify-end mt-2 mr-2" style="align-items: center;">
                    <label style="font-size: 24px;">Total: {{ $pagar }}</label>
                </div> --}}
            </div>
            <div>
                <label for="">Fecha realizado:</label>
                <input type="date" wire:model='fecha'>
            </div>
        </div>
        <div class="table-responsive" style="font-size: 1vw;">
            <table class="table table-bordered">
                <thead style="font-size: 0.7vw;">
                    <tr>
                        <th>PRODUCTO SELECCIONADO</th>
                        <th>CANTIDAD</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.6vw;">

                    @foreach ($cantidades as $id => $cantidad)
                        @php
                            $producto = DB::table('productos')->select('nombre')->where('id', $id)->first();
                        @endphp
                        <tr>
                            @if ($producto)
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $cantidad }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex">
            <input class="mt-1" type="text" wire:model.defer="search" placeholder="Buscar productos..."
                style="width: 100%;">
            <button class="btn btn-warning" wire:click="buscar">Buscar</button>
        </div>

        <div class="table-responsive" style="font-size: 1vw;">
            <table class="table table-bordered">
                <thead style="font-size: 0.7vw;">
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Cantidad</th>

                    </tr>
                </thead>
                <tbody style="font-size: 0.6vw;">
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->cantidad }}</td>
                            <td>
                                <input style="font-size: 1vw;" type="number"
                                    wire:model="cantidades.{{ $producto->id }}" min="0">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- <style>
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
    </script> --}}

</div>
