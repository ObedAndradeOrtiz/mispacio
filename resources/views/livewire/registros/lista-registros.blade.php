<div class="px-4 mt-4">
    <div class="shadow-sm card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Registros de datos en sistema</h3>
            <div class="card-options">
                <div class="form-group">

                    <select wire:model="botonRecepcion" class="form-select form-select-lg">
                        <option value="citas">Ingresos de tratamientos</option>
                        <option value="producto">Ingresos de Productos</option>
                        <option value="gabinete">Uso de Gabinete</option>
                        <option value="traspaso">Traspaso de Productos</option>
                        <option value="creacion">Creación de Productos</option>
                        <option value="modificacion">Modificación de Productos</option>
                        <option value="compras">Compras</option>
                        <option value="gastos">Gastos</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 card-body">
            <form>
                @if ($botonRecepcion == 'citas')
                    @livewire('registros.reg-pagos')
                @endif
                @if ($botonRecepcion == 'producto')
                    @livewire('registros.reg-producto')
                @endif
                @if ($botonRecepcion == 'gabinete')
                    @livewire('registros.reg-gabinete')
                @endif
                @if ($botonRecepcion == 'gastos')
                    @livewire('registros.reg-gastos')
                @endif
                @if ($botonRecepcion == 'traspaso')
                    @livewire('registros.reg-traspaso')
                @endif
                @if ($botonRecepcion == 'creacion')
                    @livewire('registros.reg-crear')
                @endif
                @if ($botonRecepcion == 'modificacion')
                    @livewire('registros.reg-edicion')
                @endif
                @if ($botonRecepcion == 'compras')
                    @livewire('registros.reg-compras')
                @endif
            </form>
        </div>
    </div>
</div>
