<style>
    .text-title{
        font-size: 2.0rem;
    }
</style>
   
@foreach ($totales as $tl)
    

   <div class="col-xl-3 col-sm-4 mb-xl-0 mb-2 ml-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 font-weight-bold text-title">Paquetes por enviar:</p>
                            <h5 class="font-weight-bolder">
                               {{ $tl->paquetes_registrados }}

                            </h5>
                            <p class="mb-0">
                                {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                            <i class="ni ni-box-2 text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-3 col-sm-4 mb-xl-0 mb-2 ml-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 font-weight-bold">Paquetes Enviados:</p>
                            <h5 class="font-weight-bolder">
                               {{ $tl->paquetes_enviados }}

                            </h5>
                            <p class="mb-0">
                                {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                            <i class="ni ni-send text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-3 col-sm-4 mb-xl-0 mb-4 ml-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 font-weight-bold">Próximo despacho:</p>
                            <h5 class="font-weight-bolder">
                                {{ $tl->fecha_envio }}

                            </h5>
                            <p class="mb-0">
                                {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                            <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endforeach
