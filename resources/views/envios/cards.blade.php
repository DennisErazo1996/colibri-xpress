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
</style>

@foreach ($envios as $envio)
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="col-md-3">
                        <img src="{{ asset('img/bx-icon.png') }}" alt="logo" class="w-80 mb-3">
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title">{{ $envio->lote }}</h5>
                        <p class="inf-txt mb-0">Fecha de Envío: {{ $envio->fecha_envio }}</p>
                        <p class="inf-txt">Fecha estimada de llegada: {{ $envio->fecha_arribo }}</p>
                        <div class="inf-peso">
                            <p class="inf-txt mb-0">Peso total: <strong>0 kg</strong></p>
                            <p class="inf-txt mb-0">Precio de envío: <strong>L 0.00</strong> </p>                    
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-default btn-sm mb-0"><i class="ni ni-app text-dark text-sm opacity-10"></i> &nbsp; ver paquetes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="pagination">
    {{ $envios->links('pagination::bootstrap-5') }}
</div>