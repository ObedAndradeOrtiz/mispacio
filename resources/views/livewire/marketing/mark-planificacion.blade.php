<div>
    <div class="flex flex-row justify-end">
        <div>
            <select name="" id="" wire:model="seleccion">
                <option value="planificacion">Planificación de videos</option>
                <option value="listavideos">Lista de videos</option>
                <option value="listacontenidos">Planificación de contenido</option>
            </select>
        </div>
        <button class="btn btn-success" wire:click="$set('nuevo',true)">NUEVO</button>
    </div>
    <div>
        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda"
            placeholder="Ingrese busqueda...">
    </div>
    @if ($seleccion == 'planificacion')
        <div>
            <label for="">FILTRO DE TRATAMIENTOS:</label>
            <select name="" id="" wire:model='tratamientoseleccionado'>
                <option value="">Todos</option>
                @foreach ($tratamientos as $item)
                    <option value="{{ $item->tratamiento }}">{{ $item->tratamiento . '(' . $item->cantidad . ')' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="table-responsive">
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr>
                        <th style="border: 3px solid black;" colspan="2"></th>
                        <th style="border: 3px solid black;"></th>
                        <th style="border: 3px solid black; text-align: center;" colspan="12">VIDEO</th>
                        <th style="border: 3px solid black; text-align: center;" colspan="8">IMAGEN</th>
                        {{-- <th style="border: 3px solid black;"></th>
                    <th style="border: 3px solid black;"></th>
                    <th style="border: 3px solid black;"></th> --}}
                    </tr>
                    <tr class="ligth">
                        <th style="border: 3px solid black; text-align: center;" colspan="2">NOMBRE</th>
                        <th style="border: 3px solid black;"></th>
                        <th style="border: 3px solid black; text-align: center;" colspan="2">HORIZ</th>
                        <th style="border: 3px solid black; text-align: center;" colspan="10">VERTI</th>
                        <th style="border: 3px solid black; text-align: center;" colspan="8">CONTENIDO</th>
                        {{-- <th style="border: 3px solid black;"></th>
                    <th style="border: 3px solid black;"></th>
                    <th style="border: 3px solid black;"></th> --}}

                    </tr>
                    <tr class="ligth">
                        <th style="border: 3px solid black;">NOMBRE</th>
                        <th style="border: 3px solid black;">TRT</th>
                        <th style="border: 3px solid red;">EDIC</th>
                        <th style="border: 3px solid rgb(84, 235, 84);">PRCD</th>
                        <th style="border: 3px solid black;">30S</th>
                        <th style="border: 3px solid black;">YT</th>
                        <th style="border: 3px solid black;">30S</th>
                        <th style="border: 3px solid black;">15S</th>
                        <th style="border: 3px solid black;">REL</th>
                        <th style="border: 3px solid black;">TRD</th>
                        <th style="border: 3px solid black;">TTK</th>
                        <th style="border: 3px solid black;">CUR</th>
                        <th style="border: 3px solid black;">FRA</th>
                        <th style="border: 3px solid black;">SUC</th>
                        <th style="border: 3px solid black;">GFD</th>
                        <th style="border: 3px solid black;">LIV</th>
                        <th style="border: 3px solid green;">ALB</th>
                        <th style="border: 3px solid green;">CNS</th>
                        <th style="border: 3px solid green;">HRA</th>
                        <th style="border: 3px solid green;">GFCI</th>
                        <th style="border: 3px solid green;">CRSI</th>
                        <th style="border: 3px solid green;">FEST</th>
                        <th style="border: 3px solid green;">CARRU</th>
                        <th style="border: 3px solid orange;">NVMO</th>


                        <th style="border: 3px solid red;">ELM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planillas as $planilla)
                        <tr>
                            <td style="border: 3px solid black;">
                                {{ $planilla->nombre }}
                            </td>
                            <td style="border: 3px solid black;">
                                {{ $planilla->tratamiento }}
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->editarcontenido == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(22,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(22,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->segundos == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(1,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(1,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->nuevomodelo == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(20,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(20,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->minutos == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(2,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(2,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->segundosv == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(3,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(3,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->minutosv == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(4,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(4,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->reel == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(5,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(5,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->trend == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(6,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(6,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->tiktok == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(7,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(7,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->curso == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(8,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(8,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->franquicia == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(9,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(9,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->sucursal == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(10,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(10,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->gifcard == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(11,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(11,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->live == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(12,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(12,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->album == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(13,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(13,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->consulta == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(14,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(14,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->horario == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(15,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(15,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->gifcardimg == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(16,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(16,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->cursoimg == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(17,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(17,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->festejo == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(18,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(18,{{ $planilla->id }})">
                                @endif
                            </td>
                            <td style="border: 3px solid black;">
                                @if ($planilla->carrusel == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(19,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(19,{{ $planilla->id }})">
                                @endif
                            </td>

                            <td style="border: 3px solid black;">
                                @if ($planilla->procedimiento == 'SI')
                                    <input type="checkbox" wire:click="guardartodo(21,{{ $planilla->id }})" checked>
                                @else
                                    <input type="checkbox" wire:click="guardartodo(21,{{ $planilla->id }})">
                                @endif
                            </td>

                            <td style="border: 3px solid red;">
                                <label data-bs-toggle="tooltip" data-bs-placement="top" title="ELIMINAR"
                                    data-original-title="Edit"
                                    wire:click="eliminarpanificacion({{ $planilla->id }})">
                                    <svg class="icon-10" width="10" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if ($seleccion == 'listavideos')
        <div>
            <select name="" id="" wire:model="videoselect">
                <option value="videos">POR VIDEOS</option>
                <option value="personas">POR PERSONAS</option>
            </select>
        </div>
        @if ($videoselect == 'videos')
            <div class="table-responsive">
                <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                    <thead>
                        <tr>
                            <th>
                                TRATAMIENTO
                            </th>
                            <th>
                                CANTIDAD DE VIDEOS
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($tratamientos as $item)
                            <tr>
                                <td>
                                    {{ $item->tratamiento }}
                                </td>
                                <td>
                                    {{ $item->cantidad }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if ($videoselect == 'personas')
            <div class="table-responsive">
                <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                    <thead>
                        <tr>
                            <th>
                                NOMBRE
                            </th>
                            <th>
                                CANTIDAD DE VIDEOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $item)
                            <tr>
                                <td>
                                    {{ $item->nombre }}
                                </td>
                                <td>
                                    {{ $item->cantidad }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
    @if ($seleccion == 'listacontenidos')
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid black;
                padding: 5px;
                text-align: center;
                font-size: 12px;
            }

            th {
                border: 1px solid black;
                background-color: #FFA500;
                color: white;
            }

            .rotate {
                border: 1px solid black;
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                white-space: nowrap;
                height: 100px;
                /* Adjust this value as needed */
                width: 30px;
                /* Adjust this value as needed */
            }

            td[contenteditable="true"] {
                background-color: #f9f9f9;
            }
        </style>

        <table>
            <thead>
                <tr>
                    <th colspan="24">CONTENIDO FACEBOOK MAYO 2024 COSMETOLOGIA Y ESTETICA Miora</th>
                </tr>
                <tr>
                    <th>DÍA</th>
                    <th colspan="2">COSMETOLOGIA</th>
                    <th colspan="2">CURSOS</th>
                    <th colspan="2">URBARI</th>
                    <th colspan="2">MACORORO</th>
                    <th colspan="2">BUSH</th>
                    <th colspan="2">V. de COTOCA</th>
                    <th colspan="2">COTOCA</th>
                    <th colspan="2">COCHABAMBA</th>
                    <th colspan="2">SAWY</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rellenar con datos de la tabla de Excel -->
                <tr>
                    <td contenteditable="true">1</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">GIFT-C</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">ALBUM</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">GIFT-C</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">GIFT-C</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">GIFT-C</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">GIFT-C</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">GIFT-C</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">CON G</td>
                    <td class="rotate" contenteditable="true" rowspan="6">VERRUGA - KATIA</td>
                    <td contenteditable="true">CON G</td>
                </tr>
                <tr>
                    <td contenteditable="true">2</td>

                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                    <td contenteditable="true">30 HORZ</td>
                </tr>
                <tr>
                    <td contenteditable="true">3</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">15 VERT</td>
                    <td contenteditable="true">30 HORZ</td>
                </tr>
                <tr>
                    <td contenteditable="true">4</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">ALBUM</td>
                    <td contenteditable="true">30 HORZ</td>
                </tr>
                <tr>
                    <td contenteditable="true">5</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 HORZ</td>
                </tr>
                <tr>
                    <td contenteditable="true">6</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 VERT</td>
                    <td contenteditable="true">30 HORZ</td>
                </tr>

            </tbody>
        </table>
        <script>
            // JavaScript para manejar la edición y guardado de contenido editable
            document.addEventListener('DOMContentLoaded', () => {
                const table = document.querySelector('table');
                table.addEventListener('input', (event) => {
                    const target = event.target;
                    if (target.tagName === 'TD' && target.hasAttribute('contenteditable')) {
                        console.log(`Changed content in cell: ${target.textContent}`);
                        // Aquí puedes agregar código para actualizar el contenido en el servidor usando Livewire
                    }
                });
            });
        </script>
    @endif



    <x-modal wire:model.defer="nuevo">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nueva planificacion
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="nombre">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Tratammiento:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="tratamiento">
                    </div>

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardarnuevo" wire:loading.remove
                wire:target="guardarnuevo">Registrar</label>
            <span class="" wire:loading wire:target="guardarnuevo">Guardando...</span>

        </div>
    </x-modal>

</div>
