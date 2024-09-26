<?php

// app/Models/Patient.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone']; // Campos que se pueden llenar en masivo

    // Relación con el modelo de Appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
