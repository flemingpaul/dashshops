<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponClicks;
use App\Models\User;
use App\Models\CouponDownloads;
use App\Models\CouponRedeemed;
use App\Models\Retailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class CouponController extends Controller
{
    //
    public function showAll(Request $request)
    {
        $category = $request->has('category') ? $request->get('category') : "0";
        $page = $request->has('page') ? $request->get('page') : 1;
        $type = $request->has('type') ? $request->get('type') : "all";
        $offertype = $request->has('offer_type') ? $request->get('offer_type') : "all";
        $search = $request->has('search') ? $request->get('search') : "";
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('users', 'users.retailer_id', '=', 'retailers.id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'users.firstname', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'retailers.zip_code', 'retailers.island', 'retailers.longitude', 'retailers.from_mobile', 'retailers.latitude', 'categories.name as category_name');
        if ($category != "0") {
            $coupons = $coupons->where('coupons.category_id', '=', $category);
        }
        if (Auth::user()->admin == 2) {
            $coupons = $coupons->where('retailers.created_by', '=', Auth::user()->id);
        }
        if ($type != "all") {
            if ($type != "expired") {
                $coupons = $coupons->where('coupons.approval_status', '=', $type);
            } else {
                $coupons = $coupons->where('coupons.end_date', '<', date('Y-m-d 23:59:59'));
            }
        }
        if ($offertype != "all") {
            $coupons = $coupons->where('coupons.offer_type', '=', $offertype);
        }

        $coupons = $coupons->where('retailers.approval_status', 'Approved');
        if ($search != "") {
            $coupons = $coupons->whereNested(function ($q) use ($search) {
                $q->where('coupons.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.island', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');
            });
        }
        $coupons = $coupons->paginate(30, ['*'], 'page', $page);
        //
        $categories = Category::all();
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();
        return view('pages.coupons', compact(['coupons', 'category', 'type', 'offertype', 'search', 'categories', 'total_downloads', 'total_clicks', 'total_redemptions']));
    }
    public function showAddCoupon()
    {
        $categories = Category::all();
        if (Auth::user()->admin == 2) {
            $retailers = Retailer::where('created_by', Auth::user()->id)->get();
        } else {
            $retailers = Retailer::all()->where('approval_status', '=', 'Approved');
        }
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();
        return view('pages.addCoupon', compact(['categories', 'retailers', 'total_downloads', 'total_clicks', 'total_redemptions']));
    }

    public function approve($id, $offer_type)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['approved_at' => now(), 'approval_status' => 'Approved', 'offer_type' => $offer_type]);
        $user = User::where('retailer_id', $coupon->retailer_id)->first();
        $notif = new \App\Http\Controllers\NotificationsController();
        $notif->setNotification(new \App\Models\Notification([
            "user_id" => $user->id,
            "title" => "Coupon Approved",
            "content" => "Your Coupon [" . $coupon->name . "] on The DashShop has been approved",
            "type" => "coupon",
            "source_id" => $coupon->id,
            "has_read" => false,
            "trash" => false
        ]));



        return redirect()->route('coupons')->withMessage('Coupon Approved Successfully');
    }
    public function deny($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['approved_at' => 0, 'approval_status' => 'Denied']);
        $user = User::where('retailer_id', $coupon->retailer_id)->first();
        $notif = new \App\Http\Controllers\NotificationsController();
        $notif->setNotification(new \App\Models\Notification([
            "user_id" => $user->id,
            "title" => "Coupon Denied",
            "content" => "Your Coupon [" . $coupon->name . "] on The DashShop was not approved",
            "type" => "coupon",
            "source_id" => $coupon->id,
            "has_read" => false,
            "trash" => false
        ]));

        return redirect()->route('coupons')->withMessage('Coupon Denied');
    }
    public function index()
    {
        $coupons =  Coupon::all();
        return response()->json([
            "message" => "Successful",
            "data" => $coupons
        ]);
    }

    public function getAll($count, $page, $search = "")
    {
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number','retailers.from_mobile', 'retailers.email', 'retailers.business_description', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.end_date', '>=', date('Y-m-d 23:59:59'))
            ->where('coupons.approval_status', 'Approved')
            ->where('retailers.approval_status', 'Approved');

        if ($search != "") {
            $coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%');
        }
        $coupons = $coupons->paginate($count);

        return response()->json([
            "data" => $coupons
        ], 200);
    }

    public function getAllCouponsByState($state, $city, $catogory = 0, $search = "")
    {
        //        $filtered_coupons = Coupon::select('coupons.*')->join('retailers')

        $filtered_coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.end_date', '>=', date('Y-m-d 23:59:59'))
            ->where('coupons.approval_status', 'Approved')->where('retailers.approval_status', 'Approved');
        if ($catogory != 0) {
            $filtered_coupons = $filtered_coupons->where('coupons.category_id', '=', $catogory);
        }
        if ($search != "") {

            $filtered_coupons = $filtered_coupons->whereNested(function ($q) use ($search) {
                $q->where('coupons.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');
            });
        }
        $filtered_coupons = $filtered_coupons->whereNested(function ($q) use ($city, $state) {
            $q->where('retailers.city', '=', $city)
                ->orWhere('retailers.state', '=', $state);
        })->get();

        return response()->json([
            'Status' => true,
            "message" => "Coupons Retrieved",
            "data" => $filtered_coupons
        ]);
    }

    public function getAllCouponsByStateIsland($island, $state = "-", $city = "-", $catogory = 0, $search = "")
    {
        //        $filtered_coupons = Coupon::select('coupons.*')->join('retailers')

        $filtered_coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.island', 'retailers.business_description', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.end_date', '>=', date('Y-m-d 23:59:59'))
            ->where('coupons.approval_status', 'Approved')->where('retailers.approval_status', 'Approved');
        if ($catogory != 0) {
            $filtered_coupons = $filtered_coupons->where('coupons.category_id', '=', $catogory);
        }
        if ($search != "") {

            $filtered_coupons = $filtered_coupons->whereNested(function ($q) use ($search) {
                $q->where('coupons.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');
            });
        }
        if ($island != "-") {
            $filtered_coupons = $filtered_coupons->where('retailers.island', '=', $island);
        }
        if ($state != "-") {
            $filtered_coupons = $filtered_coupons->where('retailers.state', '=', $state);
        }
        if ($city != "-") {
            $filtered_coupons = $filtered_coupons->where('retailers.city', '=', $city);
        }
        /*$filtered_coupons = $filtered_coupons->whereNested(function ($q) use ($city, $state) {
            $q->where('retailers.city', '=', $city)
                ->orWhere('retailers.state', '=', $state);
        })->get();*/
        $filtered_coupons = $filtered_coupons->get();

        return response()->json([
            'Status' => true,
            "message" => "Coupons Retrieved",
            "data" => $filtered_coupons
        ]);
    }

    public function getCouponOffer($type, $count, $category = 0, $search = "", $city = "", $state = "")
    {
        //Log::info("Type: " . $type . " Category: " . $category . " Search: " . $search . " City: " . $city . " State: " . $state);
        $sun = new \DateTime();
        $sun->modify('next Sunday');
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state','retailers.from_mobile', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.end_date', '>=', date('Y-m-d 00:00:01'))
            ->where('coupons.offer_type', '=', $type);
        if ($category != 0) {
            $coupons = $coupons->where('coupons.category_id', '=', $category);
        }
        if ($city != "") {
            $coupons = $coupons->where('retailers.city', '=', $city);
        }
        if ($state != "") {
            $coupons = $coupons->where('retailers.state', '=', $state);
        }

        if ($search != "") {
            /*$coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');*/
            $coupons = $coupons->whereNested(function ($q) use ($search) {
                $q->orWhere('coupons.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');
            });
        }
        $coupons = $coupons->where('coupons.approval_status', 'Approved');
        $coupons = $coupons->where('retailers.approval_status', 'Approved');

        $coupons = $coupons->get()->take($count);

        return response()->json([
            "data" => $coupons
        ], 200);
    }
    public function getCouponOffer2($type, $count, $category = 0,  $city = "", $state = "", $search = "")
    {
        //Log::info("Type: " . $type . " Category: " . $category . " Search: " . $search . " City: " . $city . " State: " . $state);
        $sun = new \DateTime();
        $sun->modify('next Sunday');
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'retailers.longitude','retailers.from_mobile', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.end_date', '>=', date('Y-m-d 00:00:01'))
            ->where('coupons.offer_type', '=', $type);
        if ($category != 0) {
            $coupons = $coupons->where('coupons.category_id', '=', $category);
        }
        if ($city != "") {
            $coupons = $coupons->where('retailers.city', '=', $city);
        }
        if ($state != "") {
            $coupons = $coupons->where('retailers.state', '=', $state);
        }

        if ($search != "") {
            /*$coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');*/
            $coupons = $coupons->whereNested(function ($q) use ($search) {
                $q->orWhere('coupons.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');
            });
        }
        $coupons = $coupons->where('coupons.approval_status', 'Approved');
        $coupons = $coupons->where('retailers.approval_status', 'Approved');

        $coupons = $coupons->get()->take($count);

        return response()->json([
            "data" => $coupons
        ], 200);
    }

    public function getCouponOffer3($type, $count, $category = 0, $island = "-", $city = "-", $state = "-", $search = "")
    {
        //Log::info("Type: " . $type . " Category: " . $category . " Search: " . $search . " City: " . $city . " State: " . $state);
        $sun = new \DateTime();
        $sun->modify('next Sunday');
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.island', 'retailers.business_description','retailers.from_mobile', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.end_date', '>=', date('Y-m-d 00:00:01'))
            ->where('coupons.offer_type', '=', $type);
        if ($category != 0) {
            $coupons = $coupons->where('coupons.category_id', '=', $category);
        }
        if ($city != "-") {
            $coupons = $coupons->where('retailers.city', '=', $city);
        }
        if ($state != "-") {
            $coupons = $coupons->where('retailers.state', '=', $state);
        }
        if ($island != "-") {
            $coupons = $coupons->where('retailers.island', '=', $island);
        }

        if ($search != "") {
            /*$coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');*/
            $coupons = $coupons->whereNested(function ($q) use ($search) {
                $q->orWhere('coupons.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('coupons.discount_description', 'LIKE', '%' . $search . '%');
            });
        }
        $coupons = $coupons->where('coupons.approval_status', 'Approved');
        $coupons = $coupons->where('retailers.approval_status', 'Approved');

        $coupons = $coupons->get()->take($count);

        return response()->json([
            "data" => $coupons
        ], 200);
    }

    public function getAllCouponsByCategory($id, $type, $count = 30, $page = 1, $search = "")
    {
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description','retailers.from_mobile', 'retailers.longitude', 'retailers.latitude', 'retailers.longitude', 'retailers.latitude', 'categories.name as category_name')
            ->where('coupons.category_id', '=', $id);
        if ($type != "all")
            $coupons = $coupons->where('coupons.approval_status', '=', $type);
        if ($search != "") {
            $coupons = $coupons->where('coupons.name', 'LIKE', '%' . $search . '%');
        }
        $coupons = $coupons->where('retailers.approval_status', 'Approved');
        $coupons = $coupons->get();

        return response()->json([
            "data" => $coupons
        ], 200);
    }

    public function getAllCouponsByZipCode($zip_code)
    {
        $filtered_coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'categories.name as category_name')
            ->where('retailers.zip_code', '=', $zip_code)
            ->where('coupons.approval_status', 'Approved')
            ->where('retailers.approval_status', 'Approved')
            ->get();

        return response()->json([
            'Status' => true,
            "message" => "Coupons Retrieved",
            "data" => $filtered_coupons
        ]);
    }

    public function getApproved()
    {
        $approved_coupons = Coupon::where('approval_status', 'Approved')->get();

        return response()->json([
            "status" => true,
            "message" => "fetch Successful",
            "data" => $approved_coupons
        ]);
    }

    public function store(Request $request)
    {

        $coupon = $this->getCouponWebportal($request);
        $coupon->created_by = Auth::user()->id;
        $coupon->modified_by = Auth::user()->id;


        $coupon['start_date'] = Carbon::createFromFormat('d-m-Y', Carbon::parse($request->start_date)->format('d-m-Y'));
        $coupon['end_date'] = Carbon::createFromFormat('d-m-Y', Carbon::parse($request->end_date)->format('d-m-Y'));

        $coupon->save();

        return redirect()->route('add-coupon')->withMessage('Coupon Created successfully');
    }
    public function storeAPI(Request $request)
    {

        //Log::info("Coupon: " . json_encode($request->all()));
        $coupon = $this->getCoupon1($request);
        $coupon->created_by = $request->created_by;
        $coupon->modified_by = $request->modified_by;


        $coupon['start_date'] = Carbon::createFromFormat('d-m-Y', Carbon::parse($request->start_date)->format('d-m-Y'));
        $coupon['end_date'] = Carbon::createFromFormat('d-m-Y', Carbon::parse($request->end_date)->format('d-m-Y'));

        $coupon->save();

        return response()->json([
            'Status' => true,
            "message" => "Coupon Created",
            "data" => $coupon
        ]);
    }

    public function getCoupon($id)
    {
        $coupon =  Coupon::find($id);

        return response()->json([
            "message" => "Coupon Fetched successfully",
            "data" => $coupon
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        if (Coupon::where('id', $id)->exists()) {
            $coupon = Coupon::find($id);
            $coupon->image = is_null($request->image) ? $coupon->image : $request->image;
            $coupon->name = is_null($request->name) ? $coupon->name : $request->name;
            $coupon->price = is_null($request->price) ? $coupon->price : $request->price;
            $coupon->category_id = is_null($request->category_id) ? $coupon->category_id : $request->category_id;
            $coupon->download_limit = is_null($request->download_limit) ? $coupon->download_limit : $request->download_limit;
            $coupon->retailer_id = is_null($request->retailer_id) ? $coupon->retailer_id : $request->retailer_id;
            $coupon->retail_price = is_null($request->retail_price) ? $coupon->retail_price : $request->retail_price;
            $coupon->discount_percentage = is_null($request->discount_percentage) ? $coupon->discount_percentage : $request->discount_percentage;
            $coupon->discount_now_price = is_null($request->discount_now_price) ? $coupon->discount_now_price : $request->discount_now_price;
            $coupon->start_date = is_null($request->start_date) ? $coupon->start_date : $request->start_date;
            $coupon->end_date = is_null($request->end_date) ? $coupon->end_date : $request->end_date;
            $coupon->qr_code = is_null($request->qr_code) ? $coupon->qr_code : $request->qr_code;
            $coupon->discount_code = is_null($request->discount_code) ? $coupon->discount_code : $request->discount_code;
            $coupon->offer_type = is_null($request->offer_type) ? $coupon->offer_type : $request->offer_type;
            $coupon->approval_status = is_null($request->approval_status) ? $coupon->approval_status : $request->approval_status;
            $coupon->created_by = is_null($request->created_by) ? $coupon->created_by : $request->created_by;
            $coupon->modified_by = is_null($request->modified_by) ? $coupon->modified_by : $request->modified_by;
            $coupon->save();

            return response()->json([
                "message" => "Coupon Updated",
                "data" => $coupon
            ], 201);
        } else {
            return response()->json([
                "message" => "Coupon Not Found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (Coupon::where('id', $id)->exists()) {
            $coupon = Coupon::find($id);
            if ($user->admin == 0) {
                if ($coupon->retailer_id != $user->retailer_id) {
                    return redirect()->back()->withMessage("You are not authorized to perform this action");
                   
                }
            }
            if (file_exists(public_path('images/' . $coupon->image))) {
                unlink(public_path('images/' . $coupon->image));
            }
            $coupon->delete();


            return redirect()->back()->withMessage('Coupon Deleted Successfully');
        } else {
            return redirect()->back()->withMessage("Coupon Not Found");
        }
    }
    public function destroyAPI($id): JsonResponse
    {
        if (Coupon::where('id', $id)->exists()) {
            $coupon = Coupon::find($id);
            if (file_exists(public_path('images/' . $coupon->image))) {
                unlink(public_path('images/' . $coupon->image));
            }
            $coupon->delete();


            return response()->json([
                "message" => "Coupon Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Coupon Not Found"
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return Coupon
     */
    public function getCoupon1(Request $request): Coupon
    {

        if (Coupon::where('id', $request->id)->exists()) {
            $coupon = Coupon::find($request->id);
            $request->validate([
                'name' => 'required',
                'price' => 'required',
                'category_id' => 'required',
                'download_limit' => 'required',
                'retailer_id' => 'required',
                'discount_percentage' => 'required',
                'start_date' => 'required',
            ]);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required',
                'price' => 'required',
                'category_id' => 'required',
                'download_limit' => 'required',
                'retailer_id' => 'required',
                'discount_percentage' => 'required',
                'start_date' => 'required',
            ]);
            $coupon = new Coupon;
        }

        if ($request->has('has_new_image')) {
            if (filter_var($request->has_new_image, FILTER_VALIDATE_BOOLEAN) == true) {

                if (Coupon::where('id', $request->id)->exists()) {
                    $request->validate([
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                    ]);
                    if (file_exists(public_path('images/' . $coupon->image))) {
                        unlink(public_path('images/' . $coupon->image));
                    }
                }
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $coupon->image = $imageName;
            }
        }


        $coupon->name = $request->name;
        $coupon->price = $request->price;
        $coupon->category_id = $request->category_id;
        $coupon->download_limit = $request->download_limit;
        $coupon->retailer_id = $request->retailer_id;
        $coupon->retail_price = $request->retail_price;
        $coupon->discount_percentage = $request->discount_percentage;
        $coupon->discount_now_price = $request->discount_now_price;
        $coupon->discount_description = $request->discount_description;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->qr_code = $request->qr_code;
        $coupon->discount_code = $request->discount_code;
        $coupon->offer_type = $request->offer_type;
        $coupon->approval_status = "NEW";
        return $coupon;
    }
    /**
     * @param Request $request
     * @return Coupon
     */
    public function getCouponWebportal(Request $request): Coupon
    {

        if (Coupon::where('id', $request->id)->exists()) {
            $coupon = Coupon::find($request->id);
            $request->validate([
                'name' => 'required',
                'price' => 'required',
                'category_id' => 'required',
                'download_limit' => 'required',
                'retailer_id' => 'required',
                'discountAmount' => 'required',
                'selectedDiscountType' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required',
                'price' => 'required',
                'category_id' => 'required',
                'download_limit' => 'required',
                'retailer_id' => 'required',
                'discountAmount' => 'required',
                'selectedDiscountType' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
            $coupon = new Coupon;
        }


        if ($request->has('has_new_image')) {
            if (filter_var($request->has_new_image, FILTER_VALIDATE_BOOLEAN) == true) {

                if (Coupon::where('id', $request->id)->exists()) {
                    $request->validate([
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                    ]);
                    if (file_exists(public_path('images/' . $coupon->image))) {
                        unlink(public_path('images/' . $coupon->image));
                    }
                }
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $coupon->image = $imageName;
            }
        }

        $coupon->retail_price = $request->price;
        if ($request->selectedDiscountType == "Discount Percent") {

            $coupon->discount_percentage = $request->selectedDiscountType;
            $coupon->discount_now_price = $request->discountAmount;
        } else {
            $coupon->discount_percentage = $request->discountAmount;
        }

        //calculate actual discount price
        if ($coupon->discount_percentage == "Discount Percent") {
            $coupon->price = $coupon->retail_price - ($coupon->retail_price * (($coupon->discount_now_price ?? 0) / 100));
        } else {
            $coupon->price = $coupon->discount_now_price = 0;
        }


        $coupon->name = $request->name;
        $coupon->category_id = $request->category_id;
        $coupon->download_limit = $request->download_limit;
        $coupon->retailer_id = $request->retailer_id;
        $coupon->discount_description = $request->discount_description;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->qr_code = substr(str_shuffle(str_repeat($c = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890', ceil(14 / strlen($c)))), 1, 14);
        $coupon->discount_code = substr(str_shuffle(str_repeat($c = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890', ceil(8 / strlen($c)))), 1, 8);
        $coupon->offer_type = $request->offer_type;
        $user = \auth()->user();
        if ($user->admin == 1) {
            $coupon->approval_status = "APPROVED";
        } else {
            $coupon->approval_status = "NEW";
        }

        return $coupon;
    }
}
