<?php

namespace App\Http\Controllers;

use App\Models\AdClick;
use App\Models\Ads;
use App\Models\CouponClicks;
use App\Models\CouponDownloads;
use App\Models\CouponRedeemed;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdsController extends Controller
{
    //
    public function showAll()
    {
        $ads = DB::table('ads')
            ->select(DB::raw('ads.*, (select count(ad_clicks.id) from ad_clicks where ad_clicks.ad_id = ads.id) as subscribed_clicks'))->get();
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();
        return view('pages.ads', compact(['ads', 'total_redemptions', 'total_downloads', 'total_clicks']));
    }

    public function getAll()
    {
        $ads = Ads::all();

        return response()->json([
            "message" => "Ads successfully Retrieved",
            "date" => $ads
        ]);
    }
    public function getById($id)
    {
        if (Ads::where('id', $id)->exists()) {
            $ad = Ads::find($id);

            return response()->json([
                "message" => "Ad successfully found",
                "data" => $ad
            ]);
        } else {
            return response()->json([
                "message" => "Ad not found"
            ], 404);
        }
    }
    public function update(Request $request)
    {
        if ($request->has("id") && Ads::where('id', $request->id)->exists()) {
            $id = $request->id;
            $ad = Ads::find($id);
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);

                if (file_exists(public_path('images/' . $ad->image))) {
                    unlink(public_path('images/' . $ad->image));
                }
                $ad->image = $imageName;
            }

            $ad->url = is_null($request->url) ? $ad->url : $request->url;
            $ad->start_date = is_null($request->start_date) ? $ad->start_date : date_format(date_create($request->start_date), "Y-m-d 00:00:01");
            $ad->end_date = is_null($request->end_date) ? $ad->end_date : date_format(date_create($request->end_date), "Y-m-d 23:59:59");
            $ad->total_clicks = is_null($request->total_clicks) ? $ad->total_clicks : $request->total_clicks;
            $ad->save();
            return redirect()->back()->withMessage('Ad updated successfully');
        } else {

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'url' => 'required',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date|after:start_date',
                'total_clicks' => 'required|numeric|min:1'
            ]);

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $ad = new Ads;

            $ad->image = $imageName;
            $ad->url = $request->url;
            $ad->start_date = date_format(date_create($request->start_date), "Y-m-d 00:00:01");
            $ad->end_date = date_format(date_create($request->end_date), "Y-m-d 23:59:59");
            $ad->total_clicks = $request->total_clicks;
            $ad->created_by = auth()->user()->id;
            $ad->modified_by = auth()->user()->id;
            $ad->save();

            return redirect()->back()->withMessage('Ad successfully Added');
        }
    }

    public function getAllActive()
    {
        /*
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'retailers.longitude', 'retailers.latitude', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.category_id', '=', $id);
        */
        $ads = DB::table('ads')
            ->select(DB::raw('ads.*, (select count(ad_clicks.id) from ad_clicks where ad_clicks.ad_id = ads.id) as total_ad_clicks'))
            ->where('ads.start_date', '<=', date('Y-m-d'))
            ->where('ads.end_date', '>=', date('Y-m-d'))
            ->where('ads.total_clicks', '>', DB::raw('(select count(ad_clicks.id) from ad_clicks where ad_clicks.ad_id = ads.id)'))
            ->get();

        return response()->json([
            "message" => "Ads successfully Retrieved",
            "data" => $ads
        ]);
    }

    public function recordClick(Request $request)
    {
        $ad = Ads::find($request->ad_id);
        if (!$ad) {
            return response()->json([
                "message" => "Ad not found"
            ]);
        }
        $request->validate([
            'ad_id' => 'required',
            'user_id' => 'required',
        ]);

        AdClick::create([
            'ad_id' => $request->ad_id,
            'user_id' => $request->user_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ]);


        return response()->json([
            "message" => "Click successfully recorded",
            "data" => $ad
        ]);
    }

    /*public function update(Request $request, $id)
    {
        if (!Ads::where('id', $id)->exists()) {
            return redirect()->back()->withMessage('Ad Not found');
        }
        $ad = Ads::find($id);
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $ad->image = $imageName;
        }

        $ad->url = is_null($request->url) ? $ad->url : $request->url;
        $ad->start_date = is_null($request->start_date) ? $ad->start_date : $request->start_date;
        $ad->end_date = is_null($request->end_date) ? $ad->end_date : $request->end_date;
        $ad->total_clicks = is_null($request->total_clicks) ? $ad->total_clicks : $request->total_clicks;
        $ad->save();

        return redirect()->back()->withMessage('Ad update successfully');
    }*/

    public function destroy($id)
    {
        if (Ads::where('id', $id)->exists()) {
            $ad = Ads::findOrFail($id);
            if (file_exists(public_path('images/' . $ad->image))) {
                unlink(public_path('images/' . $ad->image));
            }
            $ad->delete();
            return redirect()->back()->withMessage('Ad Deleted Successfully');
        } else {
            return redirect()->back()->withMessage('Ad not found');
        }
    }
}
