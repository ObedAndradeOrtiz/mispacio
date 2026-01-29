<div>
    <h2>Lista de mensajes y costos por publicidades</h2>
    <div class="flex flex-row justify-end">
        <div>

        </div>
        @livewire('marketing.registra-mensajes')
    </div>
    <div class="table-responsive">
        <table id="mitablaregistros1" class="table table-striped" role="grid" data-bs-toggle="data-table">
            <thead>
                <tr>
                    <th>Abrev</th>
                    <th>Trat</th>
                    <th>Colab</th>
                    <th>Esta</th>
                    <th>Msg</th>
                    <th>Costo</th>
                    <th>Gasto</th>
                    <th>Fecha</th>
                    <th>Cuenta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mensajes as $item)
                    <tr>
                        <td> <label>{{ $item->abreviatura }}</label></td>
                        <td><label>{{ $item->tratamiento }}</label></td>
                        <td><label>{{ $item->colaboracion }}</label></td>
                        <td><label>{{ $item->estado }}</label></td>

                        <td><label>{{ $item->mensajes }}</label></td>

                        <td><label>{{ $item->costo }}</label></td>
                        <td><label>{{ $item->importe }}</label></td>
                        <td><label>{{ $item->fecha }}</label></td>
                        <td><label>{{ $item->idcuenta }}</label></td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
