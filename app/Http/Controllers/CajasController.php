<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Mail;
use App\Mail\PackageMail;
use Auth;

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

    public function editarCaja(Request $request){

        $idCaja = $request->idCaja;
        $fechaEnvio = $request->fechaEnvio;
        $fechaArribo = $request->fechaArribo;
        $mensaje = "Caja editada correctamente";

        DB::select("update cx_cajas set fecha_envio = :fecha_envio,
                fecha_arribo = :fecha_arribo
                where id = :id_caja
                ", ['fecha_envio' => $fechaEnvio, 'fecha_arribo' => $fechaArribo,
                'id_caja' => $idCaja]);

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
                to_char(cxp.created_at::time, 'HH12:MI:SS PM') as hora_registro
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
                 
                    $actions = "<a class='btn btn-1 m-0' 
                        onclick='editarPaquete($row->id_usuario, $row->id, \"".htmlspecialchars($row->numero_tracking, ENT_QUOTES, 'UTF-8')."\", \"".htmlspecialchars($row->descripcion, ENT_QUOTES, 'UTF-8')."\")' 
                        data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'>
                        <i class='fi fi-ss-customize-edit'></i>
                    </a>";
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


        DB::Select("SET timezone TO 'America/Tegucigalpa';");
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

    public function editarPaquete(Request $request){

        $idCaja = $request->idCaja;
        $idPaquete = $request->idPaquete;
        $idCliente = $request->idCliente;
        $numeroTracking = $request->numeroTracking;
        $descripcionPaquete = $request->descripcionPaquete;
        
        $mensaje = "El paquete se editó correctamente";

        DB::select("update cx_paquetes set numero_tracking = :tracking,
                descripcion = :descripcion
                where id = :paquete and id_caja = :caja and id_usuario = :usuario
            ", ['tracking'=>$numeroTracking, 'descripcion'=>$descripcionPaquete, 
                'paquete'=>$idPaquete, 'caja'=>$idCaja, 'usuario'=>$idCliente]);

        return $mensaje;
    }

    public function enviarPaquetes(Request $request){

        $idCaja = $request->idCaja;
        $mensaje = null;
        
        $existe = DB::select("select id_caja from cx_envios where id_caja = :id_caja group by id_caja", ['id_caja' => $idCaja]);

        if($existe != null){

            $mensaje = "Los paquetes ya han sido enviados"; 
            return $mensaje; 

        }else{

            $paquetes = DB::select("select * from cx_paquetes
	                            where deleted_at is null and id_caja = :id_caja",
            ['id_caja' => $idCaja]);

            foreach($paquetes as $pq){

                /*$paquete = $pq->id;
                $caja = $pq->id_caja;*/

                DB::select("insert into cx_envios (id_caja, id_paquete, pagado, created_at) values(:idCaja, :idPaquete, false, now())",
                ['idCaja'=> $pq->id_caja, 'idPaquete' => $pq->id]);

                $mensaje = "Se cambio el estado de los paquetes correctamente";
            }
        }

        return $mensaje;
    }

    public function verPaquetesEnviados(Request $request, $id){
        if ($request->ajax()) {
            //DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("

                with paquetes_enviados as (
                select 
                    env.id as id_envio, 
                    env.id_paquete, env.id_caja, 
                    paq.id_usuario,
                    env.peso_envio,
                    env.precio_envio,
                    env.pagado
                from cx_envios env
                join cx_paquetes paq on paq.id = env.id_paquete 
                where env.id_caja = :idCaja
                and env.deleted_at is null and paq.deleted_at is null
                )
                select row_number() over(order by u.firstname) as no,
                pe.id_caja, pe.id_usuario,
                'CX-' ||  LPAD(u.id::TEXT, 4, '10') AS casillero,
                u.firstname || ' ' || u.lastname as nombre_cliente, 
                count(*) as numero_paquetes,
                coalesce(pe.peso_envio::text, '0.00 lb') as peso_envio,
                coalesce(pe.precio_envio::text, 'L 0.00') as precio_envio, 
                pe.pagado as pagado,
                pe.pagado as estado_pago
                from paquetes_enviados pe
                join users u on u.id = pe.id_usuario
                group by pe.id_usuario,      
                u.firstname,
                u.lastname,
                pe.id_caja,
                u.id,
                pe.peso_envio,
                pe.precio_envio,
                pe.pagado
                
                ;  
        ", ['idCaja'=> $id]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "<a class='btn btn-1 m-0' onclick='pesoPaquete($row->id_caja, $row->id_usuario,\"".$row->nombre_cliente."\", \"".$row->peso_envio."\", \"".$row->precio_envio."\")' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })
                ->addColumn('estadoPago', function($row) {
                    // Si el pago está realizado, marcar el checkbox como checked
                    $checked = $row->pagado ? 'checked' : '';
                    return "<div class='form-check form-switch justify-content-center'><input class='form-check-input' onchange='cambiarEstadoPago($row->id_usuario, this.checked)' type='checkbox' id='chkPago' $checked ></div>";
                    //return "<input type='checkbox' class='estado-pago-checkbox' onchange='cambiarEstadoPago($row->id_caja, $row->id_usuario)' $checked />";
                })
                ->rawColumns(['opcion', 'estadoPago'])
                ->make(true);
        }
    }


    public function guardarDatosEnvios(Request $request){

        $idUsuario = $request->idCliente;
        $precioEnvio = $request->precioEnvio;
        $pesoEnvio = $request->pesoEnvio;
        $idCaja = $request->idCaja;

        $mensaje = "Los datos se han guardado correctamente";
        
        DB::select("UPDATE cx_envios
                    SET peso_envio = :peso_envio,
                        --pagado = false
                        precio_envio = :precio_envio
                    FROM (
                        SELECT p.id_usuario, e.id_paquete, e.id_caja 
                        from cx_envios e 
                        join cx_paquetes p on p.id = e.id_paquete 
                        where p.id_usuario = :id_usuario and e.id_caja = :id_caja
                        ) AS x
                    WHERE cx_envios.id_paquete=x.id_paquete;
                    ", ['id_caja' => $idCaja, 'id_usuario' => $idUsuario, 'precio_envio' => $precioEnvio, 'peso_envio' => $pesoEnvio]);
        return $mensaje;
    }

    public function datosTotalesEnvio(Request $request){

        $idCaja = $request->idCaja;

        DB::select("SET lc_monetary = 'es_HN';");
        $data = DB::select("
                 select 
                    coalesce(sum(x.peso_envio), 0) as total_libras,
                    coalesce(sum(x.precio_envio), 0)::numeric::money as total_precio_envio,
                    coalesce((sum(x.precio_envio)/2),0)::numeric::money as mitad_ganancia,
                    coalesce(sum(case when x.pagado = true then x.precio_envio end), 0)::numeric::money as total_pagado
                    from (with paquetes_enviados as (
                        select 
                            env.id as id_envio, 
                            env.id_paquete, env.id_caja, 
                            paq.id_usuario,
                            env.peso_envio,
                            env.precio_envio,
                            env.pagado
                        from cx_envios env
                        join cx_paquetes paq on paq.id = env.id_paquete 
                        where env.id_caja = :id_caja
                        and env.deleted_at is null and paq.deleted_at is null
                        )
                        select 
                        peso_envio,
                        precio_envio,
                        pe.pagado
                        from paquetes_enviados pe
                        join users u on u.id = pe.id_usuario
                        group by pe.id_usuario,      
                        u.firstname,
                        u.lastname,
                        pe.id_caja,
                        u.id,
                        pe.peso_envio,
                        pe.precio_envio,
                        pe.pagado)x", ['id_caja' => $idCaja]);
        return response()->json([
            'data' => $data,
        ]);
    }

    public function actualizarEstadoPago(Request $request){

        $estadoPago = $request->estadoPago;
        $idUsuario = $request->idCliente;
        $idCaja = $request->idCaja;
        $mensaje = "Estado del pago actualizado correctamente";
        //$mensaje = $estadoPago.' '.$idUsuario.' '.$idCaja;
        $estado = null;

        if($estadoPago == 1){
            $estado = true;
        }else{
            $estado = false;
        }


        DB::select("UPDATE cx_envios
                    SET --peso_envio = :peso_envio,
                        pagado = :estado_pago
                        --precio_envio = :precio_envio
                    FROM (
                        SELECT p.id_usuario, e.id_paquete, e.id_caja 
                        from cx_envios e 
                        join cx_paquetes p on p.id = e.id_paquete 
                        where p.id_usuario = :id_usuario and e.id_caja = :id_caja
                        ) AS x
                    WHERE cx_envios.id_paquete=x.id_paquete;
                    ", ['id_caja' => $idCaja, 'id_usuario' => $idUsuario, 'estado_pago' => $estado]);
       
       return $mensaje;
    }

    public function indexEnvios(Request $request){

        $idUsuario = Auth::user()->id;
        $perPage = $request->input('per_page', 9); // Cantidad de envíos por página (default 6)

        $enviosData = DB::table('cx_paquetes as p')
            ->join('cx_cajas as c', 'c.id', '=', 'p.id_caja')
            ->select(
                'id_caja',
                DB::raw("'BOX-' || LPAD(c.id::TEXT, 4, '0') as lote"),
                DB::raw("to_char(fecha_envio, 'DD/MM/YYYY') as fecha_envio"),
                DB::raw("to_char(fecha_arribo, 'DD/MM/YYYY') as fecha_arribo")
            )
            ->where('id_usuario', $idUsuario)
            ->groupBy('id_caja', 'c.id', 'fecha_envio', 'fecha_arribo')
            ->orderBy('id_caja', 'desc')
            ->paginate($perPage);

        // Retornar la vista principal si no es una solicitud AJAX
        return view('pages.envios', compact('enviosData'));
    }
}
