<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Mail;
use App\Mail\PackageMail;

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
        $mensaje = "¡El pedido se agregó correctamente!";

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
                    $actions = "<a class='btn btn-1 m-0' onclick='verPedidosCliente($row->id_usuario)' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver pedidos' data-container='body' data-animation='true'><i class='fi fi-sr-eye'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }


    }

    public function verPedidosCliente(Request $request, $id, $idCliente){

        if ($request->ajax()) {
            DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("
            select 
                id,
                row_number() over(order by created_at desc) as no,
                nombre_producto,
                url_producto,
                cantidad,
                precio::numeric::money,
                ganancia::numeric::money,
                (precio+ganancia)::numeric::money as sub_total,
                ((precio+ganancia)*cantidad)::numeric::money as total,
                to_char(created_at::date, 'DD/MM/YYYY') as fecha_registro 
                from pedidos.cx_pedidos where deleted_at is null
                and id_caja = :idCaja and id_usuario = :idUsuario
                
                ;  
        ", ['idCaja'=> $id , 'idUsuario' =>$idCliente]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' onclick = 'editarPedido($row->id,  \"".$row->nombre_producto."\", \"".$row->url_producto."\", \"".$row->cantidad."\", \"".$row->precio."\", \"".$row->ganancia."\")'  data-bs-placement='top' title='Editar pedido' data-container='body' data-animation='true'><i class='fi fi-sr-edit'></i></a>
                                          <a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick = 'eliminarPedidoCliente($row->id)' data-bs-placement='top' title='Eliminar pedido' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>
                    ";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }


    }

    public function eliminarPedido(Request $request, $idCaja, $idCliente){
        $idPedido = $request->idPedido;
        $mensaje = "¡El pedido se eliminó correctamente!";

        DB::select('update pedidos.cx_pedidos set deleted_at = now() where id = :pedido and id_usuario = :idUsuario and id_caja = :caja',
            ['pedido' => $idPedido, 'idUsuario' => $idCliente, 'caja' => $idCaja]
        );

        //$prueba = "El pedido:".$idPedido." del cliente:".$idCliente." y de la caja:".$idCaja." se borro correctamente";

        return $mensaje;
    }


    public function editarPedido(Request $request){

        $idPedido = $request->idPedido;
        $idCaja = $request->idCaja;
        $idCliente = $request->idCliente;
        $nombreProducto = $request->nombreProducto;
        $cantidad = $request->cantidad;
        $precio = $request->precio;
        $ganancia = $request->ganancia;
        $enlaceProducto = $request->enlaceProducto;
        $mensaje = "El pedido se editó correctamente";

        DB::select("update pedidos.cx_pedidos set nombre_producto = :producto,
                url_producto = :urlProducto,
                cantidad = :cantidadProducto,
                precio = :precioProducto,
                ganancia = :gananciaProducto
                where id = :pedido and id_caja = :caja and id_usuario = :usuario
                ", ['producto'=>$nombreProducto, 'urlProducto'=>$enlaceProducto, 'cantidadProducto'=>$cantidad, 'precioProducto'=>$precio, 'gananciaProducto'=>$ganancia,
                'pedido'=>$idPedido, 'caja'=>$idCaja, 'usuario'=>$idCliente]);

        return $mensaje;
    }

    public function eliminarCaja($idCaja){

        $mensaje = "Se elimino correctamente la caja: ".$idCaja;

        DB::select("update cx_cajas set deleted_at = now() where id = :id ", ['id' => $idCaja]);

        return $mensaje;
    }

    public function verPaquetes(Request $request, $id){

        if ($request->ajax()) {
            //DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("
            Select
                cxp.id,
                row_number() over(order by max(cxp.created_at) desc) as no,
                cxp.id_caja,
                cxp.id_usuario,
                'CX-' ||  LPAD(u.id::TEXT, 4, '10') AS casillero,
                u.firstname || ' ' || u.lastname as nombre_cliente,
                cxp.numero_tracking,
                cxp.descripcion,
                to_char(cxp.created_at::date, 'MM/DD/YYYY') AS fecha_registro,
                to_char(cxp.created_at::time, 'HH24:MI:SS') as hora_registro
                from cx_paquetes cxp
                join users u on u.id = cxp.id_usuario
                where cxp.deleted_at is null and cxp.id_caja = :idCaja
                group by
                cxp.id_usuario,
                u.firstname,
                u.id, cxp.id_caja,
                cxp.id_usuario,
                cxp.numero_tracking,
                cxp.descripcion,
                cxp.created_at,
                cxp.id
                
                ;  
        ", ['idCaja'=> $id]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "<a class='btn btn-1 m-0' onclick='editarPaquete($row->id_usuario, $row->id)' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-sr-edit'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }
    }

    public function registrarPaquete(Request $request){

        $idCaja = $request->idCaja;
        $idCliente = $request->idCliente;
        $numeroTracking = $request->numeroTracking;
        $descripcionPaquete = $request->descripcionPaquete;
        $mensaje = "¡El paquete se registró correctamente!";


        DB::Select("set timezone TO 'GMT-6';");
        DB::select("insert into cx_paquetes(
                        id_caja, id_usuario, numero_tracking, descripcion, created_at)
                    values(:id_caja, :id_cliente, :numero_trackin, :descripcion_paquete, now())",
                    
                    ['id_caja'=>$idCaja, 'id_cliente'=>$idCliente, 'numero_trackin'=>$numeroTracking, 'descripcion_paquete'=>$descripcionPaquete]);
        
        
        
        $lockNum = DB::select("SELECT 'CX-' || LPAD(:id::TEXT, 4, '10') AS locker_number", ['id'=>$idCliente]);
        foreach($lockNum as $ln){
            $lockerNumber = $ln->locker_number;
        }
        
        $fechaEnvio = DB::select("select to_char(fecha_envio::date, 'DD/MM/YYYY') fecha_envio from cx_cajas where id = :id and deleted_at is null", ['id'=>$idCaja]);
        foreach($fechaEnvio as $fe){
            $envio = $fe->fecha_envio;
        }

        $user = DB::select("select * from users where id = :id_cliente and deleted_at is null", ['id_cliente' => $idCliente]);
        foreach($user as $u){
            $correo = $u->email;
            $nombre = $u->firstname;
        
        }

        Mail::to($correo)->send(new PackageMail($nombre, $lockerNumber, $envio, $numeroTracking));

        return $mensaje;

    }
}
