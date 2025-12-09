{{-- filepath: c:\Users\judar\OneDrive\Documentos\Proyecto Sena\JovenLaravel\resources\views\web\mis-pedidos.blade.php --}}
@extends('web.app')

@section('contenido')
<div class="mis-pedidos-wrapper">
    <div class="container py-5">
        <!-- Header -->
        <div class="pedidos-header mb-4">
            <a href="{{ route('perfil') }}" class="btn-volver">
                <i class="bi bi-arrow-left"></i> Volver a Mi Perfil
            </a>
            <h1 class="pedidos-titulo">Mis Pedidos</h1>
            <p class="pedidos-subtitulo">
                {{ $totalPedidos }} {{ $totalPedidos == 1 ? 'pedido realizado' : 'pedidos realizados' }}
            </p>
        </div>

        @if($totalPedidos == 0)
        <!-- Empty State -->
        <div class="pedidos-empty">
            <div class="empty-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <h2>No tienes pedidos aún</h2>
            <p>Explora nuestros productos y realiza tu primer pedido</p>
            <a href="{{ route('web.tienda') }}" class="btn-explorar">
                <i class="bi bi-shop"></i> Ver Productos
            </a>
        </div>
        @else
        <!-- Lista de Pedidos -->
        <div class="pedidos-lista">
            @foreach($pedidos as $pedido)
            <div class="pedido-card">
                <!-- Header del Pedido -->
                <div class="pedido-card-header">
                    <div class="pedido-info">
                        <span class="pedido-numero">Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="pedido-fecha">
                            <i class="bi bi-calendar3"></i>
                            {{ $pedido->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                    <span class="pedido-estado estado-{{ $pedido->estado }}">
                        @switch($pedido->estado)
                            @case('pendiente')
                                <i class="bi bi-clock-history"></i> Pendiente
                                @break
                            @case('enviado')
                                <i class="bi bi-truck"></i> Enviado
                                @break
                            @case('completado')
                                <i class="bi bi-check-circle"></i> Completado
                                @break
                            @case('cancelado')
                                <i class="bi bi-x-circle"></i> Cancelado
                                @break
                            @case('anulado')
                                <i class="bi bi-slash-circle"></i> Anulado
                                @break
                            @default
                                {{ ucfirst($pedido->estado) }}
                        @endswitch
                    </span>
                </div>

                <!-- Productos del Pedido -->
                <div class="pedido-productos">
                    @foreach($pedido->detalles as $detalle)
                    <div class="pedido-producto">
                        <div class="producto-img">
                            @if($detalle->producto && $detalle->producto->imagen)
                                <img src="{{ asset('uploads/productos/' . $detalle->producto->imagen) }}" 
                                     alt="{{ $detalle->producto->nombre ?? 'Producto' }}">
                            @else
                                <div class="img-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>
                        <div class="producto-info">
                            <span class="producto-nombre">{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</span>
                            <span class="producto-cantidad">Cantidad: {{ $detalle->cantidad }}</span>
                        </div>
                        <div class="producto-precio">
                            ${{ number_format($detalle->precio * $detalle->cantidad, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Footer del Pedido -->
                <div class="pedido-card-footer">
                    <div class="pedido-total">
                        <span class="total-label">Total del pedido:</span>
                        <span class="total-valor">${{ number_format($pedido->total, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($pedido->estado === 'pendiente')
                    <button type="button" class="btn-cancelar-pedido" onclick="showCancelModal({{ $pedido->id }})">
                        <i class="fas fa-times"></i> Cancelar Pedido
                    </button>
                    <form id="cancel-form-{{ $pedido->id }}" action="{{ route('pedidos.cambiar.estado', $pedido->id) }}" method="POST" style="display:none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="anulado">
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginación -->
        @if($pedidos->hasPages())
        <div class="pedidos-paginacion mt-4">
            {{ $pedidos->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<div id="cancelModal" class="custom-modal" style="display:none;">
    <div class="custom-modal-content">
        <h4>¿Cancelar pedido?</h4>
        <p>¿Estás seguro que deseas cancelar este pedido? Esta acción no se puede deshacer.</p>
        <div class="custom-modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">No, volver</button>
            <button type="button" class="btn btn-danger" id="confirmCancelBtn">Sí, cancelar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pedidoIdToCancel = null;
function showCancelModal(pedidoId) {
    pedidoIdToCancel = pedidoId;
    document.getElementById('cancelModal').style.display = 'flex';
}
function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
    pedidoIdToCancel = null;
}
document.getElementById('confirmCancelBtn').onclick = function() {
    if (pedidoIdToCancel) {
        document.getElementById('cancel-form-' + pedidoIdToCancel).submit();
    }
};
</script>
@endpush