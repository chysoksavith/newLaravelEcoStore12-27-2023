<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TempImageController extends Controller
{
    public function store(Request $request)
    {
        $image = $request->file('image');

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            // Save the original image to the temp folder
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();
            $image->move(public_path() . '/temp', $newName);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/' . $newName),
                'message' => 'Image upload success'
            ]);
        }
    }
}
