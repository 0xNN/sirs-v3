<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
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
    protected $table = 'diagnosis';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function patientProblem()
    {
        return $this->hasMany(PatientProblem::class, 'DiagnosisCode', 'DiagnosisCode');
    }

    public function icdblock()
    {
        return $this->belongsTo(ICDBlock::class, 'ICDBlockID', 'ICDBlockID');
    }
}
