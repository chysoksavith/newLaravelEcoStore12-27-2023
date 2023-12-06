<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $i = 1;
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id . $i++ . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Move the uploaded file to the temp/product folder
        $image->move(public_path('temp/product'), $imageName);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('temp/product/' . $productImage->image),
            'message' => 'Image Save success'
        ]);
    }
    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);
        if(empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ]);
        }

        File::delete(public_path('temp/product/'.$productImage->image));
        $productImage->delete();
        return response()->json([
            'status' => true,
            'message' => 'Image delete success'
        ]);
    }
}
