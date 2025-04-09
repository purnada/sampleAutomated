<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_nep', 'municipality_type', 'district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public static function getTypes()
    {
        return ['Metropolitan', 'Municipality', 'Rural Municipality'];
    }
}
