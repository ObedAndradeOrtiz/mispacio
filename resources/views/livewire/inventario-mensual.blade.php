<div>
    <style>
        .inv-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: auto;
            max-height: 70vh;
        }

        .inv-table {
            min-width: 2200px;
            /* más ancho para scroll suave */
            border-collapse: separate;
            border-spacing: 0;
        }

        /* ===== HEADER ===== */
        .inv-head th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 20;
        }

        /* ===== COLUMNA PRODUCTO (FIJA IZQUIERDA) ===== */
        .inv-prod {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 30;
            min-width: 320px;
            box-shadow: 2px 0 0 #e5e7eb;
        }

        /* ===== CELDAS DE DÍAS (SCROLL) ===== */
        .inv-cell {
            min-width: 140px;
            background: #fff;
        }

        /* ===== TOTALES (FIJOS DERECHA) ===== */
        .inv-total {
            position: sticky;
            right: 0;
            background: #fff;
            z-index: 30;
            min-width: 120px;
            box-shadow: -2px 0 0 #e5e7eb;
        }

        .inv-total-2 {
            right: 120px;
        }

        .inv-total-3 {
            right: 240px;
        }

        /* ===== INPUTS ===== */
        .inv-input {
            height: 38px;
            font-size: 15px;
            text-align: right;
            padding-right: 12px;
        }

        .inv-read {
            background: #f8fafc;
        }

        /* ===== BADGES ===== */
        .inv-badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 999px;
        }

        .inv-bp {
            background: #2563eb;
            color: #fff;
        }

        .inv-bt {
            background: #111827;
            color: #fff;
        }
    </style>


    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Inventario artesanal — {{ $mes }}</h4>
        </div>

        <div class="btn-group">
            <div class="mb-3" style="max-width:360px;">
                <input type="text" class="form-control" placeholder="Buscar producto artesanal..."
                    wire:model.debounce.400ms="buscar">
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="mesAnterior">◀</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="mesSiguiente">▶</button>
            <button type="button" class="btn btn-primary btn-sm" wire:click="guardar">Guardar</button>
        </div>
    </div>

    <div class="inv-wrap" style="max-height:70vh;">
        <table class="table table-sm table-bordered mb-0 inv-table">
            <thead class="inv-head">
                <tr style="background-color: #111827;">
                    <th class="inv-prod" style="background-color: #ffffff;">Producto</th>

                    @for ($d = 1; $d <= $diasMes; $d++)
                        <th class="text-center inv-cell">
                            <div class="fw-semibold">D{{ $d }}</div>
                            <div class="d-flex justify-content-center gap-1 mt-1">
                                <span class="inv-badge inv-bp">P</span>
                                <span class="inv-badge inv-bt">T</span>
                            </div>
                        </th>
                    @endfor

                    <th class="text-center inv-total inv-total-3">Total P</th>
                    <th class="text-center inv-total inv-total-2">Total T</th>
                    <th class="text-center inv-total">Neto</th>
                </tr>
            </thead>


            <tbody>
                @foreach ($productos as $p)
                    @php
                        // id del producto (productos.id)
                        $pid = (int) $p->id;

                        // totales por fila
                        $totalProd = 0;
                        $totalTras = 0;
                    @endphp

                    <tr wire:key="producto-{{ $pid }}">
                        {{-- Columna producto (sticky) --}}
                        <td class="inv-prod" style="background-color: #ffffff;">
                            <div class="fw-semibold">{{ $p->nombre }}</div>
                            <div class="text-muted" style="font-size:11px">
                                ID: {{ $pid }}
                            </div>
                        </td>

                        {{-- Días del mes --}}
                        @for ($d = 1; $d <= $diasMes; $d++)
                            @php
                                // producción escrita
                                $prod = (int) data_get($inputs, "{$pid}.{$d}", 0);

                                // traspasos automáticos
                                $tras = (int) data_get($traspasos, "{$pid}.{$d}", 0);

                                // acumular totales
                                $totalProd += $prod;
                                $totalTras += $tras;
                            @endphp

                            <td class="inv-cell">
                                {{-- Producción (editable) --}}
                                <input type="number" min="0" class="form-control inv-input mb-1"
                                    wire:model.lazy="inputs.{{ $pid }}.{{ $d }}">

                                {{-- Traspaso (solo lectura) --}}
                                <input type="text" class="form-control inv-input inv-read"
                                    value="{{ $tras }}" readonly>
                            </td>
                        @endfor

                        {{-- Totales --}}
                        {{-- Totales (FIJOS A LA DERECHA) --}}
                        <td class="text-end fw-bold inv-total inv-total-3" style="background-color: #ffffff;">
                            {{ $totalProd }}
                        </td>

                        <td class="text-end fw-bold inv-total inv-total-2" style="background-color: #ffffff;">
                            {{ $totalTras }}
                        </td>

                        <td class="text-end fw-bold inv-total" style="background-color: #ffffff;">
                            {{ $totalProd - $totalTras }}
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <script>
        window.addEventListener('ok', e => alert(e.detail.msg));
    </script>
</div>
