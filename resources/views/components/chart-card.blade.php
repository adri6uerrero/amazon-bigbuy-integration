@props(['title', 'icon', 'id', 'options' => false])

<div class="stats-card">
    <div class="card-header">
        <h5><i class="bi bi-{{ $icon }}"></i>{{ $title }}</h5>
        @if($options)
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-sliders me-1"></i>Opciones
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    {{ $options }}
                </ul>
            </div>
        @endif
    </div>
    <div class="chart-container">
        <canvas id="{{ $id }}"></canvas>
    </div>
    @if(isset($slot) && !empty(trim($slot)))
        <div class="custom-legend">
            {{ $slot }}
        </div>
    @endif
</div>
