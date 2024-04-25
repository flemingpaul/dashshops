<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Category;
use App\Models\CouponClicks;
use App\Models\CouponDownloads;
use App\Models\CouponRedeemed;
use App\Models\Retailer;
use App\Models\State;
use App\Models\User;
use App\Models\Vip;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\Log;


class RetailerController extends Controller
{
    //

    public function dashboard()
    {
        $total_retailers = Retailer::count();
        $total_approved = Retailer::where('approval_status', '=', 'approved')->count();
        $total_vip = Vip::where('expiry_date', '>=', date('Y-m-d H:i:s'))->count();

        return view('retailersDashboard', compact(['total_retailers', 'total_approved', 'total_vip']));
    }

    public function getCities($island)
    {
        $cities = array();
        if ($island == "Oahu") {
            $cities = array("Aiea", "Ewa Beach", "Haleiwa", "Hauula", "Honolulu", "Kaaawa", "Kahuku", "Kailua", "Kaneohe", "Kapolei", "Laie", "Mililani", "Pearl City", "Wahiawa", "Waialua", "Waianae", "Waimanalo", "Waipahu");
        } else if ($island == "Maui") {
            $cities = array("Haiku", "Hana", "Kihei", "Kahului", "Kaunakakai", "Kihei", "Kula", "Lahaina", "Lanai City", "Makawao", "Paia", "Pukalani", "Wailuku");
        } else if ($island == "Kauai") {
            $cities = array("Eleele", "Hanalei", "Kalaheo", "Kapaa", "Kekaha", "Kilauea", "Koloa", "Lihue", "Princeville");
        } else if ($island == "Hawaii") {
            $cities = array("Captain Cook", "Hilo", "Holualoa", "Honokaa", "Kailua Kona", "Kamuela", "Keaau", "Kealakekua", "Kurtistown", "Mountain View", "Naalehu", "Pahoa", "Waikoloa");
        }
        return $cities;
    }

    public function getCitiesApi($island)
    {
        return response()->json([
            "message" => "Cities Retrieved",
            "data" => $this->getCities($island)
        ]);
    }

    public function getIslandFrCityApi($city)
    {
        return response()->json([
            "message" => "Island Retrieved",
            "data" => $this->getIslandFrCity($city)
        ]);
    }

    public function getIslandFrCity($city)
    {
        $island = "";
        $oahu = array("Aiea", "Ewa Beach", "Haleiwa", "Hauula", "Honolulu", "Kaaawa", "Kahuku", "Kailua", "Kaneohe", "Kapolei", "Laie", "Mililani", "Pearl City", "Wahiawa", "Waialua", "Waianae", "Waimanalo", "Waipahu");
        $maui = array("Haiku", "Kihei", "Hana", "Kahului", "Kaunakakai", "Kihei", "Kula", "Lahaina", "Lanai City", "Makawao", "Paia", "Pukalani", "Wailuku");
        $kauai = array("Eleele", "Hanalei", "Kalaheo", "Kapaa", "Kekaha", "Kilauea", "Koloa", "Lihue", "Princeville");
        $hawaii = array("Captain Cook", "Hilo", "Holualoa", "Honokaa", "Kailua Kona", "Kamuela", "Keaau", "Kealakekua", "Kurtistown", "Mountain View", "Naalehu", "Pahoa", "Waikoloa");
        if (array_search($city, $oahu) !== false) {
            $island = "Oahu";
        } else if (array_search($city, $maui) !== false) {
            $island = "Maui";
        } else if (array_search($city, $kauai) !== false) {
            $island = "Kauai";
        } else if (array_search($city, $hawaii) !== false) {
            $island = "Hawaii";
        }
        return $island;
    }

    public function view()
    {
        //$retailers = Retailer::all();
        if (auth()->user()->admin == 1) {
            $created_retailers = Retailer::all();
        } else {
            $created_retailers = Retailer::all()->where('created_by', '=', auth()->user()->id);
        }

        $total_retailers = Retailer::count();
        $total_approved = Retailer::where('approval_status', '=', 'approved')->count();
        $total_vip = Vip::where('expiry_date', '>=', date('Y-m-d H:i:s'))->count();
        return view('pages.retailers', compact(['total_retailers', 'created_retailers', 'total_approved', 'total_vip']));
    }

    public function showAddRetailer()
    {
        $categories = Category::all();
        $states = State::all();
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();

        return view('pages.addRetailer', compact(['categories', 'states', 'total_downloads', 'total_clicks', 'total_redemptions']));
    }

    public function showSingleRetailer($id)
    {
        $total_retailers = Retailer::count();
        $total_approved = Retailer::where('approval_status', '=', 'approved')->count();
        $total_vip = Vip::where('expiry_date', '>=', date('Y-m-d H:i:s'))->count();
        $retailer = Retailer::findorFail($id);
        $category = DB::table('retailers')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select('retailers.id', 'retailers.business_name', 'categories.name', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.zip_code', 'retailers.web_url',)
            ->where('retailers.id', '=', $retailer)
            ->get();
        //        $user = DB::
        return view('pages.singleRetailer', compact(['retailer', 'category', 'total_retailers', 'total_approved', 'total_vip']));
    }

    public function approve($retailer_id)
    {
        $retailer = Retailer::findOrFail($retailer_id);
        $retailer->update(['approved_at' => new \DateTime(), 'approval_status' => 'Approved']);
        $user = User::where('email', $retailer->email)->first();
        if ($user) {
            $user->update([
                'user_type' => 'Retailer',
                /*'city' => $retailer->city,
                'state' => $retailer->state,
                'zip_code' => $retailer->zip_code,*/
                'phone_number' => $retailer->phone_number,
                'retailer_id' => $retailer->id,
                'business_name' => $retailer->business_name,
                'business_address' => $retailer->business_address,
                'password' => $retailer->password
            ]);
        } else {
            $user = User::create([
                'retailer_id' => $retailer->id,
                /*'business_name' => $retailer->business_name,
                'business_address' => $retailer->business_address,*/
                'firstname' => $retailer->firstname,
                'username' => '',
                'lastname' => $retailer->lastname,
                'email' => $retailer->email,
                'phone_number' => $retailer->phone_number,
                /*'city' => $retailer->city,
                'state' => $retailer->state,
                'zip_code' => $retailer->zip_code,*/
                'user_type' => 'Retailer',
                'password' => $retailer->password
            ]);
        }
        if ($retailer->from_mobile == 1) {
            $retailer->update(['created_by' => $user->id, 'modified_by' => $user->id]);
        }




        $data = ([
            'business_name' => $retailer->business_name,
            'Business_Address' => $retailer->business_address,
            'email' => $retailer->email,
            'phone' => $retailer->phone_number
        ]);


        Mail::send('emails.approved', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['business_name'])
                ->cc('info@thedashshops.com')
                ->subject('Account Approved')
                ->from('support@thedashshop.com', 'The DashShop');
        });

        return redirect()->route('retailers')->withMessage('Retailer approved successfully');
    }

    public function deny($retailer_id)
    {
        $user = Retailer::findOrFail($retailer_id);
        $user->update(['approved_at' => 0, 'approval_status' => 'Denied']);

        return redirect()->route('retailers')->withMessage('Retailer Denied');
    }


    public function index()
    {
        return Retailer::all();
    }

    public function postCreation(Request $request)

    {

        $request->validate([

            'business_name' => 'required',

            'business_address' => 'required',

            'type_of_business' => 'required',

            'city' => 'required',

            'state' => 'required',

            'zip_code' => 'required',

            'phone_number' => 'required',

            'web_url' => 'required',
            'password' => 'required',

            'image' => '|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);


        $data = $request->all();

        $check = $this->create($data);


        return redirect("retailers")->withSuccess('Retailer successfully Created');
    }

    public function storeAPI(Request $request)
    {
        $retailer = new Retailer;
        $retailer->business_name = $request->business_name;
        $retailer->business_address = $request->business_address;
        $retailer->business_description = $request->business_description;
        $retailer->firstname = $request->firstname;
        $retailer->lastname = $request->lastname;
        $retailer->phone_number = $request->phone_number;
        $retailer->email = $request->email;
        $retailer->type_of_business = $request->type_of_business;
        $retailer->business_hours_open = $request->business_hours_open;
        $retailer->business_hours_close = $request->business_hours_open;
        $retailer->city = $request->city;
        $retailer->state = $request->state;
        $retailer->zip_code = $request->zip_code;
        $retailer->web_url = $request->web_url;
        $retailer->banner_image = "";
        $retailer->approval_status = 'New User';
        $retailer->password = Hash::make($request->password);
        $retailer->save();

        return response()->json([
            "message" => "Retailer Created",
            "data" => $retailer
        ], 201);
    }

    public function store(Request $request)
    {

        $request->validate(
            [
                'business_name' => 'required|unique:retailers,business_name',
                'business_address' => 'required',
                'firstname' => 'required',
                'username' => 'required',
                'lastname' => 'required',
                'open_day' => 'required',
                'open_time' => 'required',
                'closed_time' => 'required',
                'closed_day' => 'required',
                'type_of_business' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
                'island' => 'required',
                'email' => 'required|email|unique:retailers,email',
                'password' => 'required',
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]
        );


        $imageName = time() . '.' . $request->banner_image->extension();
        $request->banner_image->move(public_path('images'), $imageName);
        $retailer = new Retailer;
        $retailer->business_name = $request->business_name;
        $retailer->business_address = $request->business_address;
        $retailer->business_description = $request->business_description;
        $retailer->firstname = $request->firstname;
        $retailer->lastname = $request->lastname;
        $retailer->email = $request->email;
        $retailer->phone_number = $request->phone_number;
        $retailer->type_of_business = $request->type_of_business;
        $retailer->business_hours_open = "{$request->open_day}:{$request->open_time}";
        $retailer->business_hours_close = "{$request->closed_day}:{$request->closed_time}";;
        $retailer->city = $request->city;
        $retailer->state = $request->state;
        $retailer->zip_code = $request->zip_code;
        $retailer->web_url = $request->web_url;
        $retailer->banner_image = $imageName;
        $retailer->island = $this->getIslandFrCity($request->city);
        $retailer->approval_status = "New";
        $retailer->password = Hash::make($request->password);
        $retailer->created_by = auth()->user()->id;
        $retailer->modified_by = auth()->user()->id;
        $retailer->save();


        $data = ([
            'business_name' => $request->business_name,
            'business_address' => $request->business_address,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'phone' => $request->phone_number
        ]);
        try {
            Mail::send('emails.welcome', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['business_name'])
                    ->cc('info@thedashshop.com')
                    ->subject('Welcome to The DashShop')
                    ->from('support@thedashshop.com', 'The DashShop');
            });
        } catch (\Exception $e) {
        }


        return redirect()->back()
            ->withMessage('Retailer Created successfully');
    }

    public function getPopularRetailers($count = 10, $page = 1, $category = 0, $city = "", $state = "", $search = "")
    {
        $retailers = DB::table('coupons_clicks')
            ->join('coupons', 'coupons.id', '=', 'coupons_clicks.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select(DB::raw('count(coupons_clicks.coupon_id) as click_count, retailers.*, categories.name as category_name'));

        if ($category != 0) {
            $retailers = $retailers->where('retailers.type_of_business', '=', $category);
        }

        if ($search != "") {
            /*$retailers = $retailers->where('retailers.business_name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', '%' . $search . '%');*/

            $retailers = $retailers->whereNested(function ($q) use ($search) {
                $q->orWhere('retailers.business_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $search . '%');
            });
        }
        if ($city != "") {
            $retailers = $retailers->where('retailers.city', $city);
        }
        if ($state != "") {
            $retailers = $retailers->where('retailers.state', $state);
        }

        $retailers = $retailers->where('retailers.approval_status', 'Approved');

        $retailers = $retailers->groupBy('retailers.id', 'retailers.business_name', 'retailers.business_address', 'retailers.business_description', 'retailers.firstname', 'retailers.lastname', 'retailers.phone_number', 'retailers.email', 'retailers.zip_code', 'retailers.city', 'retailers.state', 'retailers.type_of_business', 'retailers.business_hours_open', 'retailers.business_hours_close', 'retailers.web_url', 'retailers.banner_image', 'retailers.password', 'retailers.island', 'retailers.approval_status', 'retailers.approved_at', 'retailers.created_at', 'retailers.updated_at', 'retailers.created_by', 'retailers.modified_by', 'retailers.longitude', 'retailers.latitude', 'retailers.from_mobile', 'categories.name')
            ->orderBy('click_count', 'desc')
            ->get()
            ->take($count);

        return response()->json([
            "data" => $retailers
        ], 200);
    }

    public function getPopularRetailersByIsland($count = 10, $page = 1, $category = 0, $island = "-", $city = "-", $state = "-", $search = "")
    {
        $retailers = DB::table('coupons_clicks')
            ->join('coupons', 'coupons.id', '=', 'coupons_clicks.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select(DB::raw('count(coupons_clicks.coupon_id) as click_count, retailers.*, categories.name as category_name'));

        if ($category != 0) {
            $retailers = $retailers->where('retailers.type_of_business', '=', $category);
        }

        if ($search != "") {
            /*$retailers = $retailers->where('retailers.business_name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', '%' . $search . '%');*/

            $retailers = $retailers->whereNested(function ($q) use ($search) {
                $q->orWhere('retailers.business_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                    /* ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')*/
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $search . '%');
            });
        }
        if ($city != "-") {
            $retailers = $retailers->where('retailers.city', $city);
        }
        if ($state != "-") {
            $retailers = $retailers->where('retailers.state', $state);
        }
        if ($island != "-") {
            $retailers = $retailers->where('retailers.island', $island);
        }

        $retailers = $retailers->where('retailers.approval_status', 'Approved');

        $retailers = $retailers->groupBy('retailers.id', 'retailers.business_name', 'retailers.business_address', 'retailers.business_description', 'retailers.firstname', 'retailers.lastname', 'retailers.phone_number', 'retailers.email', 'retailers.zip_code', 'retailers.city', 'retailers.state', 'retailers.type_of_business', 'retailers.business_hours_open', 'retailers.business_hours_close', 'retailers.web_url', 'retailers.banner_image', 'retailers.password', 'retailers.island', 'retailers.approval_status', 'retailers.approved_at', 'retailers.created_at', 'retailers.updated_at', 'retailers.created_by', 'retailers.modified_by', 'retailers.longitude', 'retailers.latitude', 'retailers.from_mobile', 'categories.name')
            ->orderBy('click_count', 'desc')
            ->get()
            ->take($count);

        return response()->json([
            "data" => $retailers
        ], 200);
    }

    function getAnalyticsSummary($retail_id)
    {
        $clicks = DB::table('coupons_clicks')
            ->join('coupons', 'coupons.id', '=', 'coupons_clicks.coupon_id')
            ->select(DB::raw('count(coupons_clicks.id) as clicks'))
            ->where('coupons.retailer_id', '=', $retail_id)->first();

        $downloads = DB::table('coupons_download')
            ->join('coupons', 'coupons.id', '=', 'coupons_download.coupon_id')
            ->select(DB::raw('count(coupons_download.id) as downloads'))
            ->where('coupons.retailer_id', '=', $retail_id)->first();

        $redeems = DB::table('coupon_redemption')
            ->join('coupons', 'coupons.id', '=', 'coupon_redemption.coupon_id')
            ->select(DB::raw('count(coupon_redemption.id) as redeems'))
            ->where('coupons.retailer_id', '=', $retail_id)->first();

        return response()->json([
            "data" => [
                "clicks" => $clicks->clicks,
                "downloads" => $downloads->downloads,
                "redeems" => $redeems->redeems
            ]
        ], 200);
    }


    public function getRetailers($count, $page,  $category = 0, $search = "")
    {
        $retailers = DB::table('retailers')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select('retailers.*', 'categories.name as category_name');

        if ($category != 0) {
            $retailers = $retailers->where('retailers.type_of_business', '=', $category);
        }

        if ($search != "") {
            /*$retailers = $retailers->where('retailers.business_name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', '%' . $search . '%');*/

            $retailers = $retailers->whereNested(function ($q) use ($search) {
                $q->orWhere('retailers.business_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $search . '%');
            });
        }
        $retailers = $retailers->where('retailers.approval_status', 'Approved');
        $retailers = $retailers->paginate($count);


        return response()->json([
            "data" => $retailers
        ], 200);
    }

    public function getRetailersByLocation($count, $page,  $category = 0, $city = "", $state = "", $search = "")
    {
        $retailers = DB::table('retailers')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select('retailers.*', 'categories.name as category_name');

        if ($category != 0) {
            $retailers = $retailers->where('retailers.type_of_business', '=', $category);
        }

        if ($search != "") {
            /*$retailers = $retailers->where('retailers.business_name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', '%' . $search . '%');*/

            $retailers = $retailers->whereNested(function ($q) use ($search) {
                $q->orWhere('retailers.business_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $search . '%');
            });
        }
        if ($city != "") {
            $retailers = $retailers->where('retailers.city', $city);
        }
        if ($state != "") {
            $retailers = $retailers->where('retailers.state', $state);
        }
        $retailers = $retailers->where('retailers.approval_status', 'Approved');
        $retailers = $retailers->paginate($count);


        return response()->json([
            "data" => $retailers
        ], 200);
    }

    public function getRetailersByLocationIsland($count, $page,  $category = 0, $island = "-", $city = "-", $state = "-", $search = "")
    {
        $retailers = DB::table('retailers')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
            ->select('retailers.*', 'categories.name as category_name');

        if ($category != 0) {
            $retailers = $retailers->where('retailers.type_of_business', '=', $category);
        }

        if ($search != "") {
            /*$retailers = $retailers->where('retailers.business_name', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', '%' . $search . '%');*/

            $retailers = $retailers->whereNested(function ($q) use ($search) {
                $q->orWhere('retailers.business_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_address', 'LIKE', '%' . $search . '%')
                    /*->orWhere('retailers.city', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.state', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.zip_code', 'LIKE', '%' . $search . '%')*/
                    ->orWhere('retailers.web_url', 'LIKE', '%' . $search . '%')
                    ->orWhere('retailers.business_description', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $search . '%');
            });
        }
        if ($city != "-") {
            $retailers = $retailers->where('retailers.city', $city);
        }
        if ($state != "-") {
            $retailers = $retailers->where('retailers.state', $state);
        }
        if ($island != "-") {
            $retailers = $retailers->where('retailers.island', $state);
        }
        $retailers = $retailers->where('retailers.approval_status', 'Approved');
        $retailers = $retailers->paginate($count);


        return response()->json([
            "data" => $retailers
        ], 200);
    }

    public function getCoupons($id, $type, $search = "")
    {

        $retailer = Retailer::find($id);
        $coupons = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select('coupons.*', 'retailers.business_name', 'retailers.banner_image', 'retailers.business_address', 'retailers.city', 'retailers.state', 'retailers.phone_number', 'retailers.email', 'retailers.business_description', 'retailers.longitude', 'retailers.latitude', 'retailers.from_mobile', 'retailers.from_mobile', 'categories.name as category_name')
            ->where('coupons.retailer_id', '=', $retailer->id);
        if ($type != "all")
            $coupons = $coupons->where('coupons.approval_status', '=', $type);
        if ($search != "")
            $coupons = $coupons->where('coupons.title', 'LIKE', '%' . $search . '%');
        $coupons = $coupons->get();

        return response()->json([
            "data" => $coupons
        ], 200);
    }

    public function getAllAvailableStates(Request $request)
    {
        $coupon_states = DB::table('coupons')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('states', 'states.name', '=', 'retailers.state')
            ->select("states.*")
            ->where("coupons.approval_status", "=", "APPROVED")
            ->where("retailers.approval_status", "=", "APPROVED")
            ->where('coupons.end_date', '>', date('Y-m-d 00:00:01'))
            ->groupBy("states.id")->get()->toArray();

        $product_state = DB::table('product_variation')
            ->join('products', 'products.id', '=', 'product_variation.product_id')
            ->join('retailers', 'retailers.id', '=', 'products.store_id')
            ->join('states','states.name','=','retailers.state')
            ->select("states.*")
            ->where("products.status", "=", 1)
            ->where("product_variation.quantity", ">", 0)
            ->where("retailers.approval_status", "=", "APPROVED")
            ->groupBy("states.id")->get()->toArray();

        $states = $this->uniqueArray(array_merge($product_state, $coupon_states),"id");
        sort($states);
        return response()->json([
            "data" => $states
        ], 200);
    }

    public function getProducts($id, $type, $search = "")
    {

        $retailer = Retailer::find($id);
        $products = DB::table('product_variation')
            ->join('products', 'products.id', '=', 'product_variation.product_id')
            ->join('retailers', 'retailers.id', '=', 'products.store_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select($this->getSelectDBRawProducts())
            ->where('products.store_id', '=', $retailer->id);
        if ($type != "all")
            $products = $products->where('products.status', '=', $type);
        if ($search != "")
            $products = $products->where('products.product_name', 'LIKE', '%' . $search . '%');

        $products = $products->groupBy('products.id')
            ->groupBy("products.id")
            ->orderBy("products.product_name");
        $products = $products->get();

        return response()->json([
            "data" => $products
        ], 200);
    }

    public function show($id): JsonResponse
    {
        if (Retailer::where('id', $id)->exists()) {
            //$retailer = Retailer::find($id);
            $retailer = DB::table('retailers')
            ->join('categories', 'categories.id', '=', 'retailers.type_of_business')
                ->select("retailers.*","categories.name as category_name")
                ->where("retailers.id",$id)
                ->first();
            return response()->json([
                "message" => "Retailer Found",
                "data" => $retailer
            ], 201);
        } else {
            return response()->json([
                "message" => "Retailer Not Found",
                "data" => []
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {

        Log::info($request->all());

        if (Retailer::where('id', $id)->exists()) {
            $retailer = Retailer::findOrFail($id);
            Log::Info("it is to update");


            //            $file = $request->file('image');
            //            $name = Str::random(10);
            //            $url = Storage::putFileAs('images', $file, $name . '.' . $file->extension());

            //            $imageName = time().'.'.$request->banner_image->extension();
            //            $request->banner_image->move(public_path('images'), $imageName);

            $retailer->business_name = is_null($request->business_name) ? $retailer->business_name : $request->business_name;
            $retailer->business_address = is_null($request->business_address) ? $retailer->business_address : $request->business_address;
            $retailer->business_description = is_null($request->business_description) ? $retailer->business_description : $request->business_description;
            $retailer->firstname = is_null($request->firstname) ? $retailer->firstname : $request->firstname;
            $retailer->lastname = is_null($request->lastname) ? $retailer->lastname : $request->lastname;
            $retailer->email = is_null($request->email) ? $retailer->email : $request->email;
            $retailer->type_of_business = is_null($request->type_of_business) ? $retailer->type_of_business : $request->type_of_business;
            $retailer->city = is_null($request->city) ? $retailer->city : $request->city;
            $retailer->state = is_null($request->state) ? $retailer->state : $request->state;
            $retailer->island = is_null($request->island) ? $retailer->island : $request->island;
            $retailer->zip_code = is_null($request->zip_code) ? $retailer->zip_code : $request->zip_code;
            $retailer->web_url = is_null($request->web_url) ? $retailer->web_url : $request->web_url;
            $retailer->business_hours_open = is_null($request->business_hours_open) ? $retailer->business_hours_open : $request->business_hours_open;
            $retailer->business_hours_close = is_null($request->business_hours_close) ? $retailer->business_hours_close : $request->business_hours_close;
            $retailer->latitude = is_null($request->latitude) ? $retailer->latitude : $request->latitude;
            $retailer->longitude = is_null($request->longitude) ? $retailer->longitude : $request->longitude;

            $retailer->approval_status = is_null($request->approval_status) ? $retailer->approval_status : $request->approval_status;
            $retailer->password = is_null($request->password) ? $retailer->password : $request->password;
            $retailer->save();

            return response()->json([
                "message" => "Retailer Updated",
                "data" => $retailer
            ], 201);
        } else {
            return response()->json([
                "message" => "Retailer Not Found"
            ], 404);
        }
    }

    public function updateLogo(Request $request, Retailer $id)
    {


        // Validate the image upload
        $request->validate([
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->banner_image->extension();
        $request->banner_image->move(public_path('images'), $imageName);
        // Update the retailer's logo image

        Retailer::find($id)->update([
            'banner_image' => $imageName
        ]);

        return redirect()->route('retailer')->with('success', 'Retailer logo updated successfully');
    }

    public function updateBanner(Request $request)
    {

        if (Retailer::where('id', $request->id)->exists()) {
            // Validate the image upload
            $request->validate([
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $retailer = Retailer::find($request->id);
            if (file_exists(public_path('images/' . $retailer->banner_image))) {
                unlink(public_path('images/' . $retailer->banner_image));
            }
            $imageName = time() . '.' . $request->banner_image->extension();
            $request->banner_image->move(public_path('images'), $imageName);
            // Update the retailer's logo image
            $retailer->banner_image = $imageName;
            $retailer->save();

            return response()->json([
                "message" => "Retailer Updated",
                "data" => $imageName,
            ], 201);
        } else {
            return response()->json([
                "message" => "Retailer Not Found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (Retailer::where('id', $id)->exists()) {
            $retailer = Retailer::find($id);
            $retailer->delete();

            echo ("Retailer deleted successfully.");
            return redirect()->back()
                ->withMessage('Retailer deleted successfully');
        } else {
            return redirect()->back()->withMessage('Retailer not Found');
        }
    }
    public function destroyAPI($id): JsonResponse
    {
        if (Retailer::where('id', $id)->exists()) {
            $retailer = Retailer::find($id);
            $retailer->delete();


            return response()->json([
                "message" => "Retailer Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Retailer Not Found"
            ], 404);
        }
    }
}
