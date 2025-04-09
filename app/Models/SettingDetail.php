<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['setting_id', 'language_id', 'name', 'telephone', 'address', 'meta_title', 'meta_keyword', 'meta_description'];

    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }
}
