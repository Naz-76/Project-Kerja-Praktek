<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationInstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'institution_name',
        'institution_address',
        'contact_person',
        'contact_phone',
        'teacher_name',
        'teacher_email',
        'teacher_phone',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
