<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyConditionRecord extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'child_survey_id',
        'record_date',
        'mood_rating',
        'woke_up_well',
        'body_fatigue',
        'sleep_quality',
        'headache',
        'stomachache',
        'dizziness',
        'blood_pressure_high',
        'blood_pressure_low',
        'irritability',
        'depression',
        'is_menstruating',
        'blood_amount',
        'weather',
        'temperature',
        'pressure',
        'notes',
    ];
    
    protected $casts = [
        'record_date' => 'date',
        'woke_up_well' => 'boolean',
        'body_fatigue' => 'boolean',
        'sleep_quality' => 'boolean',
        'headache' => 'boolean',
        'stomachache' => 'boolean',
        'dizziness' => 'boolean',
        'irritability' => 'boolean',
        'depression' => 'boolean',
        'is_menstruating' => 'boolean',
        'temperature' => 'float',
        'pressure' => 'float',
    ];
    
    /**
     * この記録に関連する子ども
     */
    public function childSurvey()
    {
        return $this->belongsTo(ChildSurvey::class);
    }
}