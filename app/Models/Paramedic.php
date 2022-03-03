<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paramedic extends Model
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
    protected $table = 'paramedic';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function registration()
    {
        return $this->hasMany(Registration::class, 'ParamedicID', 'ParamedicID');
    }

    public function paramedicType()
    {
        return $this->belongsTo(SysGeneralCode::class, 'GCParamedicType', 'GeneralCodeID');
    }

    public function employmentStatus()
    {
        return $this->belongsTo(SysGeneralCode::class, 'GCEmploymentStatus', 'GeneralCodeID');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'SpecialtyCode', 'SpecialtyCode');
    }
}
