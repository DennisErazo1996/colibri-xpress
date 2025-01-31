@if (Auth::user()->role == 'super-admin')
<div
    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
    <i class="ni ni-circle-08 text-dark text-sm opacity-10"></i>
</div>

<select name="investor_id" id="investor_id" class="form-control investor-select" onchange="cambiarInversor(this.value)">
    <option value="" disabled
        @if (session('id_inversor') == null)
            selected
        @endif
    >Seleccione el inversionista</option>
    @foreach ($inversores as $inv)
        <option value="{{ $inv->id }}"
            @if (session('id_inversor') == $inv->id)
                selected
            @endif
        >{{ $inv->firstname.' '.$inv->lastname }}</option>
    @endforeach
</select>
@endif