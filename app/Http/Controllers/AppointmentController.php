<?php

// app/Http/Controllers/AppointmentController.php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // app/Http/Controllers/AppointmentController.php

   // app/Http/Controllers/AppointmentController.php

public function index()
{
    $appointments = Appointment::with('patient')->get()->map(function($appointment) {
        // Solo el nombre completo del paciente como título
        $patientNames = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
        return [
            'id' => $appointment->id,
            'title' => $patientNames,  // Solo el nombre
            'start' => $appointment->date . 'T' . $appointment->start_time,
            'end' => $appointment->date . 'T' . $appointment->end_time,
            'backgroundColor' => '#33FF57', // Color fijo
            'textColor' => '#fff',
        ];
    });

    $patients = Patient::all();
    return view('appointments.index', compact('appointments', 'patients'));
}



    // Guardar un nuevo turno para el paciente
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Crear un nuevo turno (permitir superposición)
        Appointment::create([
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('success', 'Turno agregado correctamente.');
    }

    // Actualizar turno al mover o cambiar duración
    public function update($id, Request $request)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->start_time = date('H:i:s', strtotime($request->start_time));
        $appointment->end_time = date('H:i:s', strtotime($request->end_time));
        $appointment->save();

        return response()->json(['success' => 'Turno actualizado correctamente.']);
    }

    // Eliminar un turno
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['success' => 'Turno eliminado correctamente.']);
    }

    // Obtener pacientes disponibles que no tengan un turno en la misma hora
    public function getAvailablePatients(Request $request)
    {
        $date = $request->input('date');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        // Obtener IDs de pacientes ocupados en ese horario
        $busyPatientIds = Appointment::where('date', $date)
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->pluck('patient_id');

        // Obtener pacientes disponibles
        $availablePatients = Patient::whereNotIn('id', $busyPatientIds)->get();

        return response()->json($availablePatients);
    }
}
