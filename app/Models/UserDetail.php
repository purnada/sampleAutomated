<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sector',
        'job',
        'start_time',
        'end_time',
        'contact_number',
        'weekend',
        'max_booking',
    ];

    protected function weekend(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value) ?? [],
            set: fn ($value) => json_encode($value) ?? [],
        );
    }

    protected function sector(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value) ?? [],
            set: fn ($value) => json_encode($value) ?? [],
        );
    }

    public function getSectorsAttribute()
    {
        $sector = null;
        if ($this->sector) {

            $sectors = $this->sector;
            if (is_array($sectors)) {
                $last_key = array_key_last($sectors);
                foreach ($sectors as $key => $sector) {
                    if ($key == $last_key) {
                        $sector .= $sector;
                    } else {
                        $sector .= $sector.', ';
                    }
                }
            }

        }

        return $sector;
    }
}
