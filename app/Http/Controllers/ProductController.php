<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cart;
use App\Models\Rating;
use App\Models\CouponClicks;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



class ProductController extends Controller
{
    function productHasSales($id){
        return false;
    }
    
    function delete(Request $request, $id)
    {
        $user = $request->user();

        if (Product::where(['id' => $id, 'store_id' => $user->retailer_id])->exists()) {
            if($this->productHasSales($id)){
             return response()->json([
                    'status' => false,
                    'message' => "Product is already associated wih sales, either disable it or set all available quantities to zero"
                ], 404);
            }
            $product = Product::find($id);

            if (file_exists(public_path('images/' . $product->image))) {
                unlink(public_path('images/' . $product->image));
            }
            $existing_images = json_decode($product->images, true);
            foreach ($existing_images as $img) {

                if (file_exists(public_path("images/" . $img))) {
                    unlink(public_path("images/" . $img));
                }
            }
            $product->delete();


            return response()->json(
                [
                    'status' => true,
                    'message' => "Product deleted successfully"
                ],
                200
            );
        } else {
            return response()->json([
                'status' => false,
                'message' => "Could not find product with id associated to your store"
            ], 404);
        }
    }
    function update(Request $request)
    {
        $existing_images = [];
        $user = $request->user();
        try {
            if ($request->id == 0) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                ]);
            }
            if ((int)$request->other_image_count > 0) {
                Log::info("it has other image count ===");
                for ($i = 0; $i < (int)$request->other_image_count; $i++) {
                    Log::info("validating ==== " . $i);
                    $request->validate([
                        "image_$i" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                    ]);
                }
            }

            $request->validate(
                [
                    'product_name' => 'required',
                    'description' => 'required',
                    'category_id' => 'required',

                ]
            );
            $product = new Product();

            if ($request->id == 0) {
                $product->store_id = $user->retailer_id;
                $product->status = -1;
            } else {
                if (Product::where(["id" => $request->id, "store_id" => $user->retailer_id])->exists()) {
                    $product = Product::where(["id" => $request->id, "store_id" => $user->retailer_id])->first();
                    //$product->status = $request->status;
                    if ($request->has("image")) {
                        if (file_exists(public_path("images/" . $product->image))) {
                            unlink(public_path("images/" . $product->image));
                        }
                    }
                    $images = json_decode($request->images_to_delete, true);
                    Log::info($images);
                    $existing_images = json_decode($product->images, true);
                    foreach ($images as $img) {
                        if (($key = array_search($img, $existing_images)) !== false) {
                            unset($existing_images[$key]);
                        }
                        if (file_exists(public_path("images/" . $img))) {
                            unlink(public_path("images/" . $img));
                        }
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Could not find product with id associated to your store"
                    ], 400);
                }
            }
            $product->category_id = $request->category_id;
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->overview = $request->overview;

            $product->save();

            $alias = str_replace(" ", "-", $request->product_name) . '_' . $product->id;
            if (strlen($alias) > 250) {
                $alias = substr($alias, 0, 210) . '_' . $product->id;
            }
            $product->alias = $alias;
            $path = public_path('images/products/' . $product->id);
            File::isDirectory($path) or File::makeDirectory($path, 0755, true, true);
            if ($request->has("image")) {
                $imageName = "products/" . $product->id . "/" . time() . '.' . $request->image->extension();
                $request->image->move($path, $imageName);
                $product->image = $imageName;
            }

            if ((int)$request->other_image_count > 0) {
                if ($request->id == 0) {
                    $images = [];
                } else {
                    $images = $existing_images;
                }

                for ($i = 0; $i < (int)$request->other_image_count; $i++) {
                    Log::info("at ====== @ " . $i);
                    //var_dump($request->file("image_".$i));
                    $file = $request->file("image_" . $i);
                    $imageName = "products/" . $product->id . "/" . time() . '_' . $i . '.' . $file->extension();
                    $file->move($path, $imageName);
                    array_push($images, $imageName);
                }
                $product->images = json_encode($images);
            }

            $product->save();

            $variants = json_decode($request->variants);
            $product_variants = ProductVariant::where(["product_id" => $product->id])->get();
            foreach ($product_variants as $pv) {
                $filtered_array = array_filter($variants, function ($obj) use ($pv) {
                    return $obj->id == $pv->id;
                });
                if (count($filtered_array) == 0) {
                    DB::delete('delete from product_variants where id = ?', [$pv->id]);
                }
            }

            foreach ($variants as $v) {
                if ($v->id == 0) {
                    ProductVariant::create([
                        "product_id" => $product->id,
                        "on_sale" => $v->on_sale,
                        "variant_types" => $v->variant_types,
                        "variant_type_values" => $v->variant_type_values,
                        "price" => $v->price,
                        "sale_price" => $v->sale_price,
                        "quantity" => $v->quantity,
                        "low_stock_value" => $v->low_stock_value,
                        "status" => $v->status

                    ]);
                } else {
                    if (ProductVariant::where("id", $v->id)->exists()) {
                        $pv = ProductVariant::find($v->id);
                        $pv->product_id = $product->id;
                        $pv->on_sale = $v->on_sale;
                        $pv->variant_types = $v->variant_types;
                        $pv->variant_type_values = $v->variant_type_values;
                        $pv->price = $v->price;
                        $pv->sale_price = $v->sale_price;
                        $pv->quantity = $v->quantity;
                        $pv->low_stock_value = $v->low_stock_value;
                        $pv->status = $v->status;
                        $pv->save();
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Product Updated Successfully',
                "product_id" => $product->id
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getProduct($id)
    {
        $products = DB::table('product_variation')
            ->join('products', 'products.id', '=', 'product_variation.product_id')
            ->join('retailers', 'retailers.id', '=', 'products.store_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select($this->getSelectDBRawProducts())
            ->where('products.id', '=', $id);
        $products = $products->first();

        $pvs = ProductVariant::where('product_id', $id)->get();

        return response()->json([
            "data" => ['product' => $products, 'variants' => $pvs]
        ], 200);
    }
    public function getProductVariants($product_id)
    {
        $pvs = ProductVariant::where('product_id', $product_id)->get();
        return response()->json([
            "data" => $pvs
        ], 200);
    }

    public function getrelevantproducts($count,$category="0",$search=""){
        $products = DB::table('product_variation')
            ->join('products', 'products.id', '=', 'product_variation.product_id')
            ->join('retailers', 'retailers.id', '=', 'products.store_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select($this->getSelectDBRawProducts());
        if ($category != 0) {
            $products = $products->where('products.category_id', '=', $category);
        }
        /*if ($city != "") {
            $products = $products->where('retailers.city', '=', $city);
        }
        if ($state != "") {
            $products = $products->where('retailers.state', '=', $state);
        }*/

        if ($search != "") {
            /*$coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');*/
            $products = $products->whereNested(function ($q) use ($search) {
                $q->orWhere('products.product_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('product_variation.variant_type_values', 'LIKE', '%' . $search . '%')
                    ->orWhere('products.description', 'LIKE', '%' . $search . '%');
            });
        }
        $products = $products->where('products.status', 1);
        $products = $products->where('product_variation.status', 1);
        $products = $products->where('retailers.approval_status', 'Approved')
        ->groupBy("products.id");

        $products = $products->get()->take($count);

        return response()->json([
            "data" => $products
        ], 200);
        
    }
    public function getrelevantproducts2($count,$category="0",$city="-",$state="-",$search="")
    {
        $products = DB::table('product_variation')
        ->join('products', 'products.id', '=', 'product_variation.product_id')
        ->join('retailers', 'retailers.id', '=', 'products.store_id')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->select($this->getSelectDBRawProducts());
        if ($category != 0) {
            $products = $products->where('products.category_id', '=', $category);
        }
        if ($city != "") {
            $products = $products->where('retailers.city', '=', $city);
        }
        if ($state != "") {
            $products = $products->where('retailers.state', '=', $state);
        }

        if ($search != "") {
            /*$coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');*/
            $products = $products->whereNested(function ($q) use ($search) {
                $q->orWhere('products.product_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('product_variation.variant_type_values', 'LIKE', '%' . $search . '%')
                    ->orWhere('products.description', 'LIKE', '%' . $search . '%');
            });
        }
        $products = $products->where('products.status', 1);
        $products = $products->where('product_variation.status', 1);
        $products = $products->where('retailers.approval_status', 'Approved')
        ->groupBy("products.id");

        $products = $products->get()->take($count);

        return response()->json([
            "data" => $products
        ], 200);
    }
    public function showAll(Request $request)
    {
        $category = $request->has('category') ? $request->get('category') : "0";
        $page = $request->has('page') ? $request->get('page') : 1;
        $type = $request->has('type') ? $request->get('type') : "all";
        $offertype = $request->has('offer_type') ? $request->get('offer_type') : "all";
        $search = $request->has('search') ? $request->get('search') : "";
        $retailers = Retailer::where('approval_status', "Approved");
      
        if (Auth::user()->admin == 2) {
            $retailers = $retailers->where('created_by', '=', Auth::user()->id);
        }
        $retailers  = $retailers->get();
        //
        $categories = Category::all();
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();
        return view('pages.products', compact(['retailers', 'category', 'type', 'offertype', 'search', 'categories', 'total_downloads', 'total_clicks', 'total_redemptions']));
    }

    function search(Request $request){
        $category = $request->get('category_id');
        $retailer_id = $request->has("retailer_id")?$request->get('retailer_id'):"0";
        $search = $request->get('search');
        $status = $request->get('status');
        $products = DB::table('product_variation')
        ->join('products', 'products.id', '=', 'product_variation.product_id')
        ->join('retailers', 'retailers.id', '=', 'products.store_id')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->select($this->getSelectDBRawProducts());
        if ((int)$category != 0) {
            $products = $products->where('products.category_id', '=', $category);
        }
        if ((int)$retailer_id != 0) {
            $products = $products->where('products.store_id', '=',(int)$retailer_id);
        }
    

        if ($search != "") {
            $products = $products->whereNested(function ($q) use ($search) {
                $q->orWhere('products.product_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('product_variation.variant_type_values', 'LIKE', '%' . $search . '%')
                    ->orWhere('products.description', 'LIKE', '%' . $search . '%')
                    ->orWhere('products.overview', 'LIKE', '%' . $search . '%');
            });
        }
        if($status != "all"){
            $products = $products->where('products.status', (int)$status);
            $products = $products->where('product_variation.status', (int)$status);
        }
        $products = $products->groupBy("products.id");
        $products = $products->get();
        //echo json_encode($products);die();
        $view = view('pages.products-table',["products"=>$products]);
        return $view;
    }
}
