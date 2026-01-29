<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('image')->store('public');
      
        if(($request->modo && $request->area && $request->cantidadpago && $request->fechapagada)!="" ){
            $restante=0;
            $pago_cliente=0;
                $pagos = Pagos::find($request->iduser);
                if ($pagos) {
                    $total_por_pagar = DB::table('pagos')
                    ->where('iduser', $request->iduser)
                    ->where('estado', 'Activo')
                    ->count();
                    if($total_por_pagar==1 && ($request->cantidadpago < $pagos->cantidad)){
                        $request->reset(['openArea2','openArea','openArea3','openArea4','openArea5','$fechapagada']);
                        $request->emit('error','¡No es posible retrasar mas pagos!');
                    }
                    else{
                        if($total_por_pagar==1){
                            // $user=User::find($pagos->iduser);
                            // $user->estado="Inactivo";
                            // $user->save();
                        }
                        $pago_cliente_anterior= $pagos->cantidad;
                        $pagos->cantidad=$request->cantidadpago;
                        $pagos->fechapagado = $request->fechapagada;
                        $pagos->estado = "Inactivo";
                        $pagos->modo=$request->modo;
                        $pagos->pertence=$request->area;
                        $pagos->comentario=$request->comentario;
                        $pagos->save();
                        $paga_siguiente = DB::table('pagos')
                        ->where('iduser', $request->usuario->iduser)
                        ->where('estado', 'Activo')
                        ->first();
                        try {
                            // Code that may throw an exception
                            $pago_objet= Pagos::find($paga_siguiente->id);
                        if( $pago_objet){
        
                            if($request->cantidadpago >  $pago_cliente_anterior){
                                //Se resta el restante al siguiente pago
                               $pago_objet->cantidad=  $pago_objet->cantidad-($request->cantidadpago -  $pago_cliente_anterior); 
                             }
                             if($request->cantidadpago <  $pago_cliente_anterior){
                                $pago_objet->cantidad=  $pago_objet->cantidad+($pago_cliente_anterior - $request->cantidadpago ); 
                             }
                             $pago_objet->save();
                        }
                        $request->reset(['openArea2','openArea','openArea3','openArea4','fechapagada']);
                        $request->emitTo('cobranza.lista-cobranza','render');
                        $request->emit('alert','¡Pago realizado satisfactoriamente!');
                        } catch (ExceptionType $e) {
                            // Exception handling code
                        }      
                    }
                } 
        }
        else{
            $request->emit('error','¡Algo anda mal!');
        }
        return back()->with('success', 'La imagen se ha cargado correctamente.');
    }
   

}
