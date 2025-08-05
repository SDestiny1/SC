<div class="student-profile-modal">
    <h2>{{ $student->nombre }} {{ $student->apellidoPaterno }} {{ $student->apellidoMaterno }}</h2>
    <p><strong>Matrícula:</strong> {{ $student->matricula ?? substr($student->_id, 0, 8) }}</p>
    <p><strong>Correo:</strong> {{ $student->_id }}</p>
    <p><strong>Carrera:</strong> {{ $student->grupo->carrera->nombreCarrera ?? 'Sin carrera' }}</p>
    <p><strong>Semestre:</strong> {{ $student->grupo->semestre ?? 'Sin semestre' }}°</p>
    <p><strong>Fecha de nacimiento:</strong> {{ $student->fechaNacimiento->format('d/m/Y') ?? 'N/A' }}</p>
    <p><strong>Estatus:</strong> {{ $student->activo ? 'Activo' : 'Inactivo' }}</p>
</div>
