<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\CouponClicks;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingsController extends Controller
{
    //
    public function getRetailerRatings($retailer_id){
        $ratings = Rating::where();
        $ratings = DB::table('ratings')
            ->join('retailers', 'retailers.id', '=', 'ratings.retailer_id')
            ->join('users', 'users.id', '=', 'ratings.user_id')
            ->select('ratings.*','retailers.business_name','users.firstname as user_firstname')
            ->where('ratings.retailer_id', '=', $retailer_id)
            ->where('ratings.approval_status', '=', 'APPROVED')
            ->whereNotNull('ratings.comment')
            ->orderBy('ratings.created_at', 'desc')
            ->get();
        return response()->json([
            "data" => $ratings,
            "message" => "Fetch Successful"
        ]);
    }

    public function getRetailerRatingSummary($retailer_id){
        $summary = Rating::select(DB::raw("AVG(rating) as rating_average, (select count(*) from ratings where comments='APPROVED') as review_count"))
            ->where('retailer_id', '=', $retailer_id)
            ->groupBy('retailer_id')
            ->first();
            return response()->json([
                "data" => $summary,
                "message" => "Fetch Successful"
            ]);
    }

    public function update(Request $request){
        $rating = new Rating();
        if($request->has('id')){
            if(Rating::where('id', $request->id)->exists()){
                $rating = Rating::find($request->id);
            } 
        }
        $user_id = 0;
        if($request->has('user_id')){
            if(User::where('id', $request->user_id)->exists()){
                $user = User::where('id', $request->user_id)->first();
                $user_id = $request->user_id;
                if((int)$user->retailer_id == (int)$request->retailer_id){
                    return response()->json(["message"=>"Sorry, you cannot rate your own store"],400);
                }
            }
        }else if($request->has('is_retailer_id')){
            if(User::where('retailer_id', $request->is_retailer_id)->exists()){
                if((int)$request->is_retailer_id == (int)$request->retailer_id){
                    return response()->json(["message"=>"Sorry, you cannot rate your own store"],400);
                }
                $user = User::where('retailer_id', $request->is_retailer_id)->first();
                $user_id = $user->id;
            }else{
                return response()->json([
                    "message" => "User Record Not Found"
                ], 404);
            }
        }else{
            return response()->json([
                "message" => "User Record Not Found"
            ], 404);
        }

        $rating->user_id = $user_id;
        if($request->has('comments') && $request->comments != ""){
            $rating->comments = $request->comments;
            $rating->approval_status = "PENDING";
        }
        $rating->rating = $request->rating;
        $rating->retailer_id = $request->retailer_id;
        $rating->save();

        return response()->json([
            "message" => "Ratings Added",
            "data" => $rating
        ], 201);
    }

    public function getUserRetailerRating($user_id,$retailer_id): JsonResponse
    {
        $rating = Rating::where('user_id', $user_id)->where('retailer_id', $retailer_id)->first();
        if(!empty($rating)) {
            return response()->json(['data'=>$rating,"status"=>"ok"],200);
        }else{
            return response()->json([
                "message" => "Record  not Found","status"=>"not found"
            ], 201);
        }
    }
   
    public function destroy($id): JsonResponse
    {
        if(Rating::where('id', $id)->exists()){
            $rating = Rating::find($id);
            $rating->delete();
            return response()->json([
                "message" => "Rating Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Rating Record Not Found"
            ], 404);
        }
    }
}
