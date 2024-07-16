<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajasController extends Controller
{
    //
    public function crearCaja(Request $request){
        $fechaEnvio = $request->fechaEnvio;
        $fechaArribo = $request->fechaArribo;

        DB::select('insert into cx_cajas (fecha_envio, fecha_arribo, created_at) values(:fechaenvio, :fechaarribo, now())',
        ['fechaenvio' => $fechaEnvio, 'fechaarribo' => $fechaArribo]
        );

    return redirect()->back();

    }
}
