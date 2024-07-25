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
}
