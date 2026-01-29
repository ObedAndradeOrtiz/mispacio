<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Inventario mensual (Artesanales)</h4>
            <small class="text-muted">Mes: <b>{{ $month }}</b></small>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" wire:click="prevMonth">← Mes anterior</button>
            <button type="button" class="btn btn-outline-secondary" wire:click="nextMonth">Mes siguiente →</button>
            <button type="button" class="btn btn-primary" wire:click="save">Guardar</button>
        </div>
    </div>

    <div class="table-responsive" style="border:1px solid #e5e7eb; border-radius:12px; overflow:auto;">
        <table class="table table-sm mb-0" style="min-width: 1200px;">
            <thead style="position: sticky; top:0; background:#fff; z-index:3;">
                <tr>
                    <th style="min-width: 280px; position: sticky; left:0; background:#fff; z-index:4;">Producto</th>

                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        <th class="text-center" style="min-width: 120px;">
                            Día {{ $d }}
                            <div class="d-flex justify-content-center gap-2 mt-1" style="font-size:12px;">
                                <span class="badge bg-primary">P</span>
                                <span class="badge bg-dark">T</span>
                            </div>
                        </th>
                    @endfor

                    <th class="text-center" style="min-width: 110px;">Total P</th>
                    <th class="text-center" style="min-width: 110px;">Total T</th>
                    <th class="text-center" style="min-width: 120px;">Neto</th>
                </tr>
            </thead>

            <tbody>
                @forelse($productos as $p)
                    @php
                        $sumP = 0;
                        $sumT = 0;
                    @endphp

                    <tr>
                        <td style="position: sticky; left:0; background:#fff; z-index:2;">
                            <div class="fw-semibold">{{ $p->nombre }}</div>
                            <small class="text-muted">artesanal</small>
                        </td>

                        @for ($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $valP = (int) data_get($inputs, "{$p->id}.{$d}", 0);
                                $valT = (int) data_get($traspasos, "{$p->id}.{$d}", 0);
                                $sumP += $valP;
                                $sumT += $valT;
                            @endphp

                            <td class="align-middle">
                                <div class="d-grid gap-1">
                                    {{-- Producción (editable) --}}
                                    <input type="number" min="0" class="form-control form-control-sm"
                                        style="text-align:right;"
                                        wire:model.lazy="inputs.{{ $p->id }}.{{ $d }}">

                                    {{-- Traspaso (solo lectura) --}}
                                    <input type="text" class="form-control form-control-sm"
                                        style="text-align:right; background:#f8fafc;" value="{{ $valT }}"
                                        readonly title="Traspaso desde ALMACEN CENTRAL">
                                </div>
                            </td>
                        @endfor

                        <td class="text-end fw-bold">{{ $sumP }}</td>
                        <td class="text-end fw-bold">{{ $sumT }}</td>
                        <td class="text-end fw-bold">{{ $sumP - $sumT }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $daysInMonth + 4 }}" class="text-center text-muted py-4">
                            No hay productos artesanales.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Idea poco común: mini notificación sin librerías --}}
    <script>
        window.addEventListener('notify', e => {
            const msg = e.detail.msg || 'OK';
            const el = document.createElement('div');
            el.textContent = msg;
            el.style.position = 'fixed';
            el.style.bottom = '18px';
            el.style.right = '18px';
            el.style.padding = '10px 14px';
            el.style.borderRadius = '12px';
            el.style.background = 'rgba(0,0,0,.85)';
            el.style.color = 'white';
            el.style.zIndex = 99999;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 1600);
        });
    </script>
</div>
