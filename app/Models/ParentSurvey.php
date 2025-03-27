<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'health_concern',
        'health_rating',
        'concerns',
        'has_consultant',
        'consultants',
        'regular_hospital_visit',
        'medical_departments',
        'other_department',
        'menstruation_tracking',
        'last_period_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'health_concern' => 'boolean',
        'concerns' => 'array',
        'has_consultant' => 'boolean',
        'consultants' => 'array',
        'regular_hospital_visit' => 'boolean',
        'medical_departments' => 'array',
        'menstruation_tracking' => 'boolean',
        'last_period_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
