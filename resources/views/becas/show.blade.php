@extends('layouts.app')

@section('title', $beca->titulo)

@section('content')
<main class="main-content">
    <header class="header">
        <a href="{{ route('becas.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1><i class="fas fa-award"></i> {{ $beca->titulo }}</h1>
        <div class="user-info">
            <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
            <span>
                {{ trim(
                    (Auth::user()->nombre ?? '') . ' ' .
                    (Auth::user()->apellidoPaterno ?? '') . ' ' .
                    (Auth::user()->apellidoMaterno ?? '')
                ) }}
            </span>
        </div>
    </header>

    <div class="beca-detail-container">
        <div class="beca-detail-card">
            <div class="detail-header">
                <h2>{{ $beca->titulo }}</h2>
                <span class="institucion">{{ $beca->institucion }}</span>
                
                <div class="meta-info">
                    <span><i class="fas fa-calendar-day"></i> Publicado: {{ \Carbon\Carbon::parse($beca->fechaPublicacion)->format('d/m/Y') }}</span>
                    <span><i class="fas fa-user-tie"></i> Contacto: {{ $beca->autorID }}</span>
                </div>
            </div>
            
            <div class="detail-body">
                <div class="detail-section">
                    <h3><i class="fas fa-info-circle"></i> Descripción</h3>
                    <p>{{ $beca->descripcion }}</p>
                </div>
                
               <div class="detail-section">
    <h3><i class="fas fa-money-bill-wave"></i> Beneficio Económico</h3>
    <div class="benefit-details">
        @if(isset($beca->monto) && is_numeric($beca->monto))
            <p><strong>Monto mensual:</strong> ${{ number_format($beca->monto, 2) }} MXN</p>
            <p><strong>Forma de pago:</strong> {{ $beca->forma_pago ?? 'No especificado' }}</p>
            <p><strong>Duración:</strong> {{ $beca->duracion ?? 'No especificado' }}</p>
        @else
            <p class="text-muted">No se especificó monto económico para esta beca</p>
        @endif
    </div>
</div>
                
                <div class="detail-section">
                    <h3><i class="fas fa-calendar-alt"></i> Periodo</h3>
                    <p>Del {{ \Carbon\Carbon::parse($beca->fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($beca->fechaFin)->format('d/m/Y') }}</p>
                </div>
                
                {{-- Sección de Requisitos --}}
<div class="detail-section">
    <h3><i class="fas fa-clipboard-check"></i> Requisitos</h3>
    <ul>
        @if(is_array($beca->requisitos) && !empty($beca->requisitos))
            @foreach($beca->requisitos as $requisito)
                @if(is_string($requisito))
                    <li>{{ $requisito }}</li>
                @elseif(is_array($requisito))
                    <li>{{ implode(', ', $requisito) }}</li>
                @endif
            @endforeach
        @else
            <li>No se especificaron requisitos</li>
        @endif
        
        @if(isset($beca->promedioMinimo) && !is_null($beca->promedioMinimo))
    <li>Promedio mínimo: {{ $beca->promedioMinimo }}</li>
@else
    <li>Promedio mínimo: N/A</li>
@endif
        
        @if($beca->sinReprobadas)
            <li>No tener materias reprobadas</li>
        @endif
        
        @if($beca->condicionEspecial)
            <li>{{ $beca->condicionEspecial }}</li>
        @endif
    </ul>
</div>

{{-- Sección de Documentos --}}
<div class="detail-section">
    <h3><i class="fas fa-file-alt"></i> Documentos requeridos</h3>
    <ul>
        @if(is_array($beca->documentos) && !empty($beca->documentos))
            @foreach($beca->documentos as $documento)
                @if(is_string($documento))
                    <li>{{ $documento }}</li>
                @elseif(is_array($documento))
                    <li>{{ implode(', ', $documento) }}</li>
                @endif
            @endforeach
        @else
            <li>No se especificaron documentos requeridos</li>
        @endif
    </ul>
</div>
                
                
                @if($beca->url)
                <div class="detail-section">
                    <h3><i class="fas fa-external-link-alt"></i> Más información</h3>
                    <a href="{{ $beca->url }}" target="_blank" class="btn btn-primary">
                        Visitar sitio oficial
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>


@section('styles')
<style>
    .beca-detail-container {
        padding: 20px;
    }
    
    .beca-detail-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .detail-header {
        background: linear-gradient(135deg, #1e5799 0%,#207cca 100%);
        color: white;
        padding: 20px;
    }
    
    .detail-header h2 {
        margin: 0 0 5px 0;
    }
    
    .institucion {
        font-size: 1rem;
        opacity: 0.9;
        display: block;
        margin-bottom: 15px;
    }
    
    .meta-info {
        display: flex;
        gap: 15px;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .detail-body {
        padding: 20px;
    }
    
    .detail-section {
        margin-bottom: 20px;
    }
    
    .detail-section h3 {
        color: #1e5799;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .detail-section ul {
        padding-left: 20px;
    }
    
    .detail-section li {
        margin-bottom: 5px;
    }
    
    .btn {
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background 0.3s ease;
        display: inline-block;
    }
    
    .btn-primary {
        background: #1e5799;
        color: white;
    }
    
    .btn-primary:hover {
        background: #154273;
    }

    .back-button {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: black;
        font-size: 1.5rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .back-button:hover {
        color: #ddd;
    }
    
    .header {
        position: relative; /* Necesario para posicionar el botón */
        padding-left: 60px; /* Espacio para el botón */
    }
    
    /* Ajuste para el título cuando hay botón de regreso */
    .header h1 {
        margin-left: 0;
    }
</style>

