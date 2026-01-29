<div>
    <div class="card">
        <div class="card-body">
            <ul class="nav justify-content-center nav-pills">
                <li class="nav-item" style="flex: 1;">
                    <label class="nav-link {{ $tipoingreso === 'ingresoexterno' ? 'nav-link active' : 'nav-link' }}"
                        wire:click="$set('tipoingreso','ingresoexterno')">Caja general</label>
                </li>
                <li class="nav-item" style="flex: 1;">
                    <label class="nav-link {{ $tipoingreso === 'gastointerno' ? 'nav-link active' : 'nav-link' }}"
                        wire:click="$set('tipoingreso','gastointerno')">Panel Gastos</label>
                </li>
            </ul>
            @if ($tipoingreso == 'ingresoexterno')
                @livewire('tesoreria.menu')
            @endif

            @if ($tipoingreso == 'gastointerno')
                @livewire('tesoreria.egreso-interno')
            @endif
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script>
        function exportToExcel() {
            // Obtener el elemento de la tabla
            var table = document.getElementById("miTabla-users");

            // Crear un libro de Excel
            var wb = XLSX.utils.table_to_book(table, {
                sheet: "Sheet1"
            });

            // Guardar el libro de Excel en un archivo
            XLSX.writeFile(wb, "caja-usuarios.xlsx");
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
</div>
