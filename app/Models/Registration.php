<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'applicant_status',
        'program_type',
        'preferred_department_id',
        'department_id',
        'status',
        'rejection_reason',
        'acceptance_message',
        'start_date',
        'end_date',
        'participant_count',
        'verified_by',
        'verified_at',
        'placed_at',
        'supervisor_name',
        'supervisor_position',
        'supervisor_phone',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'verified_at' => 'datetime',
        'placed_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function preferredDepartment()
    {
        return $this->belongsTo(Department::class, 'preferred_department_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function participants()
    {
        return $this->hasMany(RegistrationParticipant::class, 'registration_id');
    }

    public function leader()
    {
        return $this->hasOne(RegistrationParticipant::class, 'registration_id')->where('is_leader', true);
    }

    public function institution()
    {
        return $this->hasOne(RegistrationInstitution::class, 'registration_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'registration_id');
    }

    public function replyLetter()
    {
        return $this->hasOne(ReplyLetter::class, 'registration_id');
    }
}
