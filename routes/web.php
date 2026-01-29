<?php

use App\Http\Controllers\FaceCompareController;
use App\Http\Livewire\Comprar;
use App\Http\Livewire\GastoCaja;
use App\Http\Livewire\PanelEmpleados;
use App\Http\Livewire\PanelRecepcionista;
use App\Http\Livewire\PanelTratamiento;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PanelShow;
use App\Http\Livewire\Clientes;
use App\Http\Livewire\Administrador;
use App\Http\Livewire\Area;
use App\Http\Livewire\Llamadas;
use App\Http\Livewire\Clientes\VisualizarPago;
use App\Http\Livewire\Usuarios;
use App\Http\Livewire\Inventario;
use App\Http\Livewire\Estadistica;
use App\Http\Livewire\Tesoreria;
use App\Http\Livewire\Registros;
use App\Http\Livewire\Mensajeria;
use Dompdf\Dompdf;
use App\Models\User;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\FirmaController;
use App\Http\Livewire\Inventario\CargaMasiva;
use App\Http\Controllers\ImageController;
use App\Http\Livewire\Calendar;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\CustomLogin;
use App\Http\Livewire\Inmuebles;
use App\Http\Livewire\Marketing;
use App\Http\Livewire\PlanillaEditar;
use App\Http\Livewire\Rh;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GastoController;
use App\Http\Livewire\Operativos\FichaCliente;
use App\Http\Livewire\PanelInformacion;
use App\Http\Livewire\PanelPago;
use App\Http\Livewire\PanelTutoriales;
use App\Http\Livewire\Reporte;
use App\Http\Livewire\Vender;
use App\Http\Livewire\ComprarMiss;
use App\Http\Livewire\Crm\AbrirJson;
use App\Http\Livewire\PanelAgendados;
use App\Http\Livewire\PanelCrm;
use App\Http\Livewire\PanelDeudas;
use App\Http\Livewire\Paneles\ListaIventario;
use App\Http\Livewire\Paneles\PanelAlmacen;
use App\Http\Livewire\Paneles\PanelAlmacenTraspaso;
use App\Http\Livewire\Paneles\PanelMbq;
use App\Http\Livewire\PanelEstadisticaAgendados;
use App\Http\Livewire\PanelEstadisticaIngresos;
use App\Http\Livewire\PanelEstadisticaLlamadas;
use App\Http\Livewire\PanelEstadisticaTratamientos;
use App\Http\Livewire\PanelEstadisticaVentas;
use App\Http\Livewire\PanelGeneralCallRendimiento;
use App\Http\Livewire\PanelGeneralVentas;
use App\Http\Livewire\PanelGestionClientes;
use App\Http\Livewire\PanelLLamadas;
use App\Http\Livewire\PanelNodos;
use App\Http\Livewire\PanelTrapaso;
use App\Http\Livewire\PanelVentas;
use App\Http\Livewire\PanelWebCall;
use App\Models\Areas;


Route::post('/verificar-facial', [FaceCompareController::class, 'verificar']);

Route::get('/export-pdf', [GastoController::class, 'exportPdf'])->name('export-pdf');
Route::get('/login-direct', function () {
    $credentials = [
        'email' => request('email'),
        'password' => request('password'),
    ];
    if (Auth::attempt($credentials)) {
        $user = User::where('email', request('email'))->first();
        $user->key = request('key');
        $user->save();
        return redirect()->intended('/dashboard');
    }
    return redirect()->route('login')->withErrors([
        'email' => 'Las credenciales proporcionadas no son válidas.',
    ]);
})->name('login-direct');

Route::get('/custom-login/{idphone}', CustomLogin::class);
Route::get('/comprar-entradas', ComprarMiss::class);
Route::post('/guardar-datos', [CargaMasiva::class, 'guardarDatos']);
Route::get('/foto', function () {
    return view('foto');
});
Route::get('/truno/15103265151', function () {
    return view('sorteo');
});
Route::get('/truno/7165168165035', function () {
    return view('sorteo2');
});
Route::get('/miss', function () {
    return view('miss');
});
Route::get('/pdf/{idsuario}', function ($idusuario) {
    return view('pdfview', compact('idusuario'));
});
// Route::get('/pdf-preview', [PdfPreviewController::class, 'show'])->name('pdf-preview');
Route::get('/descargar-archivo/{filePath}', [FirmaController::class, 'downloadFile'])->name('descargar.archivo');
Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
Route::get('/', function () {
    return redirect('/login');
});
/**ACTUALIZADOR */
Route::get('/download/actualizador', function () {
    $filePath = public_path('actualizador.exe');
    return response()->download($filePath);
});
/**PAGOS DEL CLIENTE */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/pagos/{idoperativo}', PanelPago::class)->name('pagocliente');
});
/**PANEL DE TUTORIALES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/tutoriales', PanelTutoriales::class)->name('tutoriales');
});
/**INFORMACION DEL CLIENTE */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/informacion/{idoperativo}', PanelInformacion::class)->name('pagocliente');
});

/**CALENDARIO */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/calendario', Calendar::class)->name('calendario');
});

/**VISTA DE REPORTES PARA RECEPCION*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/reportes', Reporte::class)->name('reportes');
});

/**FICHA DE CLIENTE */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/ficha/{idoperativo}', FichaCliente::class);
});

/**PAGOS CLIENTE */
Route::get('/comprobante/{nombre}', VisualizarPago::class);

/** DASHBOARD EMPLEADOS*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard/{idsucursal}', function ($idsucursal) {

        $sucursal = Areas::find($idsucursal);
        $usuario = User::find(Auth::user()->id);
        $usuario->sucursal = $sucursal->area;
        $usuario->sesionsucursal = $idsucursal;
        $usuario->save();
        return redirect()->route('dashboard');
    });
});

/** DASHBOARD*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', PanelShow::class)->name('dashboard');
});
/** VENTA DE PRODUCTOS*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/vender', Vender::class)->name('vender');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/flujo', PanelGestionClientes::class)->name('gestionclientes');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/nodos', PanelNodos::class)->name('gestionNodos');
});

// routes/web.php
use App\Http\Controllers\SignatureController;

Route::post('/guardar-firma', [SignatureController::class, 'guardarFirma'])->name('guardar.firma');

Route::post('/firma/verificar', [FirmaController::class, 'verificar'])->name('firma.verificar');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/lista-productos', ListaIventario::class)->name('gestioninventarios');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/lista-almacen', PanelAlmacen::class)->name('gestionalmacen');
});
/** COMPRA DE PRODUCTOS*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/comprar', Comprar::class)->name('comprar');
});

//TRASPASO DE PRODUCTOS
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/traspaso', PanelTrapaso::class)->name('traspaso');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/traspaso-almacen', PanelAlmacenTraspaso::class)->name('traspaso-almacen');
});


/** GASTO*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/gastos', GastoCaja::class);
});
/**PLANILLA DE PAGOS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/planilla/{idplanilla}', PlanillaEditar::class)->name('planilla');
});


/**PLANILLA DE MISS BTQ */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/lista-mbq', PanelMbq::class)->name('panelmiss');
});
/**ADMINISTRACION */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/administrador', Administrador::class);
});
/**ESTADISTICAS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadistica-ingresos', PanelEstadisticaIngresos::class);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadistica-agendados', PanelEstadisticaAgendados::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadistica-llamadas', PanelEstadisticaLlamadas::class);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadistica-ventas', PanelEstadisticaVentas::class);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/estadistica-tratamientos', PanelEstadisticaTratamientos::class);
});
/*MARKETING */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/marketing', Marketing::class);
});

/** LLAMADAS*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/call-center', Llamadas::class);
});

/**SOLO LLAMADAS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/call-center-llamadas', PanelLLamadas::class);
});

/**SOLO LLAMADAS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/call-center-rendimiento', PanelGeneralCallRendimiento::class);
});

/** CRM*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/crm', PanelCrm::class);
});
/** RRHH*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/rh', Rh::class);
});

/** REGISTROS*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/registros', Registros::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/mensajes/{iduser}', AbrirJson::class);
});

/**CLIENTES */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/clientes', Clientes::class);
});
/**USUARIOS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/usuarios', Usuarios::class);
});
/**TRATAMIENTOS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/tratamientos', PanelTratamiento::class);
});
/**RECEPCION */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/recepcion', PanelRecepcionista::class);
});
/**VENTAS*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/ventas-general', PanelGeneralVentas::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/ventas', PanelVentas::class);
});


/**RECEPCION AGENDADOS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/agendados', PanelAgendados::class);
});
/**DEUDAS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/deudas', PanelDeudas::class);
});
/**INVENTARIO*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/inventario', Inventario::class);
});
/**INMUEBLE*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/inventario-inmueble', Inmuebles::class);
});
/**AREAS */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/areas', Area::class);
});
/*TESORERIA */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/tesoreria', Tesoreria::class);
});
/**MENSAJERIA */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/mensajeria', Mensajeria::class);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/llamadas-internas', PanelWebCall::class);
});

/**CREAR PDF */
Route::get('/generar-pdf/{idusuario}', function ($idusuario) {
    // Ruta de la imagen que deseas convertir en HTML
    $imagenPath = 'C:\xampp\htdocs\miora-project\public\storage\firmas\icono.png';

    // Obtener el tipo de archivo de la imagen
    $tipoArchivo = pathinfo($imagenPath, PATHINFO_EXTENSION);

    // Leer el contenido binario de la imagen
    $imagenBinario = file_get_contents($imagenPath);

    // Codificar la imagen en base64
    $imagenBase64 = 'data:image/' . $tipoArchivo . ';base64,' . base64_encode($imagenBinario);
    // Crear una instancia de Dompdf
    $dompdf = new Dompdf();
    $html = View::make('pdfview', compact('imagenBase64', 'idusuario'))->render();
    // Renderizar el HTML en el PDF
    $dompdf->loadHtml($html);
    // Establecer el tamaño del papel y la orientación (tamaño carta y orientación vertical)
    $dompdf->setPaper('letter', 'portrait');
    // Renderizar el PDF
    $dompdf->render();
    $usuario = DB::table('users')->where('id', $idusuario)->get();
    foreach ($usuario as $user) {
        $filename = date('Y-m-d') . $user->name . '-HISTORIAL-LLAMADAS' . '.pdf';
    }
    $filename = str_replace(' ', '', $filename);
    // Generar el nombre del archivo PDF

    // Obtener el contenido del PDF como una cadena de bytes
    $pdfContent = $dompdf->output();
    // Establecer las cabeceras para descargar el archivo
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($pdfContent));
    // Imprimir el contenido del PDF
    echo $pdfContent;
    // Detener la ejecución de Laravel para evitar que se muestre la página de error
    exit();
});
