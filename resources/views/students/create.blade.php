@extends('layouts.app')

@section('title', 'Add Student')

@section('content')
<div class="container mt-4">
    <h2><i class="fas fa-user-plus"></i> Add New Student</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('students.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" name="age" id="age"
                        class="form-control @error('age') is-invalid @enderror"
                        value="{{ old('age') }}" required>
                    @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Specialty --}}
                <div class="mb-3">
                    <label for="specialty" class="form-label">Specialty</label>
                    <select name="specialty" id="specialty"
                        class="form-select @error('specialty') is-invalid @enderror" required>
                        <option value="">-- Select Specialty --</option>
                        <option value="TICS">TICS</option>
                        <option value="Industrial">Industrial</option>
                        <option value="Contabilidad">Contabilidad</option>
                    </select>
                    @error('specialty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Course (depends on specialty) --}}
                <div class="mb-3">
                    <label for="course" class="form-label">Course</label>
                    <select name="course" id="course"
                        class="form-select @error('course') is-invalid @enderror" required>
                        <option value="">-- Select Course --</option>
                    </select>
                    @error('course') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for dependent dropdown --}}
<script>
    const courseOptions = {
        'TICS': ['5-D DSM', '5-D DAE', '5-D DTI'],
        'Industrial': ['5-D MPI', '5-D PMI'],
        'Contabilidad': ['5-D CNA', '5-D ACO']
    };

    const specialtySelect = document.getElementById('specialty');
    const courseSelect = document.getElementById('course');

    specialtySelect.addEventListener('change', function () {
        const selected = this.value;
        courseSelect.innerHTML = '<option value="">-- Select Course --</option>'; // reset

        if (courseOptions[selected]) {
            courseOptions[selected].forEach(function (course) {
                const option = document.createElement('option');
                option.value = course;
                option.text = course;
                courseSelect.appendChild(option);
            });
        }
    });
</script>
@endsection
