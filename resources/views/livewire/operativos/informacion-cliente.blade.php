<div>
    <a wire:click="$set('info',true)" class="mr-1 btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
        title="INFORMACIÓN DE TRATAMIENTO">
        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                d="M17.7366 6.04606C19.4439 7.36388 20.8976 9.29455 21.9415 11.7091C22.0195 11.8924 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8924 2.05854 11.7091C4.14634 6.87903 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12Z"
                fill="currentColor"></path>
            <path
                d="M14.4308 11.997C14.4308 13.3255 13.3381 14.4115 12.0015 14.4115C10.6552 14.4115 9.5625 13.3255 9.5625 11.997C9.5625 11.8321 9.58201 11.678 9.61128 11.5228H9.66006C10.743 11.5228 11.621 10.6695 11.6601 9.60184C11.7674 9.58342 11.8845 9.57275 12.0015 9.57275C13.3381 9.57275 14.4308 10.6588 14.4308 11.997Z"
                fill="currentColor"></path>
        </svg>
    </a>
    <x-modal wire:model="info">
        <div class="py-4 mt-4 card">
            <div class="card-header">
                <h5 class="card-title">{{ $this->operativo->nombretratamiento }}</h5>
                <div class="card-options">
                    <label for="" class="btn btn-success" wire:click="$set('crear',true)">AGREGAR
                        INFORMACION</label>
                    <label for="" class="ml-2 btn btn-warning" wire:click="imprimir">IMPRIMIR</label>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-striped text-nowrap">
                        <thead>
                            <th>INFORMACION</th>
                            <th>FECHA</th>
                            <th>SESION</th>
                            <th>ACCION</th>
                        </thead>
                        <tbody>
                            @foreach ($misfichas as $ficha)
                                <tr>
                                    <td>
                                        {{ $ficha->descripcion }}
                                    </td>
                                    <td>
                                        {{ $ficha->fecha }}
                                    </td>
                                    <td>
                                        {{ $ficha->sesion }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="mr-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="ELIMINAR"
                                                data-original-title="Edit"
                                                wire:click="preeliminar({{ $ficha->id }})">

                                                <span class="ms-1"
                                                    style="font-size: 12px;  color:aliceblue;">ELIMINAR</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-modal>
    <x-modal wire:model="crear">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVA INFORMACION
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">TRATAMIENTO:</label>
                        <textarea rows="4" style="width: 100%" wire:model="tratamiento"></textarea>

                    </div>
                    <div class="d-flex">
                        <div class="form-group">
                            <label class="form-label" for="">SESION</label>
                            <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="sesion">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                            </select>
                        </div>
                        <div class="ml-4 form-group">
                            <label class="form-label" for="">FECHA:</label>
                            <input type="date" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="fecha">


                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <button type="submit" style="background-color: green;" class="btn btn-success"
                wire:click="guardartodo">Guardar</button>
        </div>
    </x-modal>
    <x-modal wire:model="eliminar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                ¿Desea eliminar esta informacion?
            </div>
        </div>
        <div class="flex flex-row justify-end px-1 py-1 text-right bg-gray-100">
            <label type="submit" style="background-color: red;" class="btn btn-danger"
                wire:click="eliminarinformacion">Si eliminar</label>
        </div>
    </x-modal>
</div>
