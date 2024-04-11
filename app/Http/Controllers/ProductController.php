<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\CouponClicks;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    function update(Request $request)
    {
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
                    Log::info("validating ==== ".$i);
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
                    if($request->has("image")){
                        if (file_exists(public_path("images/".$product->image))) {
                            unlink(public_path("images/".$product->image));
                        }
                    }
                    $images = json_decode($request->images_to_delete,true);
                    foreach ($images as $img) {
                        if (file_exists(public_path("images/".$img))) {
                            unlink(public_path("images/".$img));
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
                $images = [];
                for ($i = 0; $i < (int)$request->other_image_count; $i++) {
                    Log::info("at ====== @ ".$i);
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
                if(count($filtered_array ) == 0){
                    DB::delete('delete from product_variants where id = ?',[$pv->id]);
                }
            }

            foreach($variants as $v){
                if($v->id == 0){
                    ProductVariant::create([
                        "product_id"=>$product->id,
                        "on_sale"=>$v->on_sale,
                        "variant_types"=>$v->variant_types,
                        "variant_type_values" => $v->variant_type_values,
                        "price"=>$v->price,
                        "sale_price" => $v->sale_price,
                        "quantity"=>$v->quantity,
                        "low_stock_value"=>$v->low_stock_value,
                        "status"=>$v->status
                        
                    ]);
                }else{
                    if(ProductVariant::where("id",$v->id)->exists()){
                        $pv = ProductVariant::find($v->id);
                        $pv->product_id=$product->id;
                        $pv->on_sale=$v->on_sale;
                        $pv->variant_types=$v->variant_types;
                        $pv->variant_type_values = $v->variant_type_values;
                        $pv->price=$v->price;
                        $pv->sale_price = $v->sale_price;
                        $pv->quantity=$v->quantity;
                        $pv->low_stock_value=$v->low_stock_value;
                        $pv->status=$v->status;
                        $pv->save();
                    }
                }
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Product Updated Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
