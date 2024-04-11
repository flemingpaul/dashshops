<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\CouponClicks;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function destroy($user_id,$retailer_id): JsonResponse
    {
        if(Favorite::where(['user_id'=> $user_id, 'retailer_id'=>$retailer_id])->exists()){
            $favorite = Favorite::where(['user_id'=> $user_id, 'retailer_id'=>$retailer_id])->first();
            $favorite->delete();
            return response()->json([
                "message" => "Favorite Removed"
            ], 202);
        } else {
            return response()->json([
                "message" => "Favorite Record Not Found"
            ], 404);
        }
    }
    public function getUserFavoriteForStore($user_id,$retailer_id): JsonResponse
    {
        if(Favorite::where(['user_id'=> $user_id, 'retailer_id'=>$retailer_id])->exists()){
            $favorite = Favorite::where(['user_id'=> $user_id, 'retailer_id'=>$retailer_id])->first();
            
            return response()->json([
                "message" => "Data Found",
                "data"=>$favorite
            ], 201);
        } else {
            return response()->json([
                "message" => "Favorite Record Not Found"
            ], 202);
        }
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required',
            'retailer_id' => 'required'
        ]);
        $favorite = Favorite::where('user_id', $request->user_id)->where('retailer_id', $request->retailer_id)->first();
        if($favorite){
            return response()->json([
                "message" => "Favorite already exists"
            ], 409);
        }
        $favorite = new Favorite();
        $favorite->user_id = $request->user_id;
        $favorite->retailer_id = $request->retailer_id;
        $favorite->save();

        return response()->json([
            "message" => "Favorite record created",
            "data"=>$favorite
        ], 201);
    }
    public function getAll($user_id): JsonResponse
    {
        $favorites = DB::table('favorites')
            ->join('retailers', 'favorites.retailer_id', '=', 'retailers.id')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select('favorites.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description','retailers.longitude','retailers.latitude','retailers.from_mobile', 'categories.name as category_name')
            ->where('favorites.user_id', $user_id)
            ->orderBy('favorites.created_at', 'desc')
            ->get();
        return response()->json(["data"=>$favorites], 200);
    }
}