<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $idUsuario = Auth::user()->id;

        $totales = DB::select("with paquetes_registrados as (
                select count(*) as paquetes_registrados from cx_paquetes p
                left join cx_envios e on p.id = e.id_paquete and e.deleted_at is null
                where p.id_usuario = :id_cliente and p.deleted_at is null
                and p.id not in(select id_paquete from cx_envios where deleted_at is null)
            ), paquetes_enviados as(
                select count(*) as paquetes_enviados from cx_paquetes p
                join cx_envios e on p.id = e.id_paquete and e.deleted_at is null
                where p.id_usuario = :id_cliente and p.deleted_at is null
            ), proximo_envio as (
                select
                    id as id_caja,
                    to_char(fecha_envio, 'DD/MM/YYYY') fecha_envio
                from cx_cajas
                    where deleted_at is null
                order by id desc
                limit 1
            )
            select 
                pg.paquetes_registrados,
                pe.paquetes_enviados,
                pd.fecha_envio
            from paquetes_registrados pg, paquetes_enviados pe, proximo_envio pd
        ", ['id_cliente' => $idUsuario]);

        $ultimosPaquetes = DB::select("
                        select 
                            row_number() over(order by id desc) as numero,
                            numero_tracking,
                            descripcion,
                            to_char(created_at::date, 'MM/DD/YYYY') AS fecha_registro,
                            to_char(created_at::time, 'HH12:MI:SS PM') as hora_registro
                        from cx_paquetes
                        where id_usuario = :id_cliente
                        limit 4", ['id_cliente' => $idUsuario]);


        return view('pages.dashboard')
        ->with('ultimosPaquetes', $ultimosPaquetes)
        ->with('totales', $totales);
    }
}
