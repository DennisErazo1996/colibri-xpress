<style>
    .inf-txt {
        font-size: 11px;
    }

    button {
        font-size: 12px;
        display: flex !important;
    }

    .inf-peso {
        background-color: rgb(222, 245, 222);
        padding: 10px;
        width: 11rem;
    }

    div.card {
        overflow: hidden;
    }

    div.pagination {
        display: flex !important;
        width: 100%;
        justify-content: center;
        align-items: center;
    }

    img#loader-img {
        width: 85px;
    }

    div.inf-estado{
        display: flex;
        justify-content: end;
        width: 100%;
        padding: 10px;
    }

    div.estado-container{
        color: #8de2a4;
        border-radius: 5px;
        padding: 7px;
    }

</style>

@if ($envios->isEmpty())
    <div class="col-12 text-center">
        <img src="{{ asset('img/no-envios.png') }}" alt="No envíos" class="img-fluid" style="max-width: 300px;">
        <p class="mt-3">No hay envíos disponibles.</p>
    </div>
@else
    @foreach ($envios as $envio)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card">
                <div class="inf-txt inf-estado">
                    <div class="p-2 estado-container"
                    @php
                        if ($envio->estado_envio == 'No Enviado') {
                            echo 'style="background-color: #f3acac; color: #910d0d;"';
                        } else if ($envio->estado_envio == 'Enviado') {
                            echo 'style="background-color: #e4e4e4; color: #404040;"';
                        } else if ($envio->estado_envio == 'Entregado') {
                            echo 'style="background-color: #8de2a4; color: #015025;"';
                        }
                    @endphp
                    >
                       {{ $envio->estado_envio }}
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-md-3">
                            <img src="{{ asset('img/bx-icon.png') }}" alt="logo" class="w-80 mb-3">
                        </div>
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $envio->lote }}</h5>
                            <p class="inf-txt mb-0">{{ $envio->firstname }}</p>
                            <p class="inf-txt mb-0">Fecha de Envío: {{ $envio->fecha_envio }}</p>
                            <p class="inf-txt">Fecha estimada de llegada: {{ $envio->fecha_arribo }}</p>
                            <div class="inf-peso">
                                <p class="inf-txt mb-0">Cantidad de paquetes: <strong>{{$envio->cantidad_paquetes}}</strong> </p>
                                <p class="inf-txt mb-0">Peso total: <strong>{{$envio->peso_envio}}</strong> </p>
                                <p class="inf-txt mb-0">Precio de envío: <strong>{{$envio->precio_envio}}</strong> </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="button" onclick="verPaquetesCliente({{$envio->id_caja}})" id="btn-ver-paquetes-cliente" class="btn btn-default btn-sm mb-0"><i class="ni ni-app text-dark text-sm opacity-10"></i> &nbsp; ver paquetes cliente</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="pagination">
        {{ $envios->onEachSide(1)->links('pagination::bootstrap-4') }} &nbsp;
        <p>
            {{$envios->count()}} registros de {{$envios->total()}}
        </p>
    </div>
@endif

<div class="col-md-12">

    <div class="modal fade" id="modal-listado-paquetes" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card card-plain">
                        <div class="card-header pb-0 text-left">
                            <h3 class="font-weight-bolder text-default">Listado de paquetes</h3>
                            {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                        </div>
                        <div class="card-body">
                
                            <br>
                            <div class="table-responsive p-0">
                                <table id="tbl-paquetes-cliente" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No.</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Número de seguimiento</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Descripción</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Fecha de registro</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Hora de registro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                </table>
                                <div class="text-center" id="loader" style="display: none">
                                    <img src="{{ asset('img/loader.gif') }}" id="loader-img">
                                </div>
                            </div>
                            <div class="text-center d-flex flex-row justify-content-end">

                                <button id="btn-cerrar-paquetes-cliente" type="button" onclick="cerrarModal()"
                                    class="btn btn-3 mt-4 mb-0 ml-50">Cerrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    function verPaquetesCliente(vrIdCaja) {

        $('#modal-listado-paquetes').modal('toggle');

        var urlRest = "{{ route('ver-paquetes-cliente') }}";

        
        $("#loader").fadeIn(300);
        
        $.ajax({
            type: "POST",
            url: urlRest,
            data: {
                "_token": "{{ csrf_token() }}",
                "idCaja": vrIdCaja,
            },
            success: function(response) {
                //console.log(response); 
                $('#tbl-paquetes-cliente tbody').empty();
                
                for (var i = 0; i < response.data.length; i++) {
                    $('#tbl-paquetes-cliente tbody').append(
                        '<tr class="text-center">' +
                            '<td>' + response.data[i].no + '</td>' +
                            '<td>' + response.data[i].numero_tracking + '</td>' +
                            '<td style="max-width: 230px !important;  white-space: normal !important; word-wrap: break-word !important;">' + response.data[i].descripcion + '</td>' +
                            '<td>' + response.data[i].fecha_registro + '</td>' +
                            '<td>' + response.data[i].hora_registro + '</td>' +
                        '</tr>'
                    );
                }
            },

            error: function(request, status, error) {
                alert(request.responseText);
            }
        }).always(function() {
            $("#loader").fadeOut(100);
        });
    }

    function cerrarModal() {
        $('#modal-listado-paquetes').modal('toggle');
        $('#tbl-paquetes-cliente tbody').empty();
    }


</script>