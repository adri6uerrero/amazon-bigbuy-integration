@props(['title', 'value', 'icon', 'color' => 'blue', 'subtext' => null])

@php
    $gradients = [
        'blue' => 'linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%)',
        'green' => 'linear-gradient(135deg, #42b883 0%, #3d6eea 100%)',
        'orange' => 'linear-gradient(135deg, #ff9e43 0%, #ff7a01 100%)',
        'red' => 'linear-gradient(135deg, #f05252 0%, #bd3737 100%)',
        'purple' => 'linear-gradient(135deg, #6e47ef 0%, #8e24aa 100%)',
    ];
    
    $gradient = $gradients[$color] ?? $gradients['blue'];
@endphp

<div class="stats-card">
    <div class="metric-card">
        <div class="metric-icon" style="background: {{ $gradient }}; color: white;">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
        <div class="metric-value" style="background: {{ $gradient }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            {{ $value }}
        </div>
        <p class="metric-label">{{ $title }}</p>
        @if($subtext)
            <div class="small text-muted">{{ $subtext }}</div>
        @endif
    </div>
</div>
