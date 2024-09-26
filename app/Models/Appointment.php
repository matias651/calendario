<?php

// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_id', 'date', 'start_time', 'end_time'];

    // Relación con el modelo de pacientes
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
