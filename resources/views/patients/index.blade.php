<!-- resources/views/patients/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pacientes</h1>

    <!-- Formulario para agregar un nuevo paciente -->
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" name="first_name" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="last_name" class="form-control" placeholder="Apellido" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="phone" class="form-control" placeholder="Teléfono" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Paciente</button>
    </form>

    <hr>

    <!-- Listado de pacientes -->
    <h2>Lista de Pacientes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
                <tr>
                    <td>{{ $patient->first_name }}</td>
                    <td>{{ $patient->last_name }}</td>
                    <td>{{ $patient->email }}</td>
                    <td>{{ $patient->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
