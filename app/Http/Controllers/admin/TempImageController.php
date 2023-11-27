<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

class TempImageController extends Controller
{
    public function store(Request $request)
    {
        $image = $request->image;
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $temImage = new TempImage();
            $temImage->name = $newName;
            $temImage->save();
            $image->move(public_path().'/temp/', $newName);

            return response()->json([
                'status' => true,
                'image_id' => $temImage->id,
                'message' => 'Image upload success'
            ]);
        }
    }

}
