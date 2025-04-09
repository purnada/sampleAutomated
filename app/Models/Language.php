<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'default', 'status', 'code'];

    public function settingDetail()
    {
        return $this->hasOne(SettingDetail::class);
    }

    public function provinceDetail()
    {
        return $this->hasOne(ProvinceDetail::class);
    }

    public function districtDetail()
    {
        return $this->hasOne(DistrictDetail::class);
    }

    public function municipalityDetail()
    {
        return $this->hasOne(MunicipalityDetail::class);
    }

    public function leaveDetail()
    {
        return $this->hasOne(LeaveDetail::class);
    }

    public static function getStatus()
    {
        $data[] = ['title' => 'Active', 'value' => '1'];
        $data[] = ['title' => 'InActive', 'value' => '0'];
    }

    public function getFlagAttribute()
    {
        $flag = null;
        if ($this->code) {

            if (is_file(public_path().'/flags/'.strtolower($this->code).'.png')) {
                $flag = asset('flags/'.strtolower($this->code).'.png');
            }
        }

        return $flag;
    }
}
