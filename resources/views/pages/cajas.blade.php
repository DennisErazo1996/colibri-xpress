@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cajas'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Crear nueva caja</button>
                    </div>
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="example" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Nombre</th>
                                        <th>Fecha de envío</th>
                                        <th>Fecha estimada de recepción</th>
                                        <th>Fecha de registro</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($cajas as $bxs)
                                        <tr>
                                            <td>{{ $bxs->numero }}</td>
                                            <td>{{ $bxs->numero_caja }}</td>
                                            <td>{{ $bxs->fecha_envio }}</td>
                                            <td>{{ $bxs->fecha_arribo }}</td>
                                            <td>{{ $bxs->fecha_registro }}</td>
                                            <td>
                                                <a class="btn btn-1 m-0" href="{{ url('/caja/' . $bxs->id . '/paquetes') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Ver caja"
                                                    data-container="body" data-animation="true"><i
                                                        class="fi fi-sr-eye"></i></a>
                                                <a class="btn btn-1 m-0" href="#"
                                                    onclick="editarCaja({{ $bxs->id }}, '{{ $bxs->fecha_envio }}', '{{ $bxs->fecha_arribo }}', '{{ $bxs->numero_caja }}');"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Editar caja"
                                                    data-container="body" data-animation="true"><i
                                                        class="fi fi-sr-edit"></i></a>
                                                <a class="btn btn-1 btn-danger m-0"
                                                    onclick="eliminarCaja({{ $bxs->id }});" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Eliminar caja" data-container="body"
                                                    data-animation="true"><i class="fi fi-sr-trash"></i></a>
                                                @if (Auth::user()->role == 'super-admin')
                                                    <a class="btn btn-1 btn-success m-0"
                                                        href="{{ url("/caja/$bxs->id/pedidos") }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Agregar pedido" data-container="body"
                                                        data-animation="true"><i class="fi fi-ss-order-history"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

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

        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default ">Agregar nueva caja</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left" method="POST" action="{{ route('crear-caja') }}">
                                    @csrf
                                    <label>Fecha de envío</label>
                                    <div class="input-group mb-3">
                                        <input type="date" name="fechaEnvio" class="form-control"
                                            placeholder="Selecciona la fecha" aria-label="Date" required>
                                    </div>
                                    <label>Fecha estimada de llegada</label>
                                    <div class="input-group mb-3">
                                        <input type="date" name="fechaArribo" class="form-control"
                                            placeholder="Ingresa la fecha de llegada" aria-label="Date" required>
                                    </div>
                                    <div class="text-center d-flex flex-row">
                                        <button id="btn-crear-caja" type="submit"
                                            class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Crear</button>
                                        <button id="btn-cancelar-caja" type="button"
                                            class="btn btn-round btn-lg w-100 mt-4 mb-0">Cancelar</button>
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

    <div class="col-md-4">

        <div class="modal fade" id="modal-form-edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default">Actualizar información de caja</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left">
                                    <input type="text" class="form-control" id="inpIdCaja" style="display: none">
                                    <div class="row">
                                      
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Nombre de la caja<span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="nombre-caja" class="form-control"
                                                    id="inpNombreCaja" placeholder="Ingresa el nombre de la caja"
                                                    aria-label="nombre de la caja" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Fecha de envío<span
                                                        style="color: red">*</span></label>
                                                <input type="date" name="fechaEnvio" class="form-control"
                                                    id="inpFechaEnvio" placeholder="Ingresa la fecha de envío"
                                                    aria-label="fecha de envío" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Fecha estimada de llegada<span
                                                        style="color: red">*</span></label>
                                                <input type="date" name="fechaArribo" class="form-control"
                                                    id="inpFechaArribo" placeholder="Ingresa la fecha de llegada"
                                                    aria-label="fecha de llegada" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center" id="mensaje"
                                            style="color: red; font-size:12px"></div>
                                    </div>
                                    <div class="text-center d-flex flex-row justify-content-end">
                                        <button id="btn-actualizar-caja" type="submit"
                                            class="btn  bg-gradient-secondary btn-3 mt-4 mb-0">Actualizar</button>
                                        &nbsp;&nbsp;
                                        <button id="btn-cancelar-caja-edit" type="button"
                                            class="btn btn-3 mt-4 mb-0 ml-50">Cancelar</button>
                                    </div>
                                </form>
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
        $('#example').DataTable({
            columnDefs: [{
                className: 'dt-center',
                targets: '_all'
            }, ],
            language: idiomaDatatables,

        });

        $('#btn-cancelar-caja').on('click', function() {
            $('#modal-form').modal('toggle')
        });

        function eliminarCaja(idCaja) {


            var urlRest = "{{ route('eliminar-caja', ['idCaja' => ':idCaja']) }}"
                .replace(':idCaja', idCaja);

            $.confirm({
                type: 'red',
                animation: 'scale',
                title: 'Eliminar Caja',
                content: 'Seguro que quiere eliminar esta caja?',
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
                                    //"idPedido": idPedido,
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
        };

        $('#btn-cancelar-caja-edit').on('click', function() {
            $('#inpIdCaja').val('');
            $('#inpFechaEnvio').val('');
            $('#inpFechaArribo').val('');
            $('#modal-form-edit').modal('toggle')

        });

        $('#btn-actualizar-caja').click(function(e) {
            e.preventDefault();

            var urlRest = "{{ route('editar-caja') }}";
            var vrIdCaja = $('#inpIdCaja').val();
            var vrFechaEnvio = $('#inpFechaEnvio').val();
            var vrFechaArribo = $('#inpFechaArribo').val();
            //var vrChckPago = $('#chkPago').val();

            if (vrIdCaja != '' && vrFechaEnvio != '' && vrFechaArribo != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-actualizar-caja').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idCaja": vrIdCaja,
                        "fechaEnvio": vrFechaEnvio,
                        "fechaArribo": vrFechaArribo,
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
                        
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    $('#modal-form-edit').modal('toggle');
                    $('#inpIdCaja').val('');
                    $('#inpFechaEnvio').val('');
                    $('#inpFechaArribo').val('');
                    $('#btn-actualizar-caja').prop('disabled', false);

                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos')
            }
        });

        function editarCaja(idCaja, fechaEnvio, fechaArribo, nombreCaja) {
            
            fechaEnvio = fechaEnvio.toString();
            fechaArribo = fechaArribo.toString();

            
            if (fechaEnvio.includes('/') && fechaArribo.includes('/')) {
               
                var partesFechaEnvio = fechaEnvio.split('/');
                var fechaFormateadaEnvio = partesFechaEnvio[2] + '-' + partesFechaEnvio[1] + '-' + partesFechaEnvio[0];

                var partesFechaArribo = fechaArribo.split('/');
                var fechaFormateadaArribo = partesFechaArribo[2] + '-' + partesFechaArribo[1] + '-' + partesFechaArribo[0];

                
                $('#inpIdCaja').val(idCaja);
                $('#inpFechaEnvio').val(fechaFormateadaEnvio);
                $('#inpFechaArribo').val(fechaFormateadaArribo);
                $('#inpNombreCaja').val(nombreCaja);

                
                $('#modal-form-edit').modal('toggle');
            } else {
                console.error("Las fechas no tienen el formato correcto: DD/MM/YYYY");
            }
        }
    </script>
@endpush
