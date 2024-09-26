<?php

// app/Http/Controllers/PatientController.php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Método para listar todos los pacientes
    public function index()
    {
        $patients = Patient::all(); // Obtener todos los pacientes
        return view('patients.index', compact('patients')); // Retornar la vista con la lista de pacientes
    }

    // Método para crear un nuevo paciente
    public function store(Request $request)
    {
        // Validar los datos recibidos del formulario
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|string|max:15',
        ]);

        // Crear el paciente en la base de datos
        Patient::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Redireccionar al listado de pacientes con un mensaje de éxito
        return redirect()->route('patients.index')->with('success', 'Paciente creado correctamente.');
    }
}
