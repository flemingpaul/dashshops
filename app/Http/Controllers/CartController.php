<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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

    function delete(Request $request, $id)
    {
        $user = $request->user();
        if (Cart::where("id", $id)->exists()) {
            $cart = Cart::find($id);
            $cart->delete();
            return response()->json(
                [
                    'status' => true,
                    'message' => "Item removed from cart"
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => true,
                    'message' => "Cart item not found"
                ],
                404
            );
        }
    }
    function getUserCart(Request $request)
    {
        $user = $request->user();
        $products = DB::table('cart')
            ->join('product_variation', 'product_variation.id', '=', 'cart.product_variation_id')
            ->join('products', 'products.id', '=', 'product_variation.product_id')
            ->join('retailers', 'retailers.id', '=', 'products.store_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select($this->getSelectDBRawCart())
            ->where('cart.user_id', '=', $user->id)
            ->where('products.status','=',1)
            ->where('product_variation.status','=',1);
    

        $products =
        $products->orderBy("products.product_name");
        $products = $products->get();

        return response()->json([
            "data" => $products
        ], 200);
    }

    function add(Request $request)
    {
        $user = $request->user();
        $products = json_decode($request->products_variant_ids, true);
        foreach ($products as $product) {
            if (Cart::where(["product_variation_id" => $product["id"], "user_id" => $user->id])->exists()) {
                $cart = Cart::where(["product_variation_id" => $product, "user_id" => $user->id])->first();
                $cart->quantity = $cart->quantity + (int)$product["quantity"];
                $cart->save();
            } else {
                Cart::create([
                    "user_id" => $user->id,
                    "product_variation_id" => $user->id,
                    "quantity" => $user->id,
                ]);
            }
        }
        return response()->json(
            [
                'status' => true,
                'message' => "Items Added to Cart Successfully"
            ],
            200
        );
    }
}
