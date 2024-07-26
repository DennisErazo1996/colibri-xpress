<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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

    public function agregarPedido(Request $request){

        $idCaja = $request->idCaja;
        $idCliente = $request->idCliente;
        $nombreProducto = $request->nombreProducto;
        $cantidad = $request->cantidad;
        $precio = $request->precio;
        $ganancia = $request->ganancia;
        $enlaceProducto = $request->enlaceProducto;
        $mensaje = "Se creo el pedido correctamente";

        DB::select("insert into pedidos.cx_pedidos(
                        id_caja, id_usuario, nombre_producto, url_producto, cantidad, precio, ganancia, created_at)
                    values(:id_caja, :id_cliente, :nombre_producto, :enlace_producto, :cantidad, :precio, :ganancia, now())",
                    
                    ['id_caja'=>$idCaja, 'id_cliente'=>$idCliente, 'nombre_producto'=>$nombreProducto, 'cantidad'=>$cantidad, 'precio'=>$precio,
                    'ganancia'=>$ganancia, 'enlace_producto'=>$enlaceProducto
                ]);

        return $mensaje;

    }

    public function pedidos(Request $request, $id){

        if ($request->ajax()) {
            DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("
            --SET lc_monetary = 'es_HN';
            Select
                row_number() over(order by max(cxp.created_at) desc) as no,
                cxp.id_caja,
                cxp.id_usuario,
                'CX-' ||  LPAD(u.id::TEXT, 4, '10') AS casillero,
                u.firstname || ' ' || u.lastname as nombre_cliente, 
                count(*) pedidos,
                ((sum(precio) + sum(ganancia))*max(cantidad))::numeric::money as total_pedido
                from pedidos.cx_pedidos cxp
                join users u on u.id = cxp.id_usuario
                where cxp.deleted_at is null and cxp.id_caja = :idCaja
                group by
                cxp.id_usuario,
                u.firstname,
                u.id, cxp.id_caja,
                cxp.id_usuario
                
                ;  
        ", ['idCaja'=> $id]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "<a class='btn btn-1 m-0' onclick='verPedidosCliente($row->id_usuario)' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver caja' data-container='body' data-animation='true'><i class='fi fi-sr-eye'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }


    }
}
