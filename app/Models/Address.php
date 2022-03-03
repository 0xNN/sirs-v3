<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
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
    protected $table = 'address';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'EntityRecordID', 'MedicalNo');
    }

    public function sysGeneralCodeProvinsi()
    {
        return $this->belongsTo(SysGeneralCode::class, 'GCProvince', 'GeneralCodeID');
    }

    public function sysKab()
    {
        
    }

    public function zipCode()
    {
        return $this->belongsTo(ZipCode::class, 'ZipCodeID', 'ZipCodeID');
    }
}
