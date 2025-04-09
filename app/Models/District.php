<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = ['province_id', 'title', 'name_nep'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
