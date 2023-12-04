<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get('Keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('Keyword') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('Keyword') . '%');
        }

        $subCategories = $subCategories->paginate(10); // Corrected line

        return view('admin.sub_category.index', compact('subCategories'));
    }
    //
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;

        return view('admin.sub_category.create', $data);
    }
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validatedData->passes()) {
            // Validation passed, proceed with creating and storing the sub-category
            try {
                $subCategory = new SubCategory();
                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->status = $request->status;
                $subCategory->category_id = $request->category;
                $subCategory->save();
                Session::flash('success', 'Sub-category created successfully');
                return response([
                    'status' => true,
                    'subCategory' => $subCategory,
                    'message' => 'Sub-category created successfully'
                ]);
            } catch (\Exception $e) {
                // Handle any exceptions that occur during the creation and storage process
                Session::flash('error', 'Failed to create sub-category. ' . $e->getMessage());

                return response([
                    'status' => false,
                    'error' => 'Failed to create sub-category. ' . $e->getMessage()
                ]);
            }
        } else {
            // Validation failed, return the validation errors
            return response([
                'status' => false,
                'errors' => $validatedData->errors()
            ]);
        }
    }
    // edit

    public function edit(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            Session::flash('error', 'Record not found');
            return redirect()->route('sub-category.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.edit', compact('categories', 'subCategory'));
    }
    // update
    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            Session::flash('error', 'Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
            //return redirect()->route('sub-category.index');
        }
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validatedData->passes()) {
            // Validation passed, proceed with creating and storing the sub-category
            try {
                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->status = $request->status;
                $subCategory->category_id = $request->category;
                $subCategory->save();
                Session::flash('success', 'Sub-category created successfully');
                return response([
                    'status' => true,
                    'subCategory' => $subCategory,
                    'message' => 'Sub-category created successfully'
                ]);
            } catch (\Exception $e) {
                // Handle any exceptions that occur during the creation and storage process
                Session::flash('error', 'Failed to updated sub-category. ' . $e->getMessage());

                return response([
                    'status' => false,
                    'error' => 'NoFound. ' . $e->getMessage()
                ]);
            }
        } else {
            // Validation failed, return the validation errors
            return response([
                'status' => false,
                'errors' => $validatedData->errors()
            ]);
        }
    }
    // delete
    public function destroy(Request $request, $id){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            Session::flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }
        $subCategory->delete();
        Session::flash('success', 'Delete Success');
        return response([
            'status' => true,
            'message' => 'Delete Success'
        ]);
    }
}
