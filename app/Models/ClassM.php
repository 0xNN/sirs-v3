<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassM extends Model
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
    protected $table = 'class';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function registration()
    {
        return $this->hasMany(Registration::class, 'ClassCode', 'ClassCode');
    }

    public function classRL()
    {
        return $this->belongsTo(SysGeneralCode::class, 'GCClassRL', 'GeneralCodeID');
    }
}
