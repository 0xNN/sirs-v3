<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
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
    protected $table = 'patient';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'DateOfBirth' => 'datetime:Y-m-d',
    ];

    public function patientProblem()
    {
        return $this->hasMany(PatientProblem::class, 'MedicalNo', 'MedicalNo');
    }

    public function registration()
    {
        return $this->hasMany(Registration::class, 'MedicalNo', 'MedicalNo');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'EntityRecordID', 'MedicalNo');
    }
}
