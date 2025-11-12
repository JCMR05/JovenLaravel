@extends('web.app')
@section('contenido')
<!-- Section-->
 <form action="{{route('carrito.agregar')}}" method="POST" class="d-flex">
    @csrf
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0"
                    src="{{asset('uploads/productos/'. $producto->imagen) }}" alt="{{$producto->nombre}}"/></div>
                <div class="col-md-6">
                    <div class="small mb-1">SKU: {{$producto->codigo}}</div>
                    <h1 class="display-5 fw-bolder">{{$producto->nombre}}</h1>
                    <div class="fs-5 mb-5">
                        <span>${{$producto->precio}}</span>
                    </div>
                    <p class="lead">{{$producto->descripcion}}</p>
                    @if (session('mensaje'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif
                    <div class="d-flex">
                        <input type="hidden" name="producto_id" value="{{$producto->id}}">
                        <div class="me-3 d-inline-flex align-items-center " style="max-width: 100px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary border-1 p-1" data-action="decrease"
                                    aria-label="Disminuir" style="box-shadow: none;">-</button>

                            <input id="inputQuantity" name="cantidad" type="number" min="1" value="1"
                                   class="form-control text-center mx-2 no-spin"
                                   style="width:56px; background:transparent; outline: none; box-shadow: none;"
                                   aria-label="Cantidad">

                            <button type="button" class="btn btn-sm btn-outline-secondary border-1 p-1" data-action="increase"
                                    aria-label="Aumentar" style="box-shadow: none;">+</button>
                        </div>
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Agregar al carrito
                        </button>
                        <a class="btn btn-outline-secondary ms-2" href="javascript:history.back()">Regresar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<style>
/* Ocultar spinners nativos en inputs type=number */
input[type=number].no-spin::-webkit-outer-spin-button,
input[type=number].no-spin::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number].no-spin {
  -moz-appearance: textfield;
}
</style>
@push('scripts')
<script>
document.addEventListener('click', function(e){
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const action = btn.getAttribute('data-action');
    const wrapper = btn.closest('.me-3') || btn.parentElement;
    const input = wrapper.querySelector('#inputQuantity');
    if (!input) return;
    let value = parseInt(input.value) || 1;
    if (action === 'increase') {
        value++;
    } else if (action === 'decrease') {
        value = Math.max(1, value - 1);
    }
    input.value = value;
});

document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('inputQuantity');
    if (!input) return;
    input.addEventListener('input', function(){
        if (this.value === '' || isNaN(this.value) || parseInt(this.value) < 1) {
            this.value = 1;
        } else {
            this.value = parseInt(this.value);
        }
    });
    // Evita que las flechas del teclado cambien el valor si no se desea
    input.addEventListener('keydown', function(e){
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection