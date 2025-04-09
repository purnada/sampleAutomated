<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'booked_by',
        'appointment_date',
        'name',
        'age',
        'gender',
        'address',
        'contact_number',
        'payment_mode',
        'status',
        'visited_type',
        'sector',
        'province_id',
        'district_id',
        'municipality_id',
        'ward_no',
        'house_no',
        'nepali_date',
        'children',
        'remarks',
        'cancel_by',
        'reschedule_type',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function diseases(): HasMany
    {
        return $this->hasMany(Disease::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function child(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'children');
    }

    public function getStatusSpanAttribute()
    {
        switch ($this->status) {
            case '1':
                return '<span class="badge border border-secondary text-secondary">Arrived</span>';
            case '2':
                return '<span class="badge border border-danger text-danger">Cancelled</span>';
            case '3':
                return '<span class="badge border border-success text-success">Fully Completed</span>';
            default:
                return '<span class="badge border border-warning text-warning">Pending</span>';
        }
    }

    public function getStatusTitleAttribute()
    {
        switch ($this->status) {
            case '1':
                return 'Arrived';
            case '2':
                return 'Cancelled';
            case '3':
                return 'Fully Completed';
            default:
                return 'Pending';
        }
    }

    public static function getStatus()
    {
        $data[] = ['value' => '0', 'title' => 'Pending'];
        $data[] = ['value' => '1', 'title' => 'Arrived'];
        $data[] = ['value' => '2', 'title' => 'Cancelled'];
        $data[] = ['value' => '3', 'title' => 'Completed'];

        return $data;
    }

    public function getDiseaseTitlesAttribute()
    {
        $return_data = null;
        if ($this->diseases) {
            $last_key = count($this->diseases);
            foreach ($this->diseases as $key => $disease) {
                if ($key + 1 == $last_key) {
                    $return_data .= $disease->name;
                } else {
                    $return_data .= $disease->name.', ';
                }
            }
        }

        return $return_data;
    }
}
