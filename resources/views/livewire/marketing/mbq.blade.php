<div>
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 18px;"> <strong>LISTA DE VENTAS ONLINE MISS BEATY QUEEN</strong> </h3>
            <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda">
            <div class="card-options">
                @livewire('marketing.crear-comprador')
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="mitablaregistros1" class="table table-striped" role="grid" data-bs-toggle="data-table">
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>TELEFONO</th>
                            <th>EMAIL</th>
                            <th>CANTIDAD</th>
                            <th>FECHA EVENTO</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
