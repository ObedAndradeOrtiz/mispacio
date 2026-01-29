<?php

namespace App\Http\Controllers;

use App\Models\Inmueble;
use App\Models\MensajesWss;
use App\Models\Productos;
use App\Models\registroinventario;
use App\Models\registropago;
use App\Models\TelefonoWss;
use Illuminate\Http\Request;
use App\Models\Tratamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\transacciones;
use App\Events\EnviarMensaje;
use App\Models\User;
use Illuminate\Support\Collection;

class ApiTratamientos extends Controller
{
    public function crearEmprendedor(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
        ]);
        $nuevo = new User;
        $nuevo->name = $request->nombre;
        $nuevo->rol = "Emprendedor";
        $nuevo->tesoreria = "Inactivo";
        $nuevo->telefono = $request->numero;
        $nuevo->password = bcrypt('12345678'); // Contraseña genérica (mejor cambiar después)
        $nuevo->estado = "Activo";
        $nuevo->sucursal = $request->codigo;
        $nuevo->save();

        return response()->json(['success' => true, 'message' => 'Emprendedor registrado correctamente.']);
    }
    public function index($sucursal)
    {
        // Definir las fechas de inicio del mes anterior y actual usando Carbon
        $fechaInicioMes = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $fechaActual = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        // Realizar la consulta SQL cruda
        $resultados = DB::select("
        WITH fechas AS (
            SELECT
                DATE_TRUNC('month', CURRENT_DATE) AS fecha_inicio_mes,
                CURRENT_DATE AS fecha_actual
        ),
        inventarios AS (
            SELECT
                p.idinventario,
                p.nombre,
                p.inicio,
                p.cantidad,
                -- Calcular modificaciones y asegurar que los valores nulos sean 0
                COALESCE((
                    SELECT SUM(cantidad) - SUM(CAST(ri.modo AS INTEGER))
                    FROM registroinventarios ri
                    WHERE ri.motivo = 'Modificaciones'
                    AND ri.nombreproducto ILIKE p.nombre
                    AND ri.sucursal ILIKE p.sucursal
                    AND ri.fecha::date BETWEEN ? AND ?
                ), 0) AS modificaciones,
                -- Calcular traspaso y asegurar que los valores nulos sean 0
                COALESCE((
                    SELECT SUM(cantidad)
                    FROM registroinventarios ri
                    WHERE ri.motivo = 'traspaso'
                    AND ri.nombreproducto ILIKE p.nombre
                    AND ri.sucursal ILIKE p.sucursal
                    AND ri.fecha::date BETWEEN ? AND ?
                ), 0) AS traspaso,
                -- Calcular compra y asegurar que los valores nulos sean 0
                COALESCE((
                    SELECT SUM(cantidad)
                    FROM registroinventarios ri
                    WHERE ri.motivo = 'nuevacompra'
                    AND ri.nombreproducto ILIKE p.nombre
                    AND ri.sucursal ILIKE p.sucursal
                    AND ri.fecha::date BETWEEN ? AND ?
                ), 0) AS compra,
                -- Calcular gabinete y asegurar que los valores nulos sean 0
                COALESCE((
                    SELECT SUM(cantidad)
                    FROM registroinventarios ri
                    WHERE ri.motivo = 'personal'
                    AND ri.nombreproducto ILIKE p.nombre
                    AND ri.sucursal ILIKE p.sucursal
                    AND ri.fecha::date BETWEEN ? AND ?
                ), 0) AS gabinete,
                -- Calcular venta y asegurar que los valores nulos sean 0
                COALESCE((
                    SELECT SUM(cantidad)
                    FROM registroinventarios ri
                    WHERE ri.motivo IN ('compra', 'farmacia')
                    AND ri.nombreproducto ILIKE p.nombre
                    AND ri.sucursal ILIKE p.sucursal
                    AND ri.fecha::date BETWEEN ? AND ?
                ), 0) AS venta
            FROM productos p
            WHERE p.sucursal = ?
            AND p.estado = 'Activo'
        )
        SELECT
            idinventario,
            nombre,
            inicio,
            modificaciones,
            traspaso,
            compra,
            gabinete,
            venta,
            ((inicio + compra + modificaciones) - traspaso - venta - gabinete) AS total,
            cantidad
        FROM inventarios
        ORDER BY cantidad DESC;
    ", [
            $fechaInicioMes,
            $fechaActual,
            $fechaInicioMes,
            $fechaActual,
            $fechaInicioMes,
            $fechaActual,
            $fechaInicioMes,
            $fechaActual,
            $fechaInicioMes,
            $fechaActual,
            $sucursal // Agregar la sucursal como parámetro al final
        ]);

        // Retornar los resultados en formato JSON
        return response()->json($resultados);
    }

    public function porsucursal($sucursal)
    {


        $productos = DB::table('productos')
            ->select(
                'nombre',
                'cantidad',
                'precio',
                DB::raw('(
            SELECT SUM(cantidad)
            FROM registroinventarios
            WHERE motivo = \'traspaso\'
            AND nombreproducto = productos.nombre
            AND modo = productos.sucursal
            AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
        ) AS traspaso'),
                DB::raw('(
            SELECT SUM(cantidad)
            FROM registroinventarios
            WHERE motivo = \'nuevacompra\'
            AND nombreproducto = productos.nombre
            AND sucursal = productos.sucursal
            AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
        ) AS compra'),
                DB::raw('(
            SELECT SUM(cantidad)
            FROM registroinventarios
            WHERE motivo IN (\'farmacia\', \'compra\')
            AND nombreproducto = productos.nombre
            AND sucursal = productos.sucursal
            AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
        ) AS venta'),
                DB::raw('(
            SELECT SUM(cantidad)
            FROM registroinventarios
            WHERE motivo = \'personal\'
            AND nombreproducto = productos.nombre
            AND sucursal = productos.sucursal
            AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
        ) AS gabinete'),
                DB::raw('(
            (
                (SELECT SUM(cantidad)
                 FROM registroinventarios
                 WHERE motivo = \'Modificaciones\'
                 AND nombreproducto = productos.nombre
                 AND sucursal = productos.sucursal
                 AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE)
                -
                (SELECT SUM(CAST(modo AS INTEGER))
                 FROM registroinventarios
                 WHERE motivo = \'Modificaciones\'
                 AND nombreproducto = productos.nombre
                 AND sucursal = productos.sucursal
                 AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE)
            ) +
            (SELECT SUM(cantidad)
             FROM registroinventarios
             WHERE motivo = \'traspaso\'
             AND nombreproducto = productos.nombre
             AND modo = productos.sucursal
             AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE)
            +
            (SELECT SUM(cantidad)
             FROM registroinventarios
             WHERE motivo = \'nuevacompra\'
             AND nombreproducto = productos.nombre
             AND sucursal = productos.sucursal
             AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE)
        ) AS total'),
                DB::raw('(
            (
                (
                    (
                        SELECT SUM(cantidad)
                        FROM registroinventarios
                        WHERE motivo = \'Modificaciones\'
                        AND nombreproducto = productos.nombre
                        AND sucursal = productos.sucursal
                        AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
                    ) -
                    (
                        SELECT SUM(CAST(modo AS INTEGER))
                        FROM registroinventarios
                        WHERE motivo = \'Modificaciones\'
                        AND nombreproducto = productos.nombre
                        AND sucursal = productos.sucursal
                        AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
                    )
                ) +
                (
                    SELECT SUM(cantidad)
                    FROM registroinventarios
                    WHERE motivo = \'traspaso\'
                    AND nombreproducto = productos.nombre
                    AND modo = productos.sucursal
                    AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
                ) +
                (
                    SELECT SUM(cantidad)
                    FROM registroinventarios
                    WHERE motivo = \'nuevacompra\'
                    AND nombreproducto = productos.nombre
                    AND sucursal = productos.sucursal
                    AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
                )
            ) -
            (
                (
                    SELECT SUM(cantidad)
                    FROM registroinventarios
                    WHERE motivo IN (\'farmacia\', \'compra\')
                    AND nombreproducto = productos.nombre
                    AND sucursal = productos.sucursal
                    AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
                ) +
                (
                    SELECT SUM(cantidad)
                    FROM registroinventarios
                    WHERE motivo = \'personal\'
                    AND nombreproducto = productos.nombre
                    AND sucursal = productos.sucursal
                    AND CAST(fecha AS date) BETWEEN \'2024-01-01\' AND CURRENT_DATE
                )
            )
        ) AS resta')
            )
            ->where('sucursal', $sucursal)
            ->where('estado', 'Activo')
            ->where('cantidad', '>', 0)
            ->orderBy('nombre')
            ->get();

        return response()->json($productos);
    }
    public function saldos($sucursal)
    {
        $productos = Productos::select('idinventario', 'nombre', 'cantidad', 'precio')
            ->where('sucursal', $sucursal)
            ->where('estado', 'Activo')
            ->orderBy('cantidad', 'desc')
            ->get();

        return response()->json($productos);
    }

    public function inmuebles($sucursal)
    {
        $productoslist = Inmueble::select('sucursal', 'area', 'tipo', 'nombre', 'descripcion', 'estado', 'cantidad', 'precio', 'fecha')->where('sucursal', 'ilike', '%' . $sucursal . '%')->get();
        return response()->json($productoslist);
    }
    public function pagos()
    {
        // Obtener las fechas desde los parámetros de la URL (query string)
        $fechaActual = request()->query('fechaActual');
        $fechaInicioMes = request()->query('fechaInicioMes');

        // Validar que se pasen las fechas correctas
        if (!$fechaActual || !$fechaInicioMes) {
            return response()->json(['error' => 'Fechas no proporcionadas'], 400);
        }

        // Obtener las transacciones entre las fechas dadas
        $transaccioneslist = transacciones::whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->where('motivo', '!=', 'ENVIO DE SALDO')
            ->where('motivo', '!=', 'AUMENTO DE SALDO')
            ->orderBy('fecha', 'asc') // Ordenar por fecha de menor a mayor
            ->get()
            ->map(function ($transaccion) {
                // Cambiar el punto por coma en el monto
                $transaccion->monto = str_replace(',', '.', $transaccion->monto);
                return $transaccion;
            });



        return response()->json($transaccioneslist);
    }
    public function ingresos()
    {
        // Obtener las fechas desde los parámetros de la URL (query string)
        $fechaActual = request()->query('fechaActual');
        $fechaInicioMes = request()->query('fechaInicioMes');

        // Validar que se pasen las fechas correctas
        if (!$fechaActual || !$fechaInicioMes) {
            return response()->json(['error' => 'Fechas no proporcionadas'], 400);
        }

        // Obtener las transacciones entre las fechas dadas
        $transaccioneslist = transacciones::whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->where('motivo', 'AUMENTO DE SALDO')
            ->orderBy('fecha', 'asc') // Ordenar por fecha de menor a mayor
            ->get()
            ->map(function ($transaccion) {
                // Cambiar el punto por coma en el monto
                $transaccion->monto = str_replace(',', '.', $transaccion->monto);
                return $transaccion;
            });
        return response()->json($transaccioneslist);
    }
    public function ingresosQr()
    {
        // Obtener las fechas desde los parámetros de la URL (query string)
        $fechaActual = request()->query('fechaActual');
        $fechaInicioMes = request()->query('fechaInicioMes');
        $sucursal = request()->query('sucursal');

        // Validar que se pasen las fechas correctas
        if (!$fechaActual || !$fechaInicioMes) {
            return response()->json(['error' => 'Fechas no proporcionadas'], 400);
        }

        $transaccioneslist = registroinventario::whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->where('motivo', ['compra', 'farmacia'])
            ->where('modo', 'qr')
            ->where('sucursal', 'ilike', '%' . $sucursal . '%')
            ->orderBy('fecha', 'asc')
            ->get();

        $pagoslist = registropago::whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->where('modo', 'Qr')
            ->where('sucursal', 'ilike', '%' . $sucursal . '%')
            ->orderBy('fecha', 'asc')
            ->get();

        // Combinar ambas listas
        $combinedList = $transaccioneslist->merge($pagoslist);

        // Opcional: ordenar la lista combinada por fecha
        $sortedList = $combinedList->sortBy('fecha')->values();

        // Devolver en formato JSON
        return response()->json($sortedList);

        // return response()->json($transaccioneslist);
    }
    public function mensajes(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'from' => 'required', // Número de quien envía el mensaje
            'to' => 'required',   // Tu número
            'message' => 'required', // Contenido del mensaje
        ]);
        try {
            $mensaje = MensajesWss::where('emisor', $validated['from'])->where('receptor', $validated['to'])->first();

            // Verificar si el mensaje recuperado no es vacío ni nulo
            if ($mensaje && !empty($mensaje->mensaje)) {
                // Si el mensaje no es vacío ni nulo, actualizamos los datos


                $mensaje->emisor = $validated['from'];
                $mensaje->receptor = $validated['to'];
                $mensaje->mensaje = $validated['message'];
                $mensaje->fecha = date('y-m-d'); // Guardar solo la fecha
                $mensaje->hora = Carbon::now()->format('H:i:s'); // Guardar solo la hora en formato HH:mm:ss
                $mensaje->save();
            } else {
                // Si el mensaje está vacío o es nulo, creamos un nuevo mensaje
                $mensaje = new MensajesWss;
                $mensaje->emisor = $validated['from'];
                $mensaje->receptor = $validated['to'];
                $mensaje->mensaje = $validated['message'];  // Asignamos el nuevo mensaje
                $mensaje->fecha = date('y-m-d'); // Guardar solo la fecha
                $mensaje->hora = Carbon::now()->format('H:i:s'); // Guardar solo la hora en formato HH:mm:ss
                $mensaje->save();
            }
            event(new EnviarMensaje($mensaje->emisor, $mensaje->mensaje));
            return response()->json(['status' => 'success', 'message' => 'Mensaje guardado exitosamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al guardar el mensaje.', 'error' => $e->getMessage()], 500);
        }
    }
    public function GetPersonal()
    {
        $usuarios = User::where('estado', 'Activo')->whereNotIn('rol', ['Recursos Humanos', 'Editor', 'TARJETAS', 'Cliente', 'Contador', 'INVENTARIO', 'Asist. Administrativo', 'Jefe Marketing y Publicidad'])->orderBy('name')->get();
        return response()->json($usuarios);
    }
}
