<?php

namespace App\Http\Controllers;

use App\Models\CouponRedeemed;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Retailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CouponRedeemController extends Controller
{
    public function getAll()
    {
        $redeems = CouponRedeemed::all();
        return response()->json([
            "data" => $redeems,
            "message" => "Fetch Successful"
        ]);
    }

    public function create(Request $request)
    {
        $redeem = new CouponRedeemed;
        $redeem->coupon_id = $request->coupon_id;
        $redeem->user_id = $request->user_id;
        $redeem->coupon_download_id = $request->coupon_download_id;
        $redeem->redemption_code = $request->redemption_code;
        $redeem->save();

        try {
            $coupon = Coupon::where('id', $request->coupon_id)->first();
            $retailer = Retailer::where('id', $coupon->retailer_id)->first();
            $user = User::where('retailer_id', $request->user_id)->first();
            $redeem_user = User::where('id', $retailer->id)->first();
            $notif = new \App\Http\Controllers\NotificationsController();
            $notif->setNotification(new \App\Models\Notification([
                "user_id" => $request->user_id,
                "title" => "Downloaded Coupon Redeemed",
                "content" => "Your Downloaded Coupon [" . $coupon->name . "] on The DashShop from \"" . $retailer->business_name . "\" was just redeemed.",
                "type" => "coupon",
                "source_id" => $coupon->id,
                "has_read" => false,
                "trash" => false
            ]));

            $notif->setNotification(new \App\Models\Notification([
                "user_id" => $user->id,
                "title" => "Coupon Redeemed",
                "content" => "Your Created Coupon [" . $coupon->name . "] on The DashShop has just redeemed by user - " . $redeem_user->email . ".",
                "type" => "coupon",
                "source_id" => $coupon->id,
                "has_read" => false,
                "trash" => false
            ]));
        } catch (\Exception $e) {
        }

        return response()->json([
            "message" => "Coupon Redemption Successful",
            "data" => $redeem
        ], 200);
    }

    public function getByRetailer($retailer_id)
    {
        $coupons = DB::table('coupon_redemption')
            ->join('coupons', 'coupons.id', '=', 'coupon_redemption.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select(DB::raw('coupon_redemption.id as analytics_id,(select created_at from coupon_redemption where coupon_id = coupons.id order by created_at desc limit 1) as last_date, (select count(*) from coupon_redemption where coupon_id = coupons.id) as click_count, coupons.*, retailers.business_name, retailers.banner_image, retailers.business_address, retailers.city, retailers.state, retailers.phone_number, retailers.email,retailers.business_description, categories.name as category_name'))
            ->where('retailers.id', '=', $retailer_id)
            ->orderBy('coupon_redemption.created_at', 'desc')
            ->get();

        $allMd5s = array_map(function ($v) {
            return $v->id;
        }, $coupons->toArray());

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
        $redeem = CouponRedeemed::find($id);
        if (!empty($redeem)) {
            return response()->json($redeem);
        } else {
            return response()->json([
                "message" => "Coupon Redemption not Found"
            ], 404);
        }
    }
    public function findByDownloadId($id): JsonResponse
    {
        $redeem = CouponRedeemed::find('coupon_download_id', $id);
        if (!empty($redeem)) {
            return response()->json(["data" => $redeem]);
        } else {
            return response()->json([
                "message" => "Coupon Redemption not Found"
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        if (CouponRedeemed::where('id', $id)->exists()) {
            $redeem = CouponRedeemed::findorfail($id);
            $redeem->coupon_id = is_null($request->coupon_id) ? $redeem->coupon_id : $request->coupon_id;
            $redeem->user_id = is_null($request->user_id) ? $redeem->user_id : $request->user_id;
            $redeem->email = is_null($request->coupon_download_id) ? $redeem->coupon_download_id : $request->coupon_download_id;
            $redeem->redemption_code = is_null($request->redemption_code) ? $redeem->redemption_code : $request->redemption_code;
        }
    }

    public function destroy($id): JsonResponse
    {
        if (CouponRedeemed::where('id', $id)->exists()) {
            $redeem = CouponRedeemed::find($id);
            $redeem->delete();


            return response()->json([
                "message" => "Coupon Redemption Record Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Coupon Redemption Record Not Found"
            ], 404);
        }
    }
}
