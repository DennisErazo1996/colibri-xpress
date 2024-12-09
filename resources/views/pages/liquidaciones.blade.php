@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Liquidaciones'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0 p-4">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <nav aria-label="breadcrumb">
                            <h2>Montos Liquidados</h2>
                          </nav>
                        <div class="table-responsive p-0">
                            <table id="tbl-montos-liquidados" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Inversión</th>
                                        <th>Ganancia</th>
                                        <th>Metodo de pago</th>
                                        <th>Fecha de liquidación</th>
                                                                              
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br><br><br>
                        <nav aria-label="breadcrumb">
                            <h2>Productos Liquidados</h2>
                          </nav>
                        <div class="table-responsive p-0">
                            <table id="tbl-liquidaciones" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Nombre del producto</th>
                                        <th>Precio de compra</th>
                                        <th>Precio de venta</th>
                                        <th>Comprador</th>
                                        <th>Fecha de compra</th>
                                        <th>Metodo de pago</th>                                      
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>




    @include('layouts.footers.auth.footer')
@endsection
@push('js')
    <script>
        var urlTableLiquidaciones = "{{ route('ver-liquidaciones') }}";
        var urlTableMontosLiquidados = "{{ route('ver-montos-liquidados') }}";

        $('#tbl-liquidaciones').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urlTableLiquidaciones,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token al encabezado
                },
                complete: function() {
                    $("#overlay").fadeOut(300);
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'  
                    },
                    {
                        data: 'nombre_producto',
                        name: 'nombre_producto'
                    },
                    {
                        data: 'precio_compra',
                        name: 'precio_compra'
                    },
                    {
                        data: 'precio_venta',
                        name: 'precio_venta'
                    },
                    {
                        data: 'comprador',
                        name: 'comprador'
                    },
                    {
                        data: 'fecha_compra',
                        name: 'fecha_compra'
                    },
                    {
                        data: 'metodo_compra',
                        name: 'metodo_compra'
                    }
                    ],
                    columnDefs: [{
                        className: 'dt-center',
                        targets: '_all'
                    }, ],
                    language: idiomaDatatables,
                    order: [[ 0, "desc" ]],
                    responsive: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
            });
        
            $('#tbl-montos-liquidados').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: {
                    url: urlTableMontosLiquidados,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token al encabezado
                    },
                    complete: function() {
                        $("#overlay").fadeOut(300);
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'inversion',
                        name: 'inversion'
                    },
                    {
                        data: 'ganancia',
                        name: 'ganancia'
                    },
                    {
                        data: 'metodo_pago',
                        name: 'metodo_pago'
                    },
                    {
                        data: 'fecha_liquidacion',
                        name: 'fecha_liquidacion'
                    }
                    ],
                    columnDefs: [{
                        className: 'dt-center',
                        targets: '_all'
                    }, ],
                    language: idiomaDatatables,
                    order: [[ 0, "desc" ]],
                    responsive: true,
                    dom: 'Bfrtip',
                    
            });

       
    </script>
@endpush
