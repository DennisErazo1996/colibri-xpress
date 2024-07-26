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

    public function paquetes()
    {
        return view("pages.paquetes");
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
                fecha_envio, 
                fecha_arribo 
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
            from users
        ");

        return view("pages.pedidos")
        ->with('usuarios', $clientes)
        ->with('idCaja', $id);
    }
}
