<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //

    public function saveImage($image, $path = 'employes')
    {
        if (!$image) {
            return null;
        }

        $filename = time() . '.png';
        //Enrregistrez l'image
        \Storage::disk($path)->put($filename, base64_decode($image));

        //retrun thepath
        //url is the base url exemple : localhost!8000
        return URL::to('/') . '/storage/' . $path . '/' . $filename;
    }
}
