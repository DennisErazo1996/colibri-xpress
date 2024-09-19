<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}");
        }

        return abort(404);
    }

    public function vr()
    {
        return view("pages.virtual-reality");
    }

    public function rtl()
    {
        return view("pages.rtl");
    }

    public function profile()
    {
        return view("pages.profile-static");
    }

    public function signin()
    {
        return view("pages.sign-in-static");
    }

    public function signup()
    {
        return view("pages.sign-up-static");
    }

    public function paquetes($idCaja)
    {   
        $clientes = DB::select("
            select 
                id,
                'CX-' || LPAD(id::TEXT, 4, '10') AS locker_number,
                firstname || ' ' || lastname as nombre
            from users where deleted_at is null
        ");

        $statusEnvio = DB::select("select id_caja from cx_envios where id_caja = :id_caja
                    and deleted_at is null
                    group by id_caja", ['id_caja' => $idCaja]);

        return view("pages.paquetes")
        ->with('usuarios', $clientes)
        ->with('statusEnvio', $statusEnvio)
        ->with('idCaja', $idCaja);
        
    }

    public function cajas()
    {   
        if (Auth::user()->role != 'super-admin' && Auth::user()->role != 'admin'){
            return;
        }else{
            $dataCajas = DB::select("
            select 
                row_number() over(order by id desc) as numero, 
                id,
                'BOX-' || LPAD(id::TEXT, 4, '0') AS numero_caja,  
                to_char(fecha_envio, 'DD/MM/YYYY') as fecha_envio,
                to_char(fecha_arribo, 'DD/MM/YYYY') as fecha_arribo,
                to_char(created_at::date, 'DD/MM/YYYY') as fecha_registro
            from cx_cajas 
            where deleted_at is null 
            order by id desc
        ");

        return view("pages.cajas")->with('cajas', $dataCajas);
        }

        
    }

    public function pedidos($id)
    {  
        $clientes = DB::select("
            select 
                id,
                'CX-' || LPAD(id::TEXT, 4, '10') AS locker_number,
                firstname || ' ' || lastname as nombre
            from users where deleted_at is null
        ");

        return view("pages.pedidos")
        ->with('usuarios', $clientes)
        ->with('idCaja', $id);
    }

    public function pedidosCliente($id, $idCliente){

        $infoCliente = DB::select("select * from users where id = :idUsuario", ['idUsuario'=>$idCliente]);

        DB::select("SET lc_monetary = 'es_HN';");
        $totales = DB::select("
        select sum(x.total_ganancia)::numeric::money as total_ganancia, 
            sum(x.total_precio)::numeric::money as total_precio, 
            (sum(x.total_ganancia)+sum(x.total_precio))::numeric::money as total_pagar
            from (select
            cantidad, precio, ganancia,
            (ganancia*cantidad)::numeric::money as total_ganancia,
            (precio*cantidad)::numeric::money as total_precio
            from pedidos.cx_pedidos where deleted_at is null
            and id_caja = :idCaja and id_usuario = :idUsuario)x                                 
        ", ['idUsuario'=>$idCliente, 'idCaja'=>$id]);

        return view("pages.pedidos-cliente")
        ->with('idCaja', $id)
        ->with('totales', $totales)
        ->with('dataCliente', $infoCliente)
        ->with('idUsuario', $idCliente );
    }

    public function verProductos()
    {   

        return view("pages.productos");
    }

    public function clientes()
    {   
        return view("pages.clientes");
    }
}
