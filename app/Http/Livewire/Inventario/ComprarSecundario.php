<?php

namespace App\Http\Livewire\Inventario;

use App\Models\Areas;
use App\Models\Productos;
use App\Models\registroinventario;
use App\Models\trasapasostext;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ComprarSecundario extends Component
{
    use WithPagination;

    public $comprar = false;
    public $search;
    public $motivo = "personal";
    public $cantidades = [];
    public $precios = [];
    public $sucursalseleccionada;
    public $areas;
    public $searchcliente = "";
    public $clientes;
    public $clienteseleccionado = '';
    public $modo = "efectivo";
    public $pagar = 0;
    public $areaseleccionada;
    public $nombre = '';
    public $fecha;
    public $general;
    public $productos;
    public $cantidad;
    public $personal;
    public $personalseleccionado;
    public $montoQr = 0;
    public $montoEfectivo = 0;

    protected $rules = [
        'producto.nombre' => 'required',
        'producto.descripcion' => 'required',
        'producto.path' => 'required',
        'producto.precio' => 'required',
        'producto.cantidad' => 'required',
        'producto.estado' => 'required',
        'producto.sucursal' => 'required',
    ];

    public function mount()
    {
        //$productos = Productos::where('estado', 'Activo')->where('sucursal', Auth::user()->sucursal)->get();
        $productos = Productos::where('sucursal', Auth::user()->sucursal)->get();
        foreach ($productos as $producto) {
            $this->precios[$producto->id] = $producto->precio;
        }
        $sucursal = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalseleccionada = $sucursal->id;
        $this->fecha = Carbon::now()->toDateString();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->personal = User::where('estado', 'Activo')->whereNotIn('rol', ['Recursos Humanos', 'Editor', 'Sistema', 'Administrador', 'TARJETAS', 'Cliente', 'Contador', 'INVENTARIO', 'Asist. Administrativo', 'Jefe Marketing y Publicidad'])->get();
        $this->personalseleccionado = Auth::user()->id;
    }
    public function render()
    {

        if ($this->searchcliente != "") {

            $this->clientes = User::where('rol', 'Cliente')->where('estado', 'Activo')->where('name', 'ilike', '%' . $this->searchcliente)->get();
        }

        $this->pagar = 0;
        $this->cantidades = array_filter($this->cantidades, function ($cantidad) {
            return $cantidad !== null && $cantidad !== '';
        });
        $sucursal = Areas::find($this->sucursalseleccionada);
        $this->productos = Productos::where('sucursal', $sucursal->area)
            ->where('cantidad', '>', 0)
            ->where(function ($query) {
                if (is_numeric($this->search)) {
                    // Buscar por idinventario si es numérico
                    $query->where('idinventario',  $this->search);
                } else {
                    // Buscar por nombre si tiene letras
                    $query->where('nombre', 'ilike', '%' . $this->search . '%');
                }
            })
            ->orderBy('nombre')
            ->get();
        $this->cantidad = Productos::where('sucursal', $sucursal->area)
            ->where('cantidad', '>', 0)
            ->where(function ($query) {
                if (is_numeric($this->search)) {
                    // Buscar por idinventario si es numérico
                    $query->where('idinventario',  $this->search);
                } else {
                    // Buscar por nombre si tiene letras
                    $query->where('nombre', 'ilike', '%' . $this->search . '%');
                }
            })
            ->count();
        if (!empty($this->cantidades)) {
            foreach ($this->cantidades as $id => $cantidad) {
                $producto = Productos::find($id);
                if ($cantidad > 0 && $cantidad <= $producto->cantidad) {

                    $this->pagar += floatval($this->precios[$id]) * $cantidad;
                }
            }
        }
        return view('livewire.inventario.comprar-secundario');
    }
    public function realizarCompra()
    {
        $total = 0;
        $falta = false;
        $faltante = "";
        $this->cantidades = array_filter($this->cantidades, function ($cantidad) {
            return $cantidad !== null && $cantidad !== '';
        });
        if (empty($this->cantidades)) {
            $this->comprar = false;
            $this->emit('error', 'No se ha seleccionado ninguna cantidad.');
            return;
        } else {
            $sucursal = Areas::find($this->sucursalseleccionada);
            $descriptionWidth = 25;
            $vendedor = User::find($this->personalseleccionado);
            $recepcion = explode(' ', $vendedor);
            $text = "FARMACIA SPA MEDIC MIORA-----------------------------------------------------------------------\n" . "Fecha: " . date('Y-m-d H:i:s') . "\nCaja: " . Auth::user()->sucursal . "\nCajero: " . implode(' ', array_slice($recepcion, 0, 2)) . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . str_pad("CANT", 8) . "PRECIO\n-----------------------------------------------------------------------------\n";
            $costo = 0;
            $lineWidth = 35;
            $pricePosition = intval($lineWidth * 0.7);
            foreach ($this->cantidades as $id => $cantidad) {
                $producto = Productos::find($id);
                if ($cantidad > 0 && $cantidad <= $producto->cantidad) {
                    $cantidadDouble = (float) $cantidad;
                    $precioDouble = floatval($this->precios[$id]);
                    $registro = new registroinventario;
                    $registro->idsucursal = $sucursal->id;
                    $registro->sucursal = $sucursal->area;
                    $registro->idproducto = $producto->id;
                    $registro->nombreproducto = $producto->nombre;
                    $registro->cantidad = $cantidad;
                    $producto->cantidad = $producto->cantidad - $cantidad;
                    $producto->save();
                    $registro->precio = $cantidadDouble * $precioDouble;
                    $registro->iduser = Auth::user()->id;
                    $registro->fecha = $this->fecha;
                    $registro->motivo = $this->motivo;
                    if ($this->motivo == "compra" && $this->clienteseleccionado) {
                        $registro->modo = $this->modo;
                        $registro->idcliente = $this->clienteseleccionado;
                        $registro->estado = 'Activo';
                        $registro->save();
                        $costo += $cantidadDouble * $precioDouble;
                        $descripcion = substr($producto->nombre, 0, 20);
                        $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                        $cantidad = $cantidadDouble;
                        $cantidad = str_pad($cantidad, 8, ' ', STR_PAD_RIGHT);
                        $precio = 'Bs.' .  $cantidadDouble * $precioDouble;
                        $text .= $descripcion . $cantidad . $precio . "\n";
                    } elseif ($this->motivo == "compra" && $this->clienteseleccionado == '') {
                        $text = 'No se ha seleccionado ningún cliente.';
                        $this->comprar = false;
                        $this->emit('error', 'No se ha seleccionado ningún cliente.');
                        return;
                    }
                    if ($this->motivo == "personal") {
                        $registro->estado = 'Activo';
                        $registro->save();
                    }
                } else {
                    if ($cantidad != null) {
                        $faltante = $producto->nombre . $cantidad;
                        $this->comprar = false;
                        $falta = true;
                    }
                }
            }
            if ($falta == false) {
                if ($this->motivo != "personal") {
                    $text .= "


                    -----------------------------------------------------------------------------\n";
                    $totalpago = "Modo: " . strtoupper($this->modo);
                    $totalpago = substr($totalpago, 0, 20);
                    $totalpago = str_pad($totalpago, 8 + $pricePosition - 1, ' ', STR_PAD_RIGHT);
                    $precio = 'Bs.' . $costo;
                    $text .=  $totalpago . $precio . "\n";


                    $text .= "



                    -----------------------------------------------------------------------------\n";
                    $data = [
                        'id' => Auth::user()->sesionsucursal,
                        'texto' => $text
                    ];
                    $url = "http://127.0.0.1:5001/imprimirticket";
                    $response = Http::post($url, $data);
                    // $url = "http://127.0.0.1:5001/imprimirticket";
                    // $response = Http::post($url, $data);
                    DB::table('traspasostexts')->insert([
                        'user_id' => Auth::user()->id,
                        'sucursal_origen' => $sucursal->area,
                        'sucursal_destino' => $sucursal->area,
                        'texto' => $text, // Texto formateado que quieres guardar
                        'fecha' => Carbon::now(), // Inserta la fecha actual
                        'created_at' => now(),
                        'updated_at' => now(),
                        'motivo' => 'venta'
                    ]);
                }

                $this->comprar = false;
                $this->cantidades = [];
                $productos = Productos::where('estado', 'Activo')->get();
                foreach ($productos as $producto) {
                    $this->precios[$producto->id] = $producto->precio;
                }
                $this->emit('alert', "Compra realizada");
            } else {
                $this->cantidades = [];
                $productos = Productos::where('estado', 'Activo')->get();
                foreach ($productos as $producto) {
                    $this->precios[$producto->id] = $producto->precio;
                }
                $this->comprar = false;
                $falta = false;
                $this->emit('error', "No hay suficientes productos disponibles." . $faltante);
            }
        }
    }

    public function realizarfarmacia()
    {
        $total = 0;
        $falta = false;
        $faltante = "";
        $this->cantidades = array_filter($this->cantidades, fn($cantidad) => $cantidad !== null && $cantidad !== '');
        // Verificar si hay solo un producto, cantidad == 1, y doble método de pago
        if (count($this->cantidades) === 1) {
            $idUnico = array_key_first($this->cantidades);
            $cantidad = $this->cantidades[$idUnico];

            if ($cantidad == 1 && $this->montoQr > 0 && $this->montoEfectivo > 0) {
                $this->emit('error', 'No puedes pagar un solo producto con dos métodos de pago (QR y Efectivo). Usa solo uno.');
                return;
            }
        }

        if (empty($this->cantidades)) {
            $this->emit('error', 'No se ha seleccionado ninguna cantidad.');
            return;
        }

        $costoTotalCalculado = 0;
        foreach ($this->cantidades as $id => $cantidad) {
            $producto = Productos::find($id);
            if ($cantidad > 0 && $cantidad <= $producto->cantidad) {
                $costoTotalCalculado += floatval($this->precios[$id]) * floatval($cantidad);
            }
        }

        $totalDisponible = $this->montoQr + $this->montoEfectivo;

        if ($totalDisponible < $costoTotalCalculado) {
            $this->emit('error', 'El monto total disponible (QR + Efectivo) no cubre el costo total de la venta.');
            return;
        }

        if ($totalDisponible > $costoTotalCalculado) {
            $this->emit('error', 'Advertencia: Hay dinero sin asignar a ningún producto. Verifica el monto ingresado.');
            return;
        }

        $vendedor = User::find($this->personalseleccionado);
        $sucursal = Areas::find($this->sucursalseleccionada);
        $montoRestanteQr = floatval($this->montoQr);
        $montoRestanteEfectivo = floatval($this->montoEfectivo);
        $ticket = "VENTA SPA MEDIC MIORA\n---------------------------------------------\n";
        $ticket .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
        $ticket .= "Sucursal: {$sucursal->area}\n";
        $ticket .= "Vendedor: {$vendedor->name}\n";
        $ticket .= "---------------------------------------------\n";
        $ticket .= "Producto           Cant Precio   Pago\n";
        $ticket .= "---------------------------------------------\n";
        $totalUnidades = 0;
        foreach ($this->cantidades as $c) {
            $totalUnidades += $c;
        }
        $unidadActual = 1;

        foreach ($this->cantidades as $id => $cantidad) {
            $producto = Productos::find($id);
            if ($cantidad <= 0 || $cantidad > $producto->cantidad) {
                $this->emit('error', "Cantidad inválida para el producto: {$producto->nombre}");
                return;
            }

            $producto->cantidad -= $cantidad;
            $producto->save();
            $precio = floatval($this->precios[$id]);

            for ($i = 0; $i < $cantidad; $i++) {
                $modo = $montoRestanteQr > 0 ? 'qr' : 'efectivo';
                $montoDisponible = $modo === 'qr' ? $montoRestanteQr : $montoRestanteEfectivo;

                // Si es la última unidad, asignamos todo lo que queda
                if ($unidadActual === $totalUnidades) {
                    $montoAsignado = $montoDisponible;
                } else {
                    $montoAsignado = min($precio, $montoDisponible);
                }

                if ($montoAsignado <= 0) break;

                $registro = new registroinventario();
                $registro->idsucursal = $sucursal->id;
                $registro->sucursal = $sucursal->area;
                $registro->idproducto = $producto->id;
                $registro->nombreproducto = $producto->nombre;
                $registro->cantidad = 1;
                $registro->precio = $montoAsignado;
                $registro->iduser = $vendedor->id;
                $registro->fecha = $this->fecha;
                $registro->motivo = $this->motivo;
                $registro->modo = $modo;
                $registro->estado = 'Activo';
                $registro->save();

                if ($modo === 'qr') {
                    $montoRestanteQr -= $montoAsignado;
                } else {
                    $montoRestanteEfectivo -= $montoAsignado;
                }

                $ticket .= str_pad(substr($producto->nombre, 0, 15), 16);
                $ticket .= str_pad('1', 5);
                $ticket .= str_pad("Bs." . number_format($montoAsignado, 2), 8);
                $ticket .= strtoupper($modo) . "\n";

                $unidadActual++;
            }
        }


        $ticket .= "---------------------------------------------\n";
        $ticket .= "Total pagado: Bs." . number_format($costoTotalCalculado, 2) . "\n";
        $ticket .= "---------------------------------------------\n\n\n";

        // Guardar en tabla de tickets
        DB::table('traspasostexts')->insert([
            'user_id' => Auth::user()->id,
            'sucursal_origen' => $sucursal->area,
            'sucursal_destino' => $sucursal->area,
            'texto' => $ticket,
            'fecha' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'motivo' => 'venta'
        ]);

        // Imprimir
        Http::post("http://127.0.0.1:5001/imprimirticket", [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $ticket
        ]);

        // Reset
        $this->comprar = false;
        $this->cantidades = [];
        $this->montoQr = 0;
        $this->montoEfectivo = 0;
        $this->search = "";
        $productos = Productos::where('estado', 'Activo')->get();
        foreach ($productos as $producto) {
            $this->precios[$producto->id] = $producto->precio;
        }

        $this->emit('alert', "Venta realizada e impresa correctamente.");
    }

    public function realizartraspaso()
    {
        $total = 0;
        $falta = false;
        $faltante = "";
        $this->cantidades = array_filter($this->cantidades, function ($cantidad) {
            return $cantidad !== null && $cantidad !== '';
        });
        if (empty($this->cantidades)) {
            $this->comprar = false;
            $this->emit('error', 'No se ha seleccionado ninguna cantidad.');
            return;
        } else {
            $descriptionWidth = 30;
            $vendedor = User::find($this->personalseleccionado);
            $recepcion = explode(' ', $vendedor);

            $area = Areas::find($this->sucursalseleccionada);
            $text = "TRASPASO SPA MEDIC MIORA-----------------------------------------------------------------------\n"
                . "DE: " . $area->area . "\n"
                . "A: " . $this->areaseleccionada . "\n"
                . "Fecha: " . date('Y-m-d H:i:s') . "\nCaja: " . Auth::user()->sucursal . "\nResponsable: " . implode(' ', array_slice($recepcion, 0, 2)) .
                "\n-----------------------------------------------------------------------------" .
                str_pad("\nDESCRIPCIÓN", $descriptionWidth) . str_pad("CANT", 8) .
                "\n-----------------------------------------------------------------------------\n";
            $costo = 0;
            $lineWidth = 44;
            $pricePosition = intval($lineWidth * 0.7);
            foreach ($this->cantidades as $id => $cantidad) {
                if ($this->areaseleccionada) {
                    $producto = Productos::find($id);
                    if ($cantidad > 0 && $cantidad <= $producto->cantidad) {
                        $cantidadDouble = (float) $cantidad;
                        $precioDouble = floatval($this->precios[$id]);
                        $registro = new registroinventario;
                        $sucursal = Areas::find($this->sucursalseleccionada);
                        $registro->idsucursal = $sucursal->id;
                        $registro->modo = $this->areaseleccionada;
                        $registro->sucursal = $sucursal->area;
                        $registro->idproducto = $producto->id;
                        $registro->nombreproducto = $producto->nombre;
                        $registro->cantidad = $cantidad;
                        $registro->precio = $cantidadDouble * $precioDouble;
                        $registro->iduser = Auth::user()->id;
                        $registro->fecha = Carbon::now()->toDateString();
                        $registro->motivo = $this->motivo;
                        if ($this->motivo == "traspaso") {
                            $nuevoproducto = Productos::where('sucursal', $this->areaseleccionada)->where('nombre', $producto->nombre)->first();
                            if ($nuevoproducto) {
                                $nuevoproducto->cantidad = $nuevoproducto->cantidad + $cantidad;
                                $producto->cantidad = $producto->cantidad - $cantidad;
                                $nuevoproducto->save();
                                $costo +=  $cantidadDouble;
                                $descripcion = substr($nuevoproducto->nombre, 0, 20);
                                $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                                $cantidad =  $cantidadDouble;
                                $cantidad = str_pad($cantidad, 15, ' ', STR_PAD_RIGHT);
                                $text .= $descripcion . $cantidad  . "\n";
                            } else {
                                $nuevoproducto = new Productos();
                                $nuevoproducto->estado = 'Activo';
                                $nuevoproducto->nombre = $producto->nombre;
                                $nuevoproducto->precio = $producto->precio;
                                $nuevoproducto->cantidad = $cantidad;
                                $nuevoproducto->sucursal = $this->areaseleccionada;
                                $nuevoproducto->cantidad = $cantidad;
                                $producto->cantidad = $producto->cantidad - $cantidad;
                                $nuevoproducto->save();
                                $costo +=  $cantidadDouble;
                                $descripcion = substr($nuevoproducto->nombre, 0, 20);
                                $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                                $cantidad =  $cantidadDouble;
                                $cantidad = str_pad($cantidad, 15, ' ', STR_PAD_RIGHT);
                                $text .= $descripcion . $cantidad  . "\n";
                            }
                        }
                        $producto->save();
                        $registro->estado = 'Activo';
                        $registro->save();
                    } else {
                        if ($cantidad != null) {
                            $faltante = $producto->nombre . $cantidad;
                            $this->comprar = false;
                            $falta = true;
                        }
                    }
                } else {
                    $this->comprar = false;
                    $falta = false;
                    $this->emit('error', "No se ha seleccionado sucursal");
                }
            }
            if ($falta == false) {
                if ($this->motivo == "traspaso") {
                    $text .= "


                    -----------------------------------------------------------------------------\n";
                    $totalpago = "TIPO: TRASPASO";
                    $totalpago = substr($totalpago, 0, 20);
                    $totalpago = str_pad($totalpago,  $pricePosition - 1, ' ', STR_PAD_RIGHT);
                    $precio = $costo;
                    $text .=  $totalpago . $precio . "\n";
                    $text .= "



                    -----------------------------------------------------------------------------\n";
                    $data = [
                        'id' => Auth::user()->sesionsucursal,
                        'texto' => $text
                    ];
                    $url = "http://127.0.0.1:5001/imprimirticket";
                    $response = Http::post($url, $data);
                    sleep(1);
                    $url = "http://127.0.0.1:5001/imprimirticket";
                    $response = Http::post($url, $data);



                    DB::table('traspasostexts')->insert([
                        'user_id' => Auth::user()->id,
                        'sucursal_origen' => $area->area,
                        'sucursal_destino' => $this->areaseleccionada,
                        'texto' => $text, // Texto formateado que quieres guardar
                        'fecha' => Carbon::now(), // Inserta la fecha actual
                        'created_at' => now(),
                        'updated_at' => now(),
                        'motivo' => 'traspaso'
                    ]);
                }

                $this->comprar = false;
                $this->cantidades = [];
                $productos = Productos::where('estado', 'Activo')->get();
                foreach ($productos as $producto) {
                    $this->precios[$producto->id] = $producto->precio;
                }
                $this->emit('alert', "ACCIÓN realizada");
            } else {
                $this->cantidades = [];
                $productos = Productos::where('estado', 'Activo')->get();
                foreach ($productos as $producto) {
                    $this->precios[$producto->id] = $producto->precio;
                }
                $this->comprar = false;
                $falta = false;
                $this->emit('error', "No hay suficientes productos disponibles." . $faltante);
            }
        }
    }
}
