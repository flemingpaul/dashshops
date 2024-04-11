<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CouponClicks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CouponClicksController extends Controller
{
    //
    public function getAll()
    {
        $clicks = CouponClicks::all();
        return response()->json([
            "data" => $clicks,
            "message" => "Fetch Successful"
        ]);
    }

    public function create(Request $request)
    {
        $click = new CouponClicks;
        $click->coupon_id = $request->coupon_id;
        $click->clicks = $request->clicks;
        $click->state = $request->state;
        $click->city = $request->city;
        $click->save();

        return response()->json([
            "message" => "Click Added",
            "data" => $click
        ], 201);
    }

    public function getByRetailer($retailer_id)
    {
        $coupons = DB::table('coupons_clicks')
            ->join('coupons', 'coupons.id', '=', 'coupons_clicks.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select(DB::raw('coupons_clicks.id as analytics_id,(select created_at from coupons_clicks where coupon_id = coupons.id order by created_at desc limit 1) as last_date, (select count(*) from coupons_clicks where coupon_id = coupons.id) as click_count, coupons.*, retailers.business_name, retailers.banner_image, retailers.business_address, retailers.city, retailers.state, retailers.phone_number, retailers.email,retailers.business_description, categories.name as category_name'))
            ->where('retailers.id', '=', $retailer_id)
            ->orderBy('coupons_clicks.created_at', 'desc')
            ->get();

        $allMd5s = array_map(function ($v) {

            return $v->id;
        }, $coupons->toArray());

        //will optimise this later  

        $uniqueMd5s = array_unique($allMd5s);

        $result = [];
        foreach (array_intersect_key($coupons->toArray(), $uniqueMd5s) as $v) {
            array_push($result, $v);
            //$result[] = $v;
        }
        return response()->json(["data" => $result]);
    }

    public function show($id): JsonResponse
    {
        $click = CouponClicks::find($id);
        if (!empty($click)) {
            return response()->json($click);
        } else {
            return response()->json([
                "message" => "Click Record  not Found"
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        if (CouponClicks::where('id', $id)->exists()) {
            $click = CouponClicks::findorfail($id);
            $click->clicks = is_null($request->clicks) ? $click->clicks : $request->clicks;
        }
    }

    public function destroy($id): JsonResponse
    {
        if (CouponClicks::where('id', $id)->exists()) {
            $click = CouponClicks::find($id);
            $click->delete();


            return response()->json([
                "message" => "Click Record Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Click Record Not Found"
            ], 404);
        }
    }
}
