@extends('layouts.app')

@section('title', 'Add Teacher')

@section('content')
<div class="container mt-4">
    <h2><i class="fas fa-chalkboard-teacher"></i> Add New Teacher</h2>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('teachers.store') }}">
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

                {{-- Subject (dependent) --}}
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <select name="subject" id="subject"
                        class="form-select @error('subject') is-invalid @enderror" required>
                        <option value="">-- Select Subject --</option>
                    </select>
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS for dependent subject select --}}
<script>
    const subjectOptions = {
        'TICS': ['Programming', 'Databases', 'Networking'],
        'Industrial': ['Automation', 'Maintenance', 'Mechanics'],
        'Contabilidad': ['Cost Accounting', 'Finance', 'Auditing']
    };

    const specialtySelect = document.getElementById('specialty');
    const subjectSelect = document.getElementById('subject');

    specialtySelect.addEventListener('change', function () {
        const selected = this.value;
        subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';

        if (subjectOptions[selected]) {
            subjectOptions[selected].forEach(function (subject) {
                const option = document.createElement('option');
                option.value = subject;
                option.text = subject;
                subjectSelect.appendChild(option);
            });
        }
    });
</script>
