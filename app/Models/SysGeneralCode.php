<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysGeneralCode extends Model
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
    protected $table = 'sysgeneralcode';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function address()
    {
        return $this->hasMany(Address::class, 'GCProvince', 'GeneralCodeID');
    }

    public function regDischargeMethod()
    {
        return $this->hasMany(Registration::class, 'GCDischargeMethod', 'GeneralCodeID');
    }

    public function regDischargeCondition()
    {
        return $this->hasMany(Registration::class, 'GCDischargeCondition', 'GeneralCodeID');
    }

    public function classRL()
    {
        return $this->hasMany(ClassM::class, 'GCClassRL', 'GeneralCodeID');
    }

    public function paramedicType()
    {
        return $this->hasMany(Paramedic::class, 'GCParamedicType', 'GeneralCodeID');
    }

    public function employmentStatus()
    {
        return $this->hasMany(Paramedic::class, 'GCEmploymentStatus', 'GeneralCodeID');
    }
}
