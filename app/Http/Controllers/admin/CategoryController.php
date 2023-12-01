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

        // Call paginate after applying filters
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
    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }
        return view('admin.category.edit', compact('category'));
    }
    // update
    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('category.index');
        }
        $validatorData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validatorData->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;

            // save image
            if (!empty($request->image_id)) {
                $temImage = TempImage::find($request->image_id);

                if ($temImage) {
                    $extArray = explode('.', $temImage->name);
                    $ext = last($extArray);

                    $newImageName = $category->id . '-' . time() . '.' . $ext;
                    $sPath = public_path() . '/temp/' . $temImage->name;
                    $dPath = public_path() . '/uploads/category/' . $newImageName;

                    File::copy($sPath, $dPath);
                    $category->image = $newImageName;
                    $category->save();

                    // delete old image
                    $oldImagePath = public_path('uploads/category/' . $oldImage);

                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
            }

            session()->flash('success', 'Category update successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category update successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validatorData->errors()
            ]);
        }
    }
    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found '
            ]);
        }

        File::delete(public_path() . '/uploads/category/' . $category->image);

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
