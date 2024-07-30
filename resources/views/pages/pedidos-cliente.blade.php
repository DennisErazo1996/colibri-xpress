@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
    <style>
        .column-color {
            background-color: rgb(241, 241, 241) !important;
        }
    </style>
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pedidos del Cliente'])

    <div class="container-fluid py-4">
        <div id="refresh-data" class="row">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4 ml-4">
                @foreach ($dataCliente as $datausr)
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Cliente:</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $datausr->firstname . ' ' }}{{ $datausr->lastname }}

                                        </h5>
                                        <p class="mb-0">
                                            {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                                        <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('totales')


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
                            <table id="tbl-pedidos-cliente" class="table align-items-center table-striped"
                                style="width:100%">
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
    <div class="col-md-4">

        <div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default">Actualizar el pedido</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left">
                                    <input type="text" class="form-control" id="inpIdPedido" style="display: none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Nombre producto<span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="identity" class="form-control"
                                                    id="inpNombreProducto" placeholder="Ingresa el nombre del producto"
                                                    aria-label="Producto" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-4">
                                            <div class="form-group">
                                                <label for="FormControlSelect1">Cantidad<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="cantidad" class="form-control" max="8"
                                                    id="inpCantidad" placeholder="0" aria-label="Phone" required>

                                            </div>
                                        </div>
                                        <div class="col-md-2 col-4">
                                            <div class="form-group">
                                                <label for="FormControlSelect1">Precio<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="cantidad" class="form-control" max="8"
                                                    id="inpPrecio" placeholder="L 0.00" aria-label="Phone" required>

                                            </div>
                                        </div>
                                        <div class="col-md-2 col-4">
                                            <div class="form-group">
                                                <label for="FormControlSelect1">Ganancia<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="cantidad" class="form-control"
                                                    placeholder="L 0.00" id="inpGanancia" aria-label="Phone" required>

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="department">Enlace del producto<span
                                                        style="color: red">*</span></label>
                                                <textarea id="inpEnlaceProducto" type="email" name="email" class="form-control" placeholder="Ingresa el enlace"
                                                    rows="5" aria-label="Enlace" required></textarea>

                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center" id="mensaje"
                                            style="color: red; font-size:12px"></div>
                                    </div>
                                    <div class="text-center d-flex flex-row justify-content-end">
                                        <button id="btn-actualizar-pedido" type="submit"
                                            class="btn  bg-gradient-secondary btn-3 w-30 mt-4 mb-0">Actualizar</button>
                                        <button id="btn-cancelar-pedido" type="button"
                                            class="btn btn-3 w-30 mt-4 mb-0 ml-50">Cancelar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection
@push('js')
    <script>
        var urlTable = "{{ route('pedidos-cliente', ['id' => $idCaja, 'idCliente' => $idUsuario]) }}";
        var idCaja = "{{ $idCaja }}";
        var idUsuario = "{{ $idUsuario }}";


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
            $('#inpIdPedido').val('');
            $('#modal-form').modal('toggle')

        });

        $('#btn-actualizar-pedido').click(function(e) {
            e.preventDefault();

            var urlRest = "{{ route('editar-pedido') }}";
            var vrNombreProducto = $('#inpNombreProducto').val();
            var vrCantidad = $('#inpCantidad').val();
            var vrPrecio = $('#inpPrecio').val();
            var vrGanancia = $('#inpGanancia').val();
            var vrEnlaceProducto = $('#inpEnlaceProducto').val();
            var vrIdPedido = $('#inpIdPedido').val();
            //var vrIdCaja = "{{ $idCaja }}";

            //alert(vrIdPedido)
            //console.log(dataId);

            if (vrNombreProducto != '' && vrCantidad != '' && vrPrecio != '' &&
                vrGanancia != '' && vrEnlaceProducto != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-actualizar-pedido').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idPedido": vrIdPedido,
                        "idCaja": idCaja,
                        "idCliente": idUsuario,
                        "nombreProducto": vrNombreProducto,
                        "cantidad": vrCantidad,
                        "precio": vrPrecio,
                        "ganancia": vrGanancia,
                        "enlaceProducto": vrEnlaceProducto
                    },
                    success: function(response) {
                        //alert(response)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response
                        });

                        window.location.reload();
                        //$('#tbl-pedidos-cliente').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    $('#modal-form').modal('toggle');
                    $('#nombre-cliente').val('');
                    $('#inpNombreProducto').val('');
                    $('#inpCantidad').val('');
                    $('#inpPrecio').val('');
                    $('#inpGanancia').val('');
                    $('#inpEnlaceProducto').val('');
                    $('#inpIdPedido').val('');
                    $('#btn-actualizar-pedido').prop('disabled', false);

                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos')
            }
        });



        function eliminarPedidoCliente(idPedido) {
            urlRest = "{{ route('eliminar-pedido', ['idCaja' => $idCaja, 'idCliente' => $idUsuario]) }}";

            $.confirm({
                type: 'red',
                animation: 'scale',
                title: 'Eliminar Pedido',
                content: 'Seguro que quiere eliminar este pedido?',
                buttons: {
                    confirm: {
                        text: 'Confirmar',
                        btnClass: 'btn-red',
                        action: function() {

                            $(document).ajaxSend(function() {
                                $("#overlay").fadeIn(300);
                            });

                            $.ajax({
                                type: "POST",
                                url: urlRest,
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "idPedido": idPedido,
                                },
                                success: function(response) {
                                    //alert(response)
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: response
                                    });

                                    location.reload();
                                    //$('#tbl-pedidos').DataTable().ajax.reload();
                                },
                                error: function(request, status, error) {
                                    alert(request.responseText);
                                }
                            }).done(function() {
                                setTimeout(function() {
                                    $("#overlay").fadeOut(300);
                                }, 500);
                            });
                        }
                    },
                    cancelar: function() {
                        //$.alert('Canceled!');
                    },

                }
            });


            //alert(idPedido);
        }

        function editarPedido(idPedido, nombreProducto, urlProducto, cantidad, precio, ganancia) {

            var vrCantidad = parseFloat(cantidad.replace("L ", ""));
            var vrPrecio = parseFloat(precio.replace("L ", ""));
            var vrGanancia = parseFloat(ganancia.replace("L ", ""));


            $('#inpIdPedido').val(idPedido);
            $('#inpNombreProducto').val(nombreProducto);
            $('#inpCantidad').val(vrCantidad);
            $('#inpPrecio').val(vrPrecio);
            $('#inpGanancia').val(vrGanancia);
            $('#inpEnlaceProducto').val(urlProducto);
            $('#modal-form').modal('toggle');

        }
    </script>
@endpush
