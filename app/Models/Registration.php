<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
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
    protected $table = 'registration';

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
        'RegistrationDateTime' => 'date:Y-m-d',
    ];

    public function serviceRoom()
    {
        return $this->belongsTo(ServiceRoom::class, 'RoomID', 'RoomID');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'MedicalNo', 'MedicalNo');
    }

    public function departmentServiceUnit()
    {
        return $this->belongsTo(DepartmentServiceUnit::class, 'ServiceUnitID', 'ServiceUnitID');
    }

    public function classCode()
    {
        return $this->belongsTo(ClassM::class, 'ClassCode', 'ClassCode');
    }

    public function patientProblem()
    {
        return $this->hasMany(PatientProblem::class, 'MedicalNo', 'MedicalNo');
    }

    public function paramedic()
    {
        return $this->belongsTo(Paramedic::class, 'ParamedicID', 'ParamedicID');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class, 'BedID', 'BedID');
    }

    public function businessPartner()
    {
        return $this->belongsTo(BusinessPartner::class, 'BusinessPartnerID', 'BusinessPartnerID');
    }

    public function status()
    {
        return $this->belongsTo(StatusPriority::class, 'StatusID', 'StatusID');
    }

    public function dischargeMethod()
    {
        return $this->belongsTo(SysGeneralCode::class, 'GCDischargeMethod', 'GeneralCodeID');
    }

    public function dischargeCondition()
    {
        return $this->belongsTo(SysGeneralCode::class, 'GCDischargeCondition', 'GeneralCodeID');
    }
}
