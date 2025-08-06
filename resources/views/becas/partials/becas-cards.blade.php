@foreach($becas as $beca)
<div class="beca-card">
    <div class="beca-header">
        <h2>{{ $beca->titulo }}</h2>
        <span class="institucion">{{ $beca->institucion }}</span>
    </div>
    
    <div class="beca-body">
        <p class="descripcion">{{ Str::limit($beca->descripcion, 200) }}</p>
        
        <div class="beca-details">
            <div class="detail-item">
                <i class="fas fa-tag"></i>
                <span>Tipo: {{ ucfirst($beca->tipo) }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-calendar-day"></i>
                <span>Del {{ \Carbon\Carbon::parse($beca->fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($beca->fechaFin)->format('d/m/Y') }}</span>
            </div>
            @if($beca->tipo === 'beca' && isset($beca->monto))
            <div class="detail-item">
                <i class="fas fa-money-bill-wave"></i>
                <span>${{ number_format($beca->monto, 2) }} MXN</span>
            </div>
            @endif
            <div class="detail-item">
                <i class="fas fa-user"></i>
                <span>Autor: {{ $beca->autorID }}</span>
            </div>
        </div>
        
        <div class="beca-actions">
            <a href="{{ route('becas.show', $beca->_id) }}" class="btn btn-primary">
                Ver detalles
            </a>
            <a href="{{ $beca->url }}" target="_blank" class="btn btn-secondary">
                <i class="fas fa-external-link-alt"></i> Sitio oficial
            </a>
        </div>
    </div>
</div>
@endforeach 