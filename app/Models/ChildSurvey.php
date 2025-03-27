<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSurvey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'sibling_order',
        'name',
        'birth_date',
        'gender',
        'menstruation_tracking',
        'last_period_date',
        'school_attendance',
        'regular_hospital_visit',
        'medical_departments',
        'other_department',
        'diagnoses',
        'other_diagnosis',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'medical_departments' => 'array',
        'diagnoses' => 'array',
        'regular_hospital_visit' => 'boolean',
        'menstruation_tracking' => 'boolean',
        'birth_date' => 'date',
        'last_period_date' => 'date',
    ];
}
