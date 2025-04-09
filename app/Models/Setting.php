<?php

namespace App\Models;

use App\Library\ImageTool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['email', 'analytics', 'latitude', 'longitude', 'social'];

    public function detail(): HasOne
    {
        return $this->hasOne(SettingDetail::class)->where('language_id', session('language', 1));
    }

    public function settingEmail(): HasOne
    {
        return $this->hasOne(SettingEmail::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('setting_icon')->singleFile();
        $this->addMediaCollection('setting_logo')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->performOnCollections('setting_logo')
            ->width(300)
            ->height(300)
            ->sharpen(10);
        $this->addMediaConversion('thumb')
            ->performOnCollections('setting_icon')
            ->width(20)
            ->height(20)
            ->sharpen(10);

    }

    /**
     * @return string
     */
    public function getLogoThumbPathAttribute()
    {
        if ($this->hasMedia('setting_logo')) {
            return $this->getFirstMedia('setting_logo')->getUrl('thumb');
        }

        return ImageTool::mycrop('placeholder.png', 100, 100);
    }

    public function getLogoThumbAttribute()
    {
        if ($this->hasMedia('setting_logo')) {
            return $this->getFirstMedia('setting_logo')->getUrl('thumb');
        }

        return null;
    }

    public function getIconThumbPathAttribute()
    {
        if ($this->hasMedia('setting_icon')) {
            return $this->getFirstMedia('setting_icon')->getUrl('thumb');
        }

        return ImageTool::mycrop('placeholder.png', 100, 100);
    }

    public function getIconThumbAttribute()
    {
        if ($this->hasMedia('setting_icon')) {
            return $this->getFirstMedia('setting_icon')->getUrl('thumb');
        }

        return null;
    }

    public static function getProtocols()
    {
        $data[] = ['title' => 'SMTP', 'value' => 'smtp'];
        $data[] = ['title' => 'Localhost', 'value' => 'sendmail'];
        $data[] = ['title' => 'Mailgun', 'value' => 'mailgun'];
        $data[] = ['title' => 'mandrill', 'value' => 'mandrill'];

        return $data;
    }
}
