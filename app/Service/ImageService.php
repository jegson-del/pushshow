<?php

namespace App\Service;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImageService 
{

    public function imageUpload($image, $path) 
    {    
        $data = explode(',', $image);
        $current_timestamp = Carbon::now()->timestamp;
        // $extension = explode('/', mime_content_type($image))[1];
        $imageName = rand().'.'.'png';
        $filename= $path.$imageName;
        $response = Storage::disk('s3')->put($filename, base64_decode($data[1]), 'public');
        $url = Storage::disk('s3')->url($filename);
        return $url;
    }
}