<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class VentasController extends Controller
{
    public function verClientes(Request $request){

        if ($request->ajax()) {
            
            $data = DB::select("select 
                        row_number() over(order by id desc) as no,
                        nombre_cliente || ' ' || apellido_cliente as nombre_cliente,
                        to_char(created_At::date, 'DD/MM/YYYY') as fecha_registro
                    from pedidos.cx_clientes where deleted_at is null");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }

    }

    public function registrarCliente(Request $request){
        
        $nombreCliente = $request->nombreCliente;
        $apellidoCliente = $request->apellidoCliente;
        $mensaje = "Cliente registrado correctamente";

        DB::select("Insert into pedidos.cx_clientes(nombre_cliente, apellido_cliente, created_at) values(:nombre_cliente, :apellido_cliente, NOW());",
        ['nombre_cliente' => $nombreCliente, 'apellido_cliente' => $apellidoCliente]); 

        return $mensaje;
    }

    public function verProductos(Request $request){

        if ($request->ajax()) {
            
            $data = DB::select("select 
                            row_number() over(order by id) as no,
                            id,
                            id_caja,
                            nombre,
                            cantidad,
                            precio_normal, precio_compra, precio_venta
                        from pedidos.cx_productos
                        where deleted_at is null");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })->addColumn('estadoPago', function($row) {
                    //$checked = $row->pagado ? 'checked' : '';
                    return "<div class='form-check form-switch justify-content-center'><input class='form-check-input' type='checkbox' id='chkPago'></div>";
                    //return "<input type='checkbox' class='estado-pago-checkbox' onchange='cambiarEstadoPago($row->id_caja, $row->id_usuario)' $checked />";
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }

    }
}
