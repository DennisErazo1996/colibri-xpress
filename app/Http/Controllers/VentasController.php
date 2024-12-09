<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Exception;

class VentasController extends Controller
{
    public function verClientes(Request $request){

        if ($request->ajax()) {
            
            $data = DB::select("select 
                        row_number() over(order by id desc) as no,
                        id,
                        nombre_cliente || ' ' || apellido_cliente as nombre_completo,
                        to_char(created_At::date, 'DD/MM/YYYY') as fecha_registro,
                        nombre_cliente,
                        apellido_cliente
                    from pedidos.cx_clientes where deleted_at is null");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "
                    <a class='btn btn-1 m-0' data-bs-toggle='tooltip' onclick='editarCliente($row->id, \"".htmlspecialchars($row->nombre_cliente, ENT_QUOTES, 'UTF-8')."\", \"".htmlspecialchars($row->apellido_cliente, ENT_QUOTES, 'UTF-8')."\")' data-bs-placement='top' title='Editar Cliente' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>
                    <a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarCliente($row->id)' data-bs-placement='top' title='Eliminar Cliente' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>
                    ";
                    
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }

    }

    public function editarCliente(Request $request){

        $idCliente = $request->idCliente;
        $nombreCliente = $request->nombreCliente;
        $apellidoCliente = $request->apellidoCliente;
        $mensaje = "Cliente editado correctamente";

        DB::select("update pedidos.cx_clientes set nombre_cliente = :nombre_cliente,
                apellido_cliente = :apellido_cliente
                where id = :id_cliente
                ", ['nombre_cliente' => $nombreCliente, 'apellido_cliente' => $apellidoCliente,
                'id_cliente' => $idCliente]);

        return $mensaje;
    }

    public function eliminarCliente(Request $request){

        $idCliente = $request->idCliente;
        $mensaje = "Cliente eliminado correctamente";

        DB::select("update pedidos.cx_clientes set deleted_at = now() where id = :idCliente", ['idCliente' => $idCliente]);

        return $mensaje;
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
            
            $data = DB::select("WITH cantidades_vendidas AS (
                                    SELECT COUNT(*) AS cantidad_vendida, p.id AS id_producto, p.nombre
                                    FROM pedidos.cx_productos p 
                                    JOIN pedidos.cx_ventas v ON v.id_producto = p.id AND v.deleted_at IS NULL
                                    WHERE p.deleted_at IS NULL
                                    GROUP BY p.id, p.nombre
                                ), 
                                cantidades_acreditadas AS (
                                    SELECT sum(coalesce(c.cantidad,0)) as cantidad_acreditada,
                                    p.id AS id_producto, p.nombre
                                    FROM pedidos.cx_productos p 
                                    JOIN pedidos.cx_creditos c ON c.id_producto = p.id AND c.deleted_at IS NULL
                                    WHERE p.deleted_at IS NULL
                                    group by p.id
                                    
                                )
                                SELECT 
                                    ROW_NUMBER() OVER(ORDER BY p.id DESC) AS no,
                                    p.id,
                                    id_caja,
                                    'BOX-' || LPAD(c.id::TEXT, 4, '0') AS numero_caja,
                                    p.nombre,
                                    COALESCE(p.cantidad - (COALESCE(cv.cantidad_vendida, 0) + COALESCE(ca.cantidad_acreditada, 0)), p.cantidad) AS cantidad,
                                    precio_normal, 
                                    precio_compra, 
                                    coalesce(precio_venta, 0.00) as precio_venta
                                FROM pedidos.cx_productos p
                                JOIN cx_cajas c ON p.id_caja = c.id AND c.deleted_at IS NULL
                                LEFT JOIN cantidades_vendidas cv ON cv.id_producto = p.id
                                LEFT JOIN cantidades_acreditadas ca ON ca.id_producto = p.id
                                WHERE p.deleted_at IS NULL and p.liquidado = false
                                AND (

                                    ((p.cantidad > 1) AND (
                                        (p.id NOT IN (SELECT id_producto FROM pedidos.cx_ventas WHERE deleted_at IS NULL)
                                        AND p.id NOT IN (SELECT id_producto FROM pedidos.cx_creditos WHERE deleted_at IS NULL))
                                        OR (COALESCE(cv.cantidad_vendida, 0) + COALESCE(ca.cantidad_acreditada, 0) < p.cantidad)
                                    ))
                                    
                                    OR (p.cantidad = 1 AND (
                                        (p.id NOT IN (SELECT id_producto FROM pedidos.cx_ventas WHERE deleted_at IS NULL)
                                        AND p.id NOT IN (SELECT id_producto FROM pedidos.cx_creditos WHERE deleted_at IS NULL))
                                        
                                    ))
                                );");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' onclick='editarProducto($row->id, \"".htmlspecialchars($row->nombre, ENT_QUOTES, 'UTF-8')."\", $row->cantidad, $row->precio_normal, $row->precio_compra, $row->precio_venta)' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>
                    <a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarProducto($row->id)' data-bs-placement='top' title='Eliminar Producto' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>";
                    return $actions;
                })->addColumn('estadoPago', function($row) {
                    // Si el pago está realizado, marcar el checkbox como checked
                    //$checked = $row->id ? 'checked' : '';
                    return "<div class='form-check form-switch justify-content-center'><input class='form-check-input' onchange='cambiarEstadoVenta($row->id, $row->precio_venta, $row->cantidad)' type='checkbox' id='chkPago-$row->id' ></div>";
                    //return "<input type='checkbox' class='estado-pago-checkbox' onchange='cambiarEstadoPago($row->id_caja, $row->id_usuario)' $checked />";
                })
                ->rawColumns(['opcion', 'estadoPago'])
                ->make(true);
        }

    }

    public function registrarProducto(Request $request){

        $idCaja = $request->idCaja;
        $nombreProducto = $request->nombreProducto;
        $precioNormal = $request->precioNormal;
        $precioCompra = $request->precioCompra;
        $precioVenta = $request->precioVenta;
        $cantidad = $request->cantidad;
        $mensaje = "Producto registrado correctamente";

        try {
            DB::beginTransaction();
    
            DB::select("insert into pedidos.cx_productos(nombre, precio_normal, precio_compra, precio_venta, created_at, id_caja, cantidad)
                    values(:nombre_producto, :precio_normal, :precio_compra, :precio_venta, now(), :id_caja, :cantidad)", 
                    ['nombre_producto' => $nombreProducto, 'precio_normal' => $precioNormal, 'precio_compra' => $precioCompra, 'precio_venta' => $precioVenta,
                    'id_caja' => $idCaja, 'cantidad' => $cantidad]);
    
            DB::commit();

            return $mensaje;

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
       
    }

    public function registrarVenta(Request $request)
{
    $idCliente = $request->idCliente;
    $idProducto = $request->idProducto;
    $metodoPago = $request->metodoPago;
    $precioVenta = $request->precioVenta;
    $cuotas = $request->cuotas;
    $cantidad = $request->cantidad;
    $totalDeuda = null;
    $mensaje = "Venta registrada correctamente";

    if ($metodoPago == 2) { 
       if ($cantidad == 1) {
                DB::select("INSERT INTO pedidos.cx_creditos(id_cliente, id_producto, monto_adeudado, cuotas, created_at, cantidad)
                VALUES (:id_cliente, :id_producto, :monto_adeudado, :cuotas_credito, now(), :cantidad)",
                [
                    'id_cliente' => $idCliente,
                    'id_producto' => $idProducto,
                    'monto_adeudado' => $precioVenta,
                    'cuotas_credito' => $cuotas,
                    'cantidad' => $cantidad
                ]);
       }else{

                $totalDeuda = $precioVenta * $cantidad;

                DB::select("INSERT INTO pedidos.cx_creditos(id_cliente, id_producto, monto_adeudado, cuotas, created_at, cantidad)
                VALUES (:id_cliente, :id_producto, :monto_adeudado, :cuotas_credito, now(), :cantidad)", 
                [
                    'id_cliente' => $idCliente,
                    'id_producto' => $idProducto,
                    'monto_adeudado' => $totalDeuda,  
                    'cuotas_credito' => $cuotas,
                    'cantidad' => $cantidad
                ]);
       }
    } else { 
        for ($i = 0; $i < $cantidad; $i++) {
            DB::select("INSERT INTO pedidos.cx_ventas(id_producto, id_metodo_pago, created_at, precio_venta, id_cliente)
                        VALUES (:id_producto, :id_metodo_pago, now(), :precio_venta, :id_cliente)", 
                        [
                            'id_producto' => $idProducto,
                            'id_metodo_pago' => $metodoPago,
                            'precio_venta' => $precioVenta, 
                            'id_cliente' => $idCliente
                        ]);
        }   
    }

    return $mensaje;
}

    public function verVentas(Request $request){
        if ($request->ajax()) {
            
            DB::select("SET lc_monetary = 'es_HN';");
            /*$data = DB::select("select row_number() over( order by x.id desc) as no, x.* from
                        (with cuotas as (select 
                            id_credito,
                            count(*) as cuotas_pagadas,
                            sum(monto_abonado) as monto_abonado
                        from pedidos.cx_cuotas_credito
                        where deleted_at is null
                        group by id_credito),
                        credito_pagado as(
                            select 
                            p.id as id_producto,
                            cr.id,
                            row_number() over(order by cr.id desc) as no,
                            c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                            p.nombre as nombre_producto,
                            coalesce(cr.cantidad,1) as cantidad,
                            cr.monto_adeudado::numeric::money,
                            cr.cuotas,
                            coalesce(cu.cuotas_pagadas, 0) as cuotas_pagadas,
                            case when coalesce(cu.cuotas_pagadas, 0) = cr.cuotas then 'Pagado' else 'No pagado' end as estado,
                            coalesce(cu.monto_abonado, 0)::numeric::money as monto_abonado,
                            to_char(c.created_at::date, 'DD/MM/YYYY') as fecha_compra
                        from pedidos.cx_creditos cr
                        join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                        join pedidos.cx_clientes c on c.id = cr.id_cliente and c.deleted_at is null
                        left join cuotas cu on cu.id_credito = cr.id	
                        where cr.deleted_at is null
                        )
                        select 
                            count(*) as cantidad,
                            max(v.id) as id,
                            max(p.nombre) as nombre,
                            sum(v.precio_venta)::numeric::money as precio_venta,
                            c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                            initcap(lower(mp.descripcion)) as metodo_pago,
                            to_char(max(v.created_at)::date, 'DD/MM/YYYY') as fecha_compra
                        from pedidos.cx_ventas v
                        join pedidos.cx_productos p on p.id = v.id_producto and p.deleted_at is null
                        join pedidos.cx_clientes c on c.id = v.id_cliente and c.deleted_at is null
                        join pedidos.cx_metodos_pago mp on mp.id = v.id_metodo_pago and mp.deleted_At is null
                        where v.deleted_at is null
                        group by v.id_producto,
                        c.nombre_cliente, c.apellido_cliente,
                        mp.descripcion
                        union all
                        select cp.cantidad, cp.id_producto,
                            cp.nombre_producto as nombre, cp.monto_abonado as precio_venta, cp.comprador,
                            'Credito' as metodo_pago, cp.fecha_compra
                            from credito_pagado cp
                            where cp.estado = 'Pagado'
                            group by cp.cantidad,
                            cp.nombre_producto, cp.monto_abonado,
                            cp.comprador,cp.fecha_compra,cp.id_producto)x
                                ");*/
            
            $data = DB::select("
                select 
                    row_number() over( order by max(v.id) desc) as no,
                    count(*) as cantidad,
                    max(v.id) as id,
                    max(p.nombre) as nombre,
                    '$' || sum(p.precio_compra) as precio_compra,
                    sum(v.precio_venta)::numeric::money as precio_venta,
                    c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                    initcap(lower(mp.descripcion)) as metodo_pago,
                    to_char(max(v.created_at)::date, 'DD/MM/YYYY') as fecha_compra
                from pedidos.cx_ventas v
                join pedidos.cx_productos p on p.id = v.id_producto and p.deleted_at is null
                join pedidos.cx_clientes c on c.id = v.id_cliente and c.deleted_at is null
                join pedidos.cx_metodos_pago mp on mp.id = v.id_metodo_pago and mp.deleted_At is null
                where v.deleted_at is null and v.liquidado = false
                group by v.id_producto,
                c.nombre_cliente, c.apellido_cliente,
                mp.descripcion

            ");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarVenta($row->id)' data-bs-placement='top' title='Eliminar venta' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }
    }

    public function verCreditos(Request $request){
        if ($request->ajax()) {
            
            DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("with cuotas as (select 
                            id_credito,
                            count(*) as cuotas_pagadas,
                            sum(monto_abonado) as monto_abonado
                        from pedidos.cx_cuotas_credito
                        where deleted_at is null
                        group by id_credito)
                        select 
                            cr.id,
                            row_number() over(order by cr.id desc) as no,
                            c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                            p.nombre as nombre_producto,
                            '$' || p.precio_compra*coalesce(cr.cantidad,1) as precio_compra,
                            coalesce(cr.cantidad,1) as cantidad,
                            cr.monto_adeudado::numeric::money,
                            cr.cuotas,
                            coalesce(cu.cuotas_pagadas, 0) as cuotas_pagadas,
                            case when (coalesce(cu.cuotas_pagadas, 0) = cr.cuotas and cr.monto_adeudado =  coalesce(cu.monto_abonado, 0)) or ( coalesce(cu.monto_abonado, 0) > cr.monto_adeudado  and coalesce(cu.cuotas_pagadas, 0) > cr.cuotas)
                                or (coalesce(cu.cuotas_pagadas, 0) < cr.cuotas and coalesce(cu.monto_abonado, 0) = cr.monto_adeudado) or (coalesce(cu.cuotas_pagadas, 0) > cr.cuotas and coalesce(cu.monto_abonado, 0) = cr.monto_adeudado)
                                then 'Pagado' else 'No pagado' end as estado,
                            coalesce(cu.monto_abonado, 0)::numeric::money as monto_abonado,
                            to_char(c.created_at::date, 'DD/MM/YYYY') as fecha_compra
                        from pedidos.cx_creditos cr
                        join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                        join pedidos.cx_clientes c on c.id = cr.id_cliente and c.deleted_at is null
                        left join cuotas cu on cu.id_credito = cr.id	
                        where cr.deleted_at is null and cr.liquidado = false
                                ");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-success btn-1 m-0' data-bs-toggle='tooltip' onclick='agregarCuota($row->id)' data-bs-placement='top' title='Agregar pago' data-container='body' data-animation='true'><i class='fi fi-ss-money'></i></a>
                    <a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarCredito($row->id)' data-bs-placement='top' title='Eliminar Credito' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }
    }

    public function datosTotalesProductos(Request $request){

        DB::select("SET lc_monetary = 'es_HN';");
        $data = DB::select("
                 WITH cantidades_vendidas AS (
                    SELECT COUNT(*) AS cantidad_vendida, p.id AS id_producto, p.nombre
                    FROM pedidos.cx_productos p 
                    JOIN pedidos.cx_ventas v ON v.id_producto = p.id AND v.deleted_at IS NULL
                    WHERE p.deleted_at IS NULL
                    GROUP BY p.id, p.nombre
                ), 
                cantidades_acreditadas AS (
                    SELECT COUNT(*) AS cantidad_acreditada, p.id AS id_producto, p.nombre
                    FROM pedidos.cx_productos p 
                    JOIN pedidos.cx_creditos c ON c.id_producto = p.id AND c.deleted_at IS NULL
                    WHERE p.deleted_at IS NULL
                    GROUP BY p.id, p.nombre
                ), productos as (
                    SELECT 
                        ROW_NUMBER() OVER(ORDER BY p.id DESC) AS no,
                        p.id,
                        id_caja,
                        'BOX-' || LPAD(c.id::TEXT, 4, '0') AS numero_caja,
                        p.nombre,
                        COALESCE(p.cantidad - (COALESCE(cv.cantidad_vendida, 0) + COALESCE(ca.cantidad_acreditada, 0)), p.cantidad) AS cantidad,
                        precio_normal, 
                        precio_compra, 
                        precio_venta,
                        precio_venta*COALESCE(p.cantidad - (COALESCE(cv.cantidad_vendida, 0) + COALESCE(ca.cantidad_acreditada, 0)), p.cantidad) as total_precio_producto,
                        precio_compra*COALESCE(p.cantidad - (COALESCE(cv.cantidad_vendida, 0) + COALESCE(ca.cantidad_acreditada, 0)), p.cantidad) as total_precio_inversion
                    FROM pedidos.cx_productos p
                    JOIN cx_cajas c ON p.id_caja = c.id AND c.deleted_at IS NULL
                    LEFT JOIN cantidades_vendidas cv ON cv.id_producto = p.id
                    LEFT JOIN cantidades_acreditadas ca ON ca.id_producto = p.id
                    WHERE p.deleted_at IS NULL and p.liquidado = false
                    AND (
                        -- Condición general para productos con cantidad mayor a 1
                        ((p.cantidad > 1) AND (
                            (p.id NOT IN (SELECT id_producto FROM pedidos.cx_ventas WHERE deleted_at IS NULL)
                            AND p.id NOT IN (SELECT id_producto FROM pedidos.cx_creditos WHERE deleted_at IS NULL))
                            OR (COALESCE(cv.cantidad_vendida, 0) + COALESCE(ca.cantidad_acreditada, 0) < p.cantidad)
                        ))
                        -- Condición específica para productos con cantidad igual a 1
                        OR (p.cantidad = 1 AND (
                            (p.id NOT IN (SELECT id_producto FROM pedidos.cx_ventas WHERE deleted_at IS NULL)
                            AND p.id NOT IN (SELECT id_producto FROM pedidos.cx_creditos WHERE deleted_at IS NULL))

                        ))
                    )
            )
            select 
                coalesce(sum(cantidad), 0) as total_productos,
                '$ ' || coalesce(sum(total_precio_inversion), 0) as total_inversion,
                coalesce(sum(total_precio_producto), 0)::numeric::money as total_venta
            from productos");
        return response()->json([
            'data' => $data,
        ]);
    }

    public function editarProducto(Request $request){

        $idProducto = $request->idProducto;
        $nombreProducto = $request->nombreProducto;
        $cantidadProducto = $request->cantidadProducto;
        $precioNormal = $request->precioNormal;
        $precioCompra = $request->precioCompra;
        $precioVenta = $request->precioVenta;
        $mensaje = "Producto editado correctamente";

       try {
            DB::beginTransaction();

            DB::select("update pedidos.cx_productos set nombre = :nombre_producto,
                cantidad = :cantidad_producto,
                precio_normal = :precio_normal,
                precio_compra = :precio_compra,
                precio_venta = :precio_venta
                where id = :id_producto
                ", ['nombre_producto' => $request->nombreProducto, 'cantidad_producto' => $request->cantidadProducto,
                'precio_normal' => $request->precioNormal, 'precio_compra' => $request->precioCompra,
                'precio_venta' => $request->precioVenta, 'id_producto' => $request->idProducto]);

            DB::commit();

            return $mensaje;

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }       
    }

    public function datosTotalesVentas(){

        DB::select("SET lc_monetary = 'es_HN';");
        $data = DB::select("
                 select
                    coalesce(sum(case when v.id_metodo_pago = 1 then v.precio_venta else 0 end), 0)::numeric::money as total_efectivo,
                    coalesce(sum(case when v.id_metodo_pago = 3 then v.precio_venta else 0 end), 0)::numeric::money as total_bac,
                    coalesce(sum(case when v.id_metodo_pago = 4 then v.precio_venta else 0 end), 0)::numeric::money as total_ficohsa,
                    coalesce(sum(case when v.id_metodo_pago = 5 then v.precio_venta else 0 end), 0)::numeric::money as total_atlantida,
                    coalesce(sum(case when v.id_metodo_pago = 6 then v.precio_venta else 0 end), 0)::numeric::money as total_banpais,
                    coalesce(sum(case when v.id_metodo_pago = 7 then v.precio_venta else 0 end), 0)::numeric::money as total_occidente,
                    coalesce(sum(case when v.id_producto is not null then v.precio_venta end), 0)::numeric::money as total_venta,
                    '$ ' || coalesce(sum(p.precio_compra), 0) as total_inversion,
                    coalesce(sum(case when v.id_producto is not null then v.precio_venta end), 0) as total_venta_format,
                    coalesce(sum(p.precio_compra), 0) as total_inversion_format
                from pedidos.cx_ventas v
                join pedidos.cx_productos p on p.id = v.id_producto and p.deleted_at is null
                    where v.deleted_at is null and v.liquidado = false");
        return response()->json([
            'data' => $data,
        ]);      
    }

    public function eliminarVenta(Request $request){

        $idVenta = $request->idVenta;
        $mensaje = "Venta eliminada correctamente";

        try {
            DB::beginTransaction();
            DB::select("update pedidos.cx_ventas set deleted_at = now() where id = :idVenta", ['idVenta' => $idVenta]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;
    }

    public function eliminarProducto(Request $request){

        $idProducto = $request->idProducto;
        $mensaje = "Producto eliminado correctamente";

        try {
            DB::beginTransaction();
            DB::select("update pedidos.cx_productos set deleted_at = now() where id = :idProducto", ['idProducto' => $idProducto]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;
    }

    public function eliminarCredito(Request $request){

        $idCredito = $request->idCredito;
        $mensaje = "Credito eliminado correctamente";

        try {
            DB::beginTransaction();
            DB::select("update pedidos.cx_creditos set deleted_at = now() where id = :idCredito", ['idCredito' => $idCredito]);
            DB::select("update pedidos.cx_cuotas_credito set deleted_at = now() where id_credito = :idCredito", ['idCredito' => $idCredito]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;
    }

    public function eliminarCuota(Request $request){

        $idCuota = $request->idCuota;
        $mensaje = null;

        try {
            DB::beginTransaction();

           DB::select("update pedidos.cx_cuotas_credito set deleted_at = now() where id = :id", ['id' => $idCuota]);
           $mensaje = "Cuota eliminada correctamente";

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $mensaje = $e->getMessage();
        }

        return $mensaje;
    }

    public function datosTotalesCreditos(){

        DB::select("SET lc_monetary = 'es_HN';");
        
        /*$data = DB::select("
                with cx_creditos as (select
                    cr.id,
                    p.nombre,
                    p.precio_compra,
                    cr.cantidad,
                    p.precio_venta,
                    monto_adeudado,
                    precio_compra*coalesce(cr.cantidad, 1) as total_precio_compra
                    from pedidos.cx_creditos cr
                    join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                    --left join pedidos.cx_cuotas_credito cu on cu.id_credito = cr.id and cu.deleted_at is null
                    where cr.deleted_at is null and cr.liquidado = false and p.liquidado = false)
                    select
                    '$ ' || coalesce(sum(cr.total_precio_compra), 0) as total_inversion, 
                    coalesce(sum(cr.monto_adeudado), 0)::numeric::money as total_monto_adeudado,
                    coalesce(sum(cu.monto_abonado), 0)::numeric::money as total_monto_abonado,
                    coalesce(sum(cr.total_precio_compra), 0) as total_inversion_format

                    from cx_creditos cr
                    left join pedidos.cx_cuotas_credito cu on cu.id_credito = cr.id and cu.deleted_at is null");*/
        $data = DB::select("select
                    '$ ' || coalesce(sum(case when cr.estado = 'Pagado' then cr.precio_compra end), 0) as total_inversion_pagada,
                    '$ ' || coalesce(sum(case when cr.estado = 'No pagado' then cr.precio_compra end), 0) as total_inversion,
                    coalesce(sum(cr.monto_adeudado), 0)::numeric::money as total_monto_adeudado,
                    coalesce(sum(case when cr.estado = 'No pagado' then cr.monto_abonado end), 0)::numeric::money as total_monto_abonado,
                    coalesce(sum(cr.precio_compra), 0) as total_inversion_format,
                    coalesce(sum(case when cr.estado = 'Pagado' then cr.monto_abonado end), 0)::numeric::money as total_productos_pagados,
                    coalesce(sum(case when cr.estado = 'Pagado' then cr.monto_abonado end), 0) as total_productos_pagados_format,
                    coalesce(sum(case when cr.estado = 'Pagado' then cr.precio_compra end), 0) as total_inversion_pagada_format,
                    count(case when cr.estado = 'Pagado' then 1 end) as productos_pagados from
                    (with cuotas as (select 
                        id_credito,
                        count(*) as cuotas_pagadas,
                        sum(monto_abonado) as monto_abonado
                    from pedidos.cx_cuotas_credito
                    where deleted_at is null
                    group by id_credito)
                    select 
                        cr.id,
                        row_number() over(order by cr.id desc) as no,
                        c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                        p.nombre as nombre_producto,
                        p.precio_compra*coalesce(cr.cantidad,1) as precio_compra,
                        coalesce(cr.cantidad,1) as cantidad,
                        cr.monto_adeudado,
                        cr.cuotas,
                        coalesce(cu.cuotas_pagadas, 0) as cuotas_pagadas,
                        case when (coalesce(cu.cuotas_pagadas, 0) = cr.cuotas and cr.monto_adeudado =  coalesce(cu.monto_abonado, 0)) or ( coalesce(cu.monto_abonado, 0) > cr.monto_adeudado  and coalesce(cu.cuotas_pagadas, 0) > cr.cuotas)
                            or (coalesce(cu.cuotas_pagadas, 0) < cr.cuotas and coalesce(cu.monto_abonado, 0) = cr.monto_adeudado) or (coalesce(cu.cuotas_pagadas, 0) > cr.cuotas and coalesce(cu.monto_abonado, 0) = cr.monto_adeudado)
                            then 'Pagado' else 'No pagado' end as estado,
                        coalesce(cu.monto_abonado, 0) as monto_abonado,
                        to_char(c.created_at::date, 'DD/MM/YYYY') as fecha_compra,
                        cr.liquidado
                    from pedidos.cx_creditos cr
                    join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                    join pedidos.cx_clientes c on c.id = cr.id_cliente and c.deleted_at is null
                    left join cuotas cu on cu.id_credito = cr.id	
                    where cr.deleted_at is null and cr.liquidado = false) cr");
                
        
        return response()->json([
            'data' => $data,
        ]);
                
    }

    public function registrarCuota(Request $request){
        
        $idCredito = $request->idCredito;
        $idMetodoPago = $request->idMetodoPago;
        $montoAbonado = $request->montoAbonado;
        $mensaje = "Cuota registrada exitosamente";

        try {
            DB::beginTransaction();
            DB::select("insert into pedidos.cx_cuotas_credito (id_credito, id_metodo_pago, monto_abonado, created_at) values(:idCredito, :idMetodoPago, :montoAbonado, now())",
            ['idCredito' => $idCredito, 'idMetodoPago' => $idMetodoPago, 'montoAbonado' => $montoAbonado]
            );
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;

    }


    public function verCuotasCredito(Request $request){

        $idCredito = $request->idCredito;

        if ($request->ajax()) {
            
            $data = DB::select("select 
                                row_number() over(order by cc.id desc) as no,
                                cc.id,
                                cc.id_credito,
                                cl.nombre_cliente || ' ' || cl.apellido_cliente as nombre_cliente,
                                initcap(lower(mp.descripcion)) as metodo_pago,
                                cc.monto_abonado,
                                to_char(cc.created_at::date, 'DD/MM/YYYY') as fecha_pago
                                from pedidos.cx_cuotas_credito cc
                                join pedidos.cx_creditos c on c.id = cc.id_credito and c.deleted_at is null
                                join pedidos.cx_clientes cl on cl.id = c.id_cliente and cl.deleted_At is null
                                join pedidos.cx_metodos_pago mp on mp.id = cc.id_metodo_pago and mp.deleted_at is null
                                where cc.deleted_at is null and cc.id_credito = :idCredito
                                ", ['idCredito' => $idCredito]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('acciones', function ($item) {
                    return '<a onclick="eliminarCuota('.$item->id.')" class="btn btn-danger btn-sm"><i class="fi fi-sr-trash"></i></a>';
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
    }
    
    public function liquidarVentas(Request $request){

        $totalVenta = $request->totalVenta;
        $totalInversion = $request->totalInversion;
        $idVenta = $request->idVenta;
        $mensaje = "Ventas liquidada correctamente";

        

       if($idVenta == 1){
            try {
                DB::beginTransaction();

                DB::select("insert into pedidos.cx_liquidaciones(ganancia, inversion, id_metodo_pago, created_at) values(:total_venta, :total_inversion, :id_venta, now())",
                ['total_venta' => $totalVenta, 'total_inversion' => $totalInversion, 'id_venta' => $idVenta]);
                
                $productos = DB::select("select id from pedidos.cx_ventas where deleted_at is null and liquidado = false");
                foreach($productos as $p){
                    DB::select("update pedidos.cx_ventas set liquidado = true where id = :id", ['id' => $p->id_producto]);
                }
                
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['mensaje' => $e->getMessage()], 500);
            }
       }  

        return $mensaje;
    }

    public function liquidarCreditos(Request $request){

        $totalVenta = $request->totalVenta;
        $totalInversion = $request->totalInversion;
        $idVenta = $request->idVenta;
        $mensaje = "Creditos liquidado correctamente";

        
        try {
                DB::beginTransaction();
                
                DB::select("insert into pedidos.cx_liquidaciones(ganancia, inversion, id_metodo_pago, created_at) values(:total_venta, :total_inversion, :id_venta, now())",
                ['total_venta' => $totalVenta, 'total_inversion' => $totalInversion, 'id_venta' => $idVenta]);

                $creditos = DB::select("with cuotas as (select 
                                        id_credito,
                                        count(*) as cuotas_pagadas,
                                        sum(monto_abonado) as monto_abonado
                                    from pedidos.cx_cuotas_credito
                                    where deleted_at is null
                                    group by id_credito),
                                    productos_credito as(
                                    select 
                                        cr.id_producto,
                                        cr.id_credito,
                                        row_number() over(order by cr.id desc) as no,
                                        c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                                        p.nombre as nombre_producto,
                                        '$' || p.precio_compra*coalesce(cr.cantidad,1) as precio_compra,
                                        coalesce(cr.cantidad,1) as cantidad,
                                        cr.monto_adeudado::numeric::money,
                                        cr.cuotas,
                                        coalesce(cu.cuotas_pagadas, 0) as cuotas_pagadas,
                                        case when (coalesce(cu.cuotas_pagadas, 0) = cr.cuotas and cr.monto_adeudado =  coalesce(cu.monto_abonado, 0)) or ( coalesce(cu.monto_abonado, 0) > cr.monto_adeudado  and coalesce(cu.cuotas_pagadas, 0) > cr.cuotas)
                                            or (coalesce(cu.cuotas_pagadas, 0) < cr.cuotas and coalesce(cu.monto_abonado, 0) = cr.monto_adeudado) or (coalesce(cu.cuotas_pagadas, 0) > cr.cuotas and coalesce(cu.monto_abonado, 0) = cr.monto_adeudado)
                                            then 'Pagado' else 'No pagado' end as estado,
                                        coalesce(cu.monto_abonado, 0)::numeric::money as monto_abonado,
                                        to_char(c.created_at::date, 'DD/MM/YYYY') as fecha_compra,
                                        cr.liquidado
                                    from pedidos.cx_creditos cr
                                    join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                                    join pedidos.cx_clientes c on c.id = cr.id_cliente and c.deleted_at is null
                                    left join cuotas cu on cu.id_credito = cr.id	
                                    where cr.deleted_at is null and cr.liquidado = false
                                    )
                                    select pc.id_credito from productos_credito pc
                                    where pc.estado = 'Pagado'");
                foreach($creditos as $c){
                    DB::select("update pedidos.cx_creditos set liquidado = true where id = :id", ['id' => $c->id_producto]);
                }
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['mensaje' => $e->getMessage()], 500);
            }
       }
    
        public function verLiquidaciones(Request $request){

            $data = DB::select("select 
                            max(v.id) as id,
                            max(p.nombre) as nombre_producto,
                            sum(p.precio_compra) as precio_compra,
                            sum(v.precio_venta) as precio_venta,
                            c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                            to_char(max(v.created_at)::date, 'DD/MM/YYYY') as fecha_compra,
                            'Al Contado' as metodo_compra
                        from pedidos.cx_ventas v
                        join pedidos.cx_productos p on p.id = v.id_producto and p.deleted_at is null
                        join pedidos.cx_clientes c on c.id = v.id_cliente and c.deleted_at is null
                        join pedidos.cx_metodos_pago mp on mp.id = v.id_metodo_pago and mp.deleted_At is null
                        where v.deleted_at is null and v.liquidado = true
                        group by v.id_producto,
                        c.nombre_cliente, c.apellido_cliente,
                        mp.descripcion
                        union all
                        select * from (with cuotas as (select 
                            id_credito,
                            count(*) as cuotas_pagadas,
                            sum(monto_abonado) as monto_abonado
                        from pedidos.cx_cuotas_credito
                        where deleted_at is null
                        group by id_credito)
                        select 
                            cr.id,
                            p.nombre as nombre_producto,
                            p.precio_compra*coalesce(cr.cantidad,1) as precio_compra,
                            coalesce(cu.monto_abonado, 0) as precio_venta,
                            c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                            to_char(c.created_at::date, 'DD/MM/YYYY') as fecha_compra,
                            'Al Crédito' as metodo_compra
                        from pedidos.cx_creditos cr
                        join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                        join pedidos.cx_clientes c on c.id = cr.id_cliente and c.deleted_at is null
                        left join cuotas cu on cu.id_credito = cr.id	
                        where cr.deleted_at is null and cr.liquidado = true)x  
                        ");
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        public function montosLiquidados(Request $request){

            Db::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("select 
                                    row_number() over(order by id) as no,
                                    '$ '|| inversion as inversion,
                                    ganancia::numeric::money,
                                    case when id_metodo_pago = 1 then 'Ventas al contado' else 'Ventas al crédito' end as metodo_pago,
                                    to_char(created_at::date, 'DD/MM/YYYY') as fecha_liquidacion
                                from pedidos.cx_liquidaciones
                                where deleted_at is null");
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }   

}
