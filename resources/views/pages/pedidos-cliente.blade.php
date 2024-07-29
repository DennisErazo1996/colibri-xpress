@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
    <style>
        .column-color{
            background-color: rgb(241, 241, 241) !important;
        }
    </style>
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pedidos del Cliente'])
    
    <div class="container-fluid py-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 ml-4">
            @foreach ($dataCliente as $datausr)
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Cliente:</p>
                                <h5 class="font-weight-bolder">
                                    {{$datausr->firstname.' '}}{{$datausr->lastname}}
                                    
                                </h5>
                                <p class="mb-0">
                                    {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}
                                    
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                                <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Crear nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="tbl-pedidos-cliente" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Nombre del producto</th>
                                        <th>Enlace del producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Ganancia</th>
                                        <th>Subtotal</th>
                                        <th>Total</th>
                                        <th>Fecha de registro</th>
                                        <th>Opcion</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    @include('layouts.footers.auth.footer')
    </div>

    
    </div>
@endsection
@push('js')
    <script>

       var urlTable = "{{ route('pedidos-cliente', ['id' => $idCaja, 'idCliente' => $idUsuario]) }}";



        $('#tbl-pedidos-cliente').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urlTable,
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
                    data: 'nombre_producto',
                    name: 'nombre_producto'
                },
                {
                    data: 'url_producto',
                    name: 'url_producto'
                },
                {
                    data: 'cantidad',
                    name: 'cantidad'
                },
                {
                    data: 'precio',
                    name: 'precio'
                },
                {
                    data: 'ganancia',
                    name: 'ganancia'
                },
                {
                    data: 'sub_total',
                    name: 'sub_total'
                },
                {
                    data: 'total',
                    name: 'total',
                    className: 'column-color'
                },
                {
                    data: 'fecha_registro',
                    name: 'fecha_registro'
                },
                {
                    data: 'opcion',
                    name: 'Opciones',
                    orderable: true,
                    searchable: true
                }

            ],
            columnDefs: [{
                className: 'dt-center',
                targets: '_all'
            }, ],
            language: idiomaDatatables,

        });

        $('#btn-cancelar-pedido').on('click', function() {
            $('div#mensaje').html('')
            $('#inpNombreProducto').val('');
            $('#inpCantidad').val('');
            $('#inpPrecio').val('');
            $('#inpGanancia').val('');
            $('#inpEnlaceProducto').val('');
            $('#nombre-cliente').val('');
            $('#modal-form').modal('toggle')

        });

        

        function verPedidosCliente(idCliente) {
            alert(idCliente);
        }
    </script>
@endpush
