<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'full_name',
        'nis_nim',
        'institution_level',
        'major',
        'semester_or_grade',
        'phone',
        'email',
        'is_leader',
    ];

    protected $casts = [
        'is_leader' => 'boolean',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
