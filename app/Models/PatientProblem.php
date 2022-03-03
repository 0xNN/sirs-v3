<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProblem extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql2';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patientproblem';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'MedicalNo', 'MedicalNo');
    }
    
    public function diagnosa()
    {
        return $this->belongsTo(Diagnosis::class, 'DiagnosisCode', 'DiagnosisCode');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'MedicalNo', 'MedicalNo');
    }
}
