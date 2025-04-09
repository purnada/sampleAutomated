<?php

namespace App\Library;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageTool
{
    public static function mycrop($myfilename, $width, $height, $default = '')
    {

        if (! Storage::exists($myfilename)) {
            return;
        }
        $new_extens = ['svg', 'webp'];
        $extension = pathinfo($myfilename, PATHINFO_EXTENSION);
        if (in_array($extension, $new_extens)) {

            return Storage::url($myfilename);
        }

        $extension = pathinfo($myfilename, PATHINFO_EXTENSION);

        $old_image = $myfilename;
        $new_image = 'cache/'.Str::remove('.'.$extension, $myfilename).'-'.$width.'x'.$height.'.'.$extension;

        if (! Storage::exists($new_image)) {
            [$width_orig, $height_orig, $image_type] = getimagesize(Storage::path($old_image));
            if ($image_type == IMAGETYPE_PNG) {
                $f = @imagecreatefrompng($myfilename);
                if (! $f) {
                    return Storage::url($myfilename);
                }
            }

            if (! in_array($image_type, [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF])) {
                return Storage::url($old_image);
            }
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));
            $tempdir = public_path('images/catalog/');

            foreach ($directories as $directory) {

                $path = $path.'/'.$directory;

                if (! Storage::exists($path)) {
                    Storage::makeDirectory($path);
                }

                if (! is_dir($tempdir.$path)) {
                    if (! mkdir($tempdir.$path, 0777) && ! is_dir($tempdir.$path)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $tempdir.$path));
                    }
                }

            }

            if ($width_orig != $width || $height_orig != $height) {

                $image = new MyImage(Storage::path($old_image));
                $image->resize($width, $height);
                $image->save(Storage::path($new_image));
                Storage::move($tempdir.$new_image, $new_image);
            } else {
                Storage::copy($old_image, $new_image);
            }
        }

        return Storage::url($new_image);

    }
}
