<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProdutController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if ($request->get('Keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->Keyword . '%');
        }
        $products = $products->paginate(10);
        return view('admin.product.index', compact('products'));
    }
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.create', compact('categories', 'brands'));
    }
    //store
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validateData = Validator::make($request->all(), $rules);

        if ($validateData->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();
            //save image
            if (!empty($request->image_Array)) {
                $i = 1;

                foreach ($request->image_Array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);

                    if ($tempImageInfo) {
                        $extArray = explode('.', $tempImageInfo->name);
                        $ext = last($extArray);

                        // Create ProductImage instance
                        $productImage = new ProductImage();
                        $productImage->product_id = $product->id;
                        $productImage->image = 'NULL';
                        $productImage->save();

                        // Generate a unique image name
                        $imageName = $product->id . $i++ . $productImage->id . '-' . time() . '.' . $ext;
                        $productImage->image = $imageName;
                        $productImage->save();

                        $tempDestinationPath = public_path() . '/temp/product/';
                        $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                        $tempLargeDestPath = $tempDestinationPath . $imageName;

                        // Move the file within the temp/product folder
                        if (rename($sourcePath, $tempLargeDestPath)) {
                            // Optionally, you may want to delete the TempImage record
                            $tempImageInfo->delete();

                            // Optional: Check if the file exists after moving within the temp/product folder
                            if (file_exists($tempLargeDestPath)) {
                                Log::info('File exists after move within temp/product folder: ' . $tempLargeDestPath);
                            } else {
                                Log::error('File does not exist after move within temp/product folder: ' . $tempLargeDestPath);
                            }
                        } else {
                            // Handle the case where the file move failed
                            Log::error('File move within temp/product folder failed: ' . $sourcePath);
                        }
                    }
                }
            }


            Session::flash('success', 'product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'product added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors()
            ]);
        }
    }
    // edit

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            Session::flash('error', 'Product Not Found');
            return redirect()->route('product.index')->with('error', 'Product Not Found');
        }
        $productImages = ProductImage::where('product_id', $product->id)->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.edit', compact('categories', 'brands', 'product', 'subCategories', 'productImages'));
    }
    // update
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug, ' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku, ' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validateData = Validator::make($request->all(), $rules);

        if ($validateData->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();
            Session::flash('success', 'product updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'product updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors()
            ]);
        }
    }
    //destroy
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            Session::flash('error', 'ID Not Found');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();
        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('temp/product/' . $productImage->image));
            }

            ProductImage::where('product_id', $id)->delete();
        }
        $product->delete();
        Session::flash('success', 'delete successfully');
        return response()->json([
            'status' => true,
            'message' => 'delete success'
        ]);
    }
}
