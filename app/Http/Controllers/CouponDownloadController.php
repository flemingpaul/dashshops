<?php

namespace App\Http\Controllers;

use App\Models\CouponDownloads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponDownloadController extends Controller
{
    //
    public function getAll(){
        $downloads = CouponDownloads::all();
        return response()->json([
            "data" => $downloads,
            "message" => "Fetch Successful"
        ]);
    }

    public function create(Request $request){
        $download = new CouponDownloads;
        $download->coupon_id = $request->coupon_id;
        $download->downloads = $request->downloads;
        $download->user_id = $request->user_id;
        $download->coupon_code = $request->coupon_code;
        $download->save();

        return response()->json([
            "message" => "Coupon Download Added",
            "data" => $download
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $download = CouponDownloads::find($id);
        if(!empty($download)) {
            return response()->json($download);
        }else{
            return response()->json([
                "message" => "Coupon Download Record  not Found"
            ], 404);
        }
    }

    public function getByRetailer($retailer_id)
    {
        $coupons = DB::table('coupons_download')
            ->join('coupons', 'coupons.id', '=', 'coupons_download.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select(DB::raw('coupons_download.id as analytics_id,(select created_at from coupons_download where coupon_id = coupons.id order by created_at desc limit 1) as last_date, (select count(*) from coupons_download where coupon_id = coupons.id) as click_count, coupons.*, retailers.business_name, retailers.banner_image, retailers.business_address, retailers.city, retailers.state, retailers.phone_number, retailers.email,retailers.business_description, categories.name as category_name'))
            ->where('retailers.id', '=', $retailer_id)
            ->orderBy('coupons_download.created_at', 'desc')
            ->get();

        $allMd5s = array_map(function ($v) {
            return $v->id;
        }, $coupons->toArray());

        $uniqueMd5s = array_unique($allMd5s);

        $result = [];
        foreach(array_intersect_key($coupons->toArray(), $uniqueMd5s) as $v){
            array_push($result, $v);
            //$result[] = $v;
        }
        return response()->json(["data" => $result]);
    }


    public function getUserDownloadedCoupons($id){
        $downloads = DB::table('coupons_download')
            ->join('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')
            ->join('retailers', 'coupons.retailer_id', '=', 'retailers.id')
            ->join('categories', 'coupons.category_id', '=', 'categories.id')
            ->leftJoin('coupon_redemption', 'coupons_download.id', '=', 'coupon_redemption.coupon_download_id')
            ->select('coupon_redemption.id as redemption_id','coupons_download.id as download_id','coupons_download.coupon_code','coupons_download.coupon_id','coupons_download.user_id as creator','coupons_download.created_at as download_date' , 'coupons.*','retailers.business_name','retailers.banner_image','retailers.business_address','retailers.city','retailers.state','retailers.phone_number','retailers.email','retailers.business_description', 'categories.name as category_name')
            ->where('coupons_download.user_id', '=', $id)
            ->get();
        return response()->json([
            "data" => $downloads,
            "message" => "Fetch Successful"
        ]);
    }
    public function getUserDownloadedCouponByQRCode($qr_code,$user_id){
        $download = DB::table('coupons_download')
            ->join('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')
            ->join('retailers', 'coupons.retailer_id', '=', 'retailers.id')
            ->join('categories', 'coupons.category_id', '=', 'categories.id')
            ->leftJoin('coupon_redemption', 'coupons_download.id', '=', 'coupon_redemption.coupon_download_id')
            ->select('coupon_redemption.id as redemption_id','coupons_download.id as download_id','coupons_download.coupon_code','coupons_download.coupon_id','coupons_download.user_id as creator','coupons_download.created_at as download_date' , 'coupons.*','retailers.business_name','retailers.banner_image','retailers.business_address','retailers.city','retailers.state','retailers.phone_number','retailers.email','retailers.business_description', 'categories.name as category_name')
            ->where('coupons_download.user_id', '=', $user_id)
            ->where('coupons.qr_code', '=', $qr_code)
            ->first();
        return response()->json([
            "data" => $download,
            "message" => "Fetch Successful"
        ]);
    }
    public function update(Request $request, $id){
        if (CouponDownloads::where('id', $id)->exists()) {
            $download = CouponDownloads::findorfail($id);
            $download -> downloads = is_null($request->downloads) ? $download->downloads : $request->downloads;

            $download->save();
        }
        return response()->json([
            "message" => "update successful",
            "data" => $download
        ]);
    }

    public function destroy($id): JsonResponse
    {
        if(CouponDownloads::where('id', $id)->exists()){
            $download = CouponDownloads::find($id);
            $download->delete();


            return response()->json([
                "message" => "Coupon Download Record Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Coupon Download Record Not Found"
            ], 404);
        }
    }
}
