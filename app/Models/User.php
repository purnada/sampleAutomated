<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Library\ImageTool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, HasRoles, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'name',
        'email',
        'password',
    ];

    /**
     * Get the branch that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(UserShift::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('user_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->performOnCollections('user_image')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('thumb-cropped')
            ->performOnCollections('user_image')
            ->crop('crop-center', 400, 400);
    }

    public function getImageThumbPathAttribute()
    {
        if ($this->hasMedia('user_image')) {
            return $this->getFirstMedia('user_image')->getUrl('thumb');
        }

        return ImageTool::mycrop('placeholder.png', 100, 100);
    }

    public function getImageThumbAttribute()
    {
        if ($this->hasMedia('user_image')) {
            return $this->getFirstMedia('user_image')->getUrl('thumb');
        }

        return null;
    }

    public static function getWeekends()
    {
        return ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];
    }
}
