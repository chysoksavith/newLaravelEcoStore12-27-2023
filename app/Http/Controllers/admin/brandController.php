<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class brandController extends Controller
{
    public function index(Request $request)
    {
        $brand = Brand::latest('id');

        if ($request->get('Keyword')) {
            $brand = $brand->where('name', 'like', '%' . $request->Keyword . '%');
        }

        $brand = $brand->paginate(10);

        return view('admin.brand.index', compact('brand'));
    }
    // create
    public function create()
    {
        return view('admin.brand.create');
    }
    // store
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]);
        Session::flash('success', 'Brand created successfully');

        if ($validateData->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors()
            ]);
        }
    }
    // edit
    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            Session::flash('error', 'Record Not Found');
            return redirect()->route('brand.index');
        }

        return view('admin.brand.edit', compact('brand'));
    }
    public function update(Request $request, int $id)
    {
        $brand = Brand::find($id);
        if(empty($brand)){
            Session::flash('error', 'Record Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }
        $validateData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' => 'required'
        ]);
        Session::flash('success', 'Brand created successfully');

        if ($validateData->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors()
            ]);
        }
    }
    // deleet
    public function destroy(Request $request, $id){
        $brand = Brand::find($id);
        if(empty($brand)){
            Session::flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }
        $brand->delete();
        Session::flash('success', 'Delete Success');
        return response([
            'status' => true,
            'message' => 'Delete Success'
        ]);
    }
}
