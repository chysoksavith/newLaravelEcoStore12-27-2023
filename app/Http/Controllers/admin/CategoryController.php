<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category = Category::latest();

        if (!empty($request->get('Keyword'))) {
            $category = $category->where('name', 'like', '%' . $request->get('Keyword') . '%');
        }
        $category = $category->paginate(10);
        return view('admin.category.list', compact('category'));
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $request)
    {
        $validatorData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validatorData->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();
            // save image
            if (!empty($request->image_id)) {
                $temImage = TempImage::find($request->image_id);
                $extArray = explode('.', $temImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $temImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sPath, $dPath);

                // generate image thumbnail
                try {
                    $img = Image::make($sPath);
                    $img->resize(450, 600);
                    $img->save($dPath);
                } catch (\Exception $e) {
                    // Log or handle the exception
                    \Illuminate\Support\Facades\Log::error('Image Resize and Save Exception: ' . $e->getMessage());
                }
                $category->image = $newImageName;
                $category->save();
            }
            session()->flash('success', 'Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validatorData->errors()
            ]);
        }
    }
    public function edit()
    {
        return view('');
    }
    public function update()
    {
    }
    public function destroy()
    {
        return view('');
    }
}
