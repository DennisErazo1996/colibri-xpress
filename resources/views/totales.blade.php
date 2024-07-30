@foreach ($totales as $tl)
    <div class="col-xl-2 col-sm-2 mb-xl-0 mb-4 ml-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total ganancia:</p>
                            <h5 class="font-weight-bolder">
                                {{ $tl->total_ganancia }}

                            </h5>
                            <p class="mb-0">
                                {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-2 col-sm-2 mb-xl-0 mb-4 ml-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total precio:</p>
                            <h5 class="font-weight-bolder">
                                {{ $tl->total_precio }}

                            </h5>
                            <p class="mb-0">
                                {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xl-2 col-sm-2 mb-xl-0 mb-4 ml-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total a pagar:</p>
                            <h5 class="font-weight-bolder">
                                {{ $tl->total_pagar }}

                            </h5>
                            <p class="mb-0">
                                {{-- <span class="text-success text-sm font-weight-bolder">+3%</span> --}}

                            </p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-danger text-center rounded-circle">
                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endforeach
