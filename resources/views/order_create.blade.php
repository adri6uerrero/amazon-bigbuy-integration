@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Crear Pedido Manual</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <h5 class="mb-3">Datos del Cliente</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="customer_address" class="form-control" value="{{ old('customer_address') }}" required>
                            </div>
                        </div>
                        <h5 class="mb-3">Datos del Pedido</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Amazon Order ID</label>
                                <input type="text" name="amazon_order_id" class="form-control" value="{{ old('amazon_order_id') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="pendiente" {{ old('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="enviado" {{ old('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                    <option value="error" {{ old('status') == 'error' ? 'selected' : '' }}>Error</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tracking (opcional)</label>
                                <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number') }}">
                            </div>
                        </div>
                        <h5 class="mb-3">Items</h5>
                        <div id="items-list">
                            <div class="row mb-2 item-row">
                                <div class="col-md-4">
                                    <input type="text" name="items[0][sku]" class="form-control" placeholder="SKU" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Cantidad" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" name="items[0][price]" class="form-control" placeholder="Precio" min="0" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none"><i class="bi bi-x"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-success" id="add-item"><i class="bi bi-plus"></i> Agregar Item</button>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let itemIndex = 1;
    document.getElementById('add-item').addEventListener('click', function() {
        let html = `<div class=\"row mb-2 item-row\">
            <div class=\"col-md-4\">
                <input type=\"text\" name=\"items[${itemIndex}][sku]\" class=\"form-control\" placeholder=\"SKU\" required>
            </div>
            <div class=\"col-md-3\">
                <input type=\"number\" name=\"items[${itemIndex}][quantity]\" class=\"form-control\" placeholder=\"Cantidad\" min=\"1\" required>
            </div>
            <div class=\"col-md-3\">
                <input type=\"number\" step=\"0.01\" name=\"items[${itemIndex}][price]\" class=\"form-control\" placeholder=\"Precio\" min=\"0\" required>
            </div>
            <div class=\"col-md-2 d-flex align-items-center\">
                <button type=\"button\" class=\"btn btn-danger btn-sm remove-item\"><i class=\"bi bi-x\"></i></button>
            </div>
        </div>`;
        let div = document.createElement('div');
        div.innerHTML = html;
        document.getElementById('items-list').appendChild(div.firstChild);
        itemIndex++;
        updateRemoveButtons();
    });
    function updateRemoveButtons() {
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.style.display = '';
            btn.onclick = function() {
                btn.closest('.item-row').remove();
            }
        });
    }
</script>
@endsection
