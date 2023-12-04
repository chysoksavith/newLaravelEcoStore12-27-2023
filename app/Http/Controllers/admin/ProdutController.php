<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProdutController extends Controller
{
    public function create(){
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.create', compact('categories','brands'));
    }
    //store
}
