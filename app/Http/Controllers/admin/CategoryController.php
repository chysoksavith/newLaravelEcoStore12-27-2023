<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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
