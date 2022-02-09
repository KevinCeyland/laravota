<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Stockage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ParamHelper
{

    /**
     * Fonction permettant d'encodé en base 64 une image
     *
     * @param [string] $img
     * @param [string] $type
     * @param [string] $folder
     * @return l'image en base64
     */
    public static function getImageStringAttribute($img, $type, $folder)
    {
        if ($img) {
            // Nous cherchons dans le dossier public de Laravel l'image demandé grâce au paramètre $folder qui est le dossier de conservation de l'image
            // Et le nom de l'image
            $data = File::get(public_path("img/" . $folder . "/" . $img));
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data);
            return $base64;
        }
        return null;
    }

    public static function deleteFile($img, $folder)
    {
        File::delete(public_path("img/" . $folder . "/" . $img));
    }
    /**
     * Image resize
     */
    public static function resizeImg($source)
    {

        $img = Image::make($source);

        $img->resize(512, 512, function ($const) {
            $const->aspectRatio();
        });

        return $img->encode(null, 80);
    }

    public static function validateExtensions()
    {
        return '|mimes:jpg,jpeg,png,gif';
    }
}
