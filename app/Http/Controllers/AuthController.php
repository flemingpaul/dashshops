<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponClicks;
use App\Models\CouponDownloads;
use App\Models\CouponRedeemed;
use App\Models\FileUpload;
use App\Models\LoginToken;
use App\Models\Retailer;
use App\Models\State;
use App\Notifications\NewUser;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Vip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Kreait\Laravel\Firebase\Facades\Firebase;



class AuthController extends Controller
{
    //

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index()
    {
        return view('auth.login');
    }

    public function fillProfile()
    {
        $states = State::all();
        //        $user = User::find($id)

        return view('auth.profile', compact(['states']));
    }

    private function strMonthToNumber($month)
    {
        $month = strtolower($month);

        switch ($month) {
            case "january":
                return "01";
                break;
            case "february":
                return "02";
                break;
            case "march":
                return "03";
                break;
            case "april":
                return "04";
                break;
            case "may":
                return "05";
                break;
            case "june":
                return "06";
                break;
            case "july":
                return "07";
                break;
            case "august":
                return "08";
                break;
            case "september":
                return "09";
                break;
            case "october":
                return "10";
                break;
            case "november":
                return "11";
                break;
            case "december":
                return "12";
                break;
            default:
                return date("m");
        }
    }
    public function analyticsDetail(Request $request)
    {
        $user = Auth::user();
        $year = $request->has("year") ? $request->year : Carbon::now()->format('Y');
        $month = $request->has("month") ? $this->strMonthToNumber($request->month) : Carbon::now()->format('m');

        $retailer_id = $request->has("retailer_id") ? $request->retailer_id : 0;

        $coupon_id = $request->has("coupon_id") ? $request->coupon_id : 0;
        $last_day_of_month = date("t", mktime(0, 0, 0, (int)$month, 1, $year));


        $start_date = Carbon::parse("$year-$month-01")->format('Y-m-d 00:00:00');
        $end_date = Carbon::parse("$year-$month-$last_day_of_month")->format('Y-m-d 23:59:59');

        $month = $request->has("month") ? $request->month : Carbon::now()->format('F');

        $coupon_clicks = DB::table('coupons_clicks')
            ->join('coupons', 'coupons.id', '=', 'coupons_clicks.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('users', 'users.retailer_id', '=', 'retailers.id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select(DB::raw('users.firstname,coupons_clicks.created_at as date_created, coupons_clicks.id as analytics_id,(select created_at from coupons_clicks where coupon_id = coupons.id order by created_at desc limit 1) as last_date, (select count(*) from coupons_clicks where coupon_id = coupons.id) as click_count, coupons.*, retailers.business_name, retailers.banner_image, retailers.business_address, retailers.city, retailers.state,retailers.zip_code,retailers.island, retailers.phone_number, retailers.email,retailers.business_description, categories.name as category_name'))
            ->whereBetween('coupons_clicks.created_at', [$start_date, $end_date]);

        if ($retailer_id != 0) {
            $coupon_clicks = $coupon_clicks->where('retailers.id', '=', $retailer_id);
        }
        if (Auth::user()->admin != 1) {
            $coupon_clicks = $coupon_clicks->where('retailers.created_by', '=', Auth::user()->id);
        }
        if ($coupon_id != 0) {
            $coupon_clicks = $coupon_clicks->where('coupons.id', '=', $coupon_id);
        }
        $coupon_clicks = $coupon_clicks->orderBy('coupons_clicks.created_at', 'desc')
            ->get();

        $coupon_downloads = DB::table('coupons_download')
            ->join('coupons', 'coupons.id', '=', 'coupons_download.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('users', 'users.retailer_id', '=', 'retailers.id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select(DB::raw('users.firstname,coupons_download.created_at as date_created,coupons_download.id as analytics_id,(select created_at from coupons_download where coupon_id = coupons.id order by created_at desc limit 1) as last_date, (select count(*) from coupons_download where coupon_id = coupons.id) as download_count, coupons.*, retailers.business_name, retailers.banner_image, retailers.business_address, retailers.city, retailers.state,retailers.zip_code,retailers.island, retailers.phone_number, retailers.email,retailers.business_description, categories.name as category_name'))
            ->whereBetween('coupons_download.created_at', [$start_date, $end_date]);
        $retailer = [];
        $coupon = [];
        if ($retailer_id != 0) {
            $retailer = Retailer::find($retailer_id);
            $coupon_downloads = $coupon_downloads->where('retailers.id', '=', $retailer_id);
        }
        if ($coupon_id != 0) {
            $coupon = Coupon::find($coupon_id);
            $coupon_downloads = $coupon_downloads->where('coupons.id', '=', $coupon_id);
        }
        if (Auth::user()->admin != 1) {
            $coupon_downloads = $coupon_downloads->where('retailers.created_by', '=', Auth::user()->id);
        }
        $coupon_downloads = $coupon_downloads->orderBy('coupons_download.created_at', 'desc')
            ->get();

        $coupon_redeemeds = DB::table('coupon_redemption')
            ->join('coupons', 'coupons.id', '=', 'coupon_redemption.coupon_id')
            ->join('retailers', 'retailers.id', '=', 'coupons.retailer_id')
            ->join('users', 'users.retailer_id', '=', 'retailers.id')
            ->join('categories', 'categories.id', '=', 'coupons.category_id')
            ->select(DB::raw('users.firstname,coupon_redemption.created_at as date_created,coupon_redemption.id as analytics_id,(select created_at from coupon_redemption where coupon_id = coupons.id order by created_at desc limit 1) as last_date, (select count(*) from coupon_redemption where coupon_id = coupons.id) as redeemed_count, coupons.*, retailers.business_name, retailers.banner_image, retailers.business_address, retailers.city, retailers.state,retailers.zip_code,retailers.island, retailers.phone_number, retailers.email,retailers.business_description, categories.name as category_name'))
            ->whereBetween('coupon_redemption.created_at', [$start_date, $end_date]);

        if ($retailer_id != 0) {
            $coupon_redeemeds = $coupon_redeemeds->where('retailers.id', '=', $retailer_id);
        }
        if (Auth::user()->admin != 1) {
            $coupon_redeemeds = $coupon_redeemeds->where('retailers.created_by', '=', Auth::user()->id);
        }
        if ($coupon_id != 0) {
            $coupon_redeemeds = $coupon_redeemeds->where('coupons.id', '=', $coupon_id);
        }
        $coupon_redeemeds = $coupon_redeemeds->orderBy('coupon_redemption.created_at', 'desc')
            ->get();
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();
        if ($user->admin == 1) {
            $retailers = Retailer::where("approval_status", "=", "Approved")->get();
        } else {
            $retailers = Retailer::where("created_by", "=", Auth::user()->id)->get();
        }

        return view(
            'pages.analytics_details',
            compact([
                'year',
                'month',
                'coupon_clicks',
                'coupon_downloads',
                'coupon_redeemeds',
                'retailer_id',
                'coupon_id',
                'total_downloads',
                'total_clicks',
                'total_redemptions',
                'retailer',
                'coupon',
                'retailers'

            ])
        );
    }

    public function analytics(Request $request)
    {
        $year = $request->has("year") ? $request->year : Carbon::now()->format('Y');

        $last_day_feb = date("t", mktime(0, 0, 0, 2, 1, $year));

        $total_retailers = Retailer::count();
        $total_downloads = $this->getTotalDownloads(); // CouponDownloads::sum('Downloads');
        $total_clicks = $this->getTotalClicks(); //CouponClicks::sum('clicks');
        $total_redemptions = $this->getTotalRedemptions(); // CouponRedeemed::count();
        $total_coupons = Coupon::count();
        $total_consumers = User::where('user_type', '=', 'Consumer')->count();
        $user = Auth::user();
        if ($user->admin == 1) {
            $january_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-01-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-01-31")->format('Y-m-d 23:59:59')])->count();
            $february_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-02-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-02-$last_day_feb")->format('Y-m-d 23:59:59')])->count();
            $march_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-03-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-03-31")->format('Y-m-d 23:59:59')])->count();
            $april_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-04-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-04-30")->format('Y-m-d 23:59:59')])->count();
            $may_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-05-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-05-31")->format('Y-m-d 23:59:59')])->count();
            $june_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-06-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-06-30")->format('Y-m-d 23:59:59')])->count();
            $july_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-07-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-07-31")->format('Y-m-d 23:59:59')])->count();
            $august_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-08-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-08-31")->format('Y-m-d 23:59:59')])->count();
            $september_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-09-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-09-30")->format('Y-m-d 23:59:59')])->count();
            $october_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-10-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-10-31")->format('Y-m-d 23:59:59')])->count();
            $november_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-11-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-11-30")->format('Y-m-d 23:59:59')])->count();
            $december_click = CouponClicks::whereBetween('created_at', [Carbon::parse("$year-12-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-12-31")->format('Y-m-d 23:59:59')])->count();

            $january_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-01-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-01-31")->format('Y-m-d 23:59:59')])->count();
            $february_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-02-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-02-$last_day_feb")->format('Y-m-d 23:59:59')])->count();
            $march_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-03-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-03-31")->format('Y-m-d 23:59:59')])->count();
            $april_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-04-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-04-30")->format('Y-m-d 23:59:59')])->count();
            $may_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-05-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-05-31")->format('Y-m-d 23:59:59')])->count();
            $june_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-06-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-06-30")->format('Y-m-d 23:59:59')])->count();
            $july_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-07-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-07-31")->format('Y-m-d 23:59:59')])->count();
            $august_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-08-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-08-31")->format('Y-m-d 23:59:59')])->count();
            $september_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-09-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-09-30")->format('Y-m-d 23:59:59')])->count();
            $october_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-10-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-10-31")->format('Y-m-d 23:59:59')])->count();
            $november_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-11-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-11-30")->format('Y-m-d 23:59:59')])->count();
            $december_download = CouponDownloads::whereBetween('created_at', [Carbon::parse("$year-12-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-12-31")->format('Y-m-d 23:59:59')])->count();

            $january_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-01-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-01-31")->format('Y-m-d 23:59:59')])->count();
            $february_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-02-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-02-$last_day_feb")->format('Y-m-d 23:59:59')])->count();
            $march_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-03-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-03-31")->format('Y-m-d 23:59:59')])->count();
            $april_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-04-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-04-30")->format('Y-m-d 23:59:59')])->count();
            $may_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-05-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-05-31")->format('Y-m-d 23:59:59')])->count();
            $june_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-06-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-06-30")->format('Y-m-d 23:59:59')])->count();
            $july_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-07-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-07-31")->format('Y-m-d 23:59:59')])->count();
            $august_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-08-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-08-31")->format('Y-m-d 23:59:59')])->count();
            $september_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-09-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-09-30")->format('Y-m-d 23:59:59')])->count();
            $october_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-10-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-10-31")->format('Y-m-d 23:59:59')])->count();
            $november_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-11-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-11-30")->format('Y-m-d 23:59:59')])->count();
            $december_redeemed = CouponRedeemed::whereBetween('created_at', [Carbon::parse("$year-12-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-12-31")->format('Y-m-d 23:59:59')])->count();

            $total_click_in_30_days = CouponClicks::whereBetween('created_at', [Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->count();
            $total_download_in_30_days = CouponDownloads::whereBetween('created_at', [Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->count();
            $total_redeemed_in_30_days = CouponRedeemed::whereBetween('created_at', [Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->count();
        } else {
            $january_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-01-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-01-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $february_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-02-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-02-$last_day_feb")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $march_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-03-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-03-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $april_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-04-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-04-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $may_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-05-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-05-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $june_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-06-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-06-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $july_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-07-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-07-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $august_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-08-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-08-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $september_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-09-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-09-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $october_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-10-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-10-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $november_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-11-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-11-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $december_click = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::parse("$year-12-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-12-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();

            $january_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-01-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-01-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $february_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-02-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-02-$last_day_feb")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $march_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-03-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-03-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $april_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-04-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-04-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $may_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-05-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-05-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $june_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-06-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-06-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $july_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-07-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-07-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $august_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-08-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-08-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $september_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-09-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-09-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $october_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-10-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-10-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $november_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-11-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-11-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $december_download = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::parse("$year-12-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-12-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();

            $january_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-01-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-01-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $february_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-02-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-02-$last_day_feb")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $march_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-03-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-03-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $april_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-04-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-04-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $may_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-05-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-05-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $june_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-06-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-06-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $july_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-07-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-07-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $august_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-08-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-08-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $september_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-09-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-09-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $october_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-10-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-10-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $november_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-11-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-11-30")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $december_redeemed = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::parse("$year-12-01")->format('Y-m-d 00:00:00'), Carbon::parse("$year-12-31")->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();

            $total_click_in_30_days = CouponClicks::whereBetween('coupons_clicks.created_at', [Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $total_download_in_30_days = CouponDownloads::whereBetween('coupons_download.created_at', [Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
            $total_redeemed_in_30_days = CouponRedeemed::whereBetween('coupon_redemption.created_at', [Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])->leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
        }

        $total_vip = Vip::where('vips.expiry_date', '>=', date('Y-m-d 23:59:59'))->count();
        $total_banners = Ads::where('end_date', '>=', date('Y-m-d 23:59:59'))->count();

        $january = array(
            'clicks' => $january_click,
            'downloads' => $january_download,
            'redeemed' => $january_redeemed
        );
        $february = array(
            'clicks' => $february_click,
            'downloads' => $february_download,
            'redeemed' => $february_redeemed
        );
        $march = array(
            'clicks' => $march_click,
            'downloads' => $march_download,
            'redeemed' => $march_redeemed
        );
        $april = array(
            'clicks' => $april_click,
            'downloads' => $april_download,
            'redeemed' => $april_redeemed
        );
        $may = array(
            'clicks' => $may_click,
            'downloads' => $may_download,
            'redeemed' => $may_redeemed
        );
        $june = array(
            'clicks' => $june_click,
            'downloads' => $june_download,
            'redeemed' => $june_redeemed
        );
        $july = array(
            'clicks' => $july_click,
            'downloads' => $july_download,
            'redeemed' => $july_redeemed
        );
        $august = array(
            'clicks' => $august_click,
            'downloads' => $august_download,
            'redeemed' => $august_redeemed
        );
        $september = array(
            'clicks' => $september_click,
            'downloads' => $september_download,
            'redeemed' => $september_redeemed
        );
        $october = array(
            'clicks' => $october_click,
            'downloads' => $october_download,
            'redeemed' => $october_redeemed
        );
        $november = array(
            'clicks' => $november_click,
            'downloads' => $november_download,
            'redeemed' => $november_redeemed
        );
        $december = array(
            'clicks' => $december_click,
            'downloads' => $december_download,
            'redeemed' => $december_redeemed
        );

        return view(
            'pages.analytics',
            compact([
                'year',
                'total_retailers',
                'total_consumers',
                'total_banners',
                'total_vip',
                'total_coupons',
                'january',
                'february',
                'march',
                'april',
                'may',
                'june',
                'july',
                'august',
                'september',
                'october',
                'november',
                'december',
                'total_downloads', 'total_clicks', 'total_redemptions',
                'total_click_in_30_days', 'total_download_in_30_days', 'total_redeemed_in_30_days'
            ])
        );
    }


    /**
     * Write code on Method
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application()
     */

    public function registration()

    {
        $categories = Category::all();

        return view('auth.register', compact(['categories']));
    }

    public function approval()
    {
        return view('auth.approval');
    }

    public function denial()
    {
        return view('auth.deny');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */

    public function postLogin(Request $request)

    {

        $request->validate([

            'email' => 'required',

            'password' => 'required',

        ]);


        $credentials = $request->only('email', 'password');

        //var_dump(Session::get('url.intended'));die();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->admin == 0) {
                Auth::logout();
                return redirect("login")->withMessage('Opps! you do not have a profile to access this page. You can login using https://thedashshop.com or The Dash Shop Mobile app.');
            }
            return redirect()->intended('dashboard/analytics')
                ->withSuccess('You have Successfully logged in');
        }


        return redirect("login")->withMessage('Opps! You have entered invalid credentials');
    }

    function deleteAccount(Request $request)
    {
        $auth_user = Auth::user();
        $user = User::find($auth_user->id);
        $user->user_status = 4;
        $user->email = $user->email . "_deleted";
        $user->save();
        if (Retailer::where('id', $user->retailer_id)->exists()) {
            $retailer = Retailer::where('id', $user->retailer_id)->first();
            $retailer->approval_status = "Deleted";
            $retailer->email = $retailer->email . "_deleted";
            $retailer->save();
        }
        Session::flush();

        Auth::logout();
        return redirect()->route('home')->with('success', 'Account Deleted Successfully');
    }

    function deleteAccountAPI(Request $request, $id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->first();
            $user->user_status = 4;
            $user->email = $user->email . "_deleted";
            $user->save();
            if (Retailer::where('id', $user->retailer_id)->exists()) {
                $retailer = Retailer::where('id', $user->retailer_id)->first();
                $retailer->approval_status = "Deleted";
                $retailer->email = $retailer->email . "_deleted";
                $retailer->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'Account Deleted Successfully',
                "data" => $user
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Account does not exist',
            ], 204);
        }
    }


    /**
     * Write code on Method
     *
     * @return response()
     */

    public function postRegistration(Request $request)

    {

        $request->validate([

            'business_name' => 'required',

            'business_address' => 'required',

            'firstname' => 'required',

            'lastname' => 'required',

            'email' => 'required|email|unique:users',

            'phone_number' => 'required',

            'password' => 'required|min:6',

        ]);


        $data = $request->all();

        $check = $this->create($data);

        Mail::send('emails.welcome', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['business_name'])
                ->cc('info@thedashshops.com')
                ->subject('Welcome to The DashShop')
                ->from('support@thedashshop.com', 'The DashShop');
        });


        return redirect("approval")->withSuccess('Great! You have Successfully logged in');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */

    public function dashboard()

    {

        if (Auth::check()) {

            return view('analytics');
        }


        return redirect("login")->withSuccess('Opps! You do not have access');
    }


    /**
     * Write code on Method
     *
     * @return JsonResponse()
     */

    public function create(array $data)
    {

        return Retailer::create([

            'business_name' => $data['business_name'],
            'business_address' => $data['business_address'],
            'business_description' => '',
            'business_hours_open' => '',
            'business_hours_close' => '',
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'type_of_business' => $data['type_of_business'],
            'phone_number' => $data['phone_number'],
            'city' => '',
            'state' => '',
            'zip_code' => '96788',
            'password' => Hash::make($data['password']),
            'created_by' => 1,
            'modified_by' => 1
        ]);
    }

    public function changePassword(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:8|string'
        ]);
        $auth = Auth::user();

        // The passwords match
        if (!Hash::check($request->get('current_password'), $auth->password)) {
            return back()->with('error', "Current Password is Invalid");
        }

        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $user = User::find($auth->id);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with('success', "Password Changed Successfully");
    }

    public function changePasswordAPI(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:8|string'
        ]);
        $auth = Auth::user();

        // The passwords match
        if (!Hash::check($request->get('current_password'), $auth->password)) {
            return back()->with('error', "Current Password is Invalid");
        }

        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $user = User::find($auth->id);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'Password Updated Successfully',
            "data" => $user
        ], 200);
    }


    /**
     * Write code on Method
     *
     * @return response()
     */

    public function logout()
    {

        Session::flush();

        Auth::logout();


        return Redirect('login');
    }

    public function createUser(Request $request)
    {
        try {
            //Validated
            $request->validate(
                [
                    /*'business_name' => 'required',
                    'business_address' => 'required',*/
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'phone_number' => 'required|phone|unique:users,phone_number',
                    'password' => 'required'
                ]
            );



            $user = User::create([
                'business_name' => $request->has('business_name') && $request->business_name != "" ? $request->business_name : "",
                'business_address' => $request->has('business_address') && $request->business_address != "" ? $request->business_address : "",
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone_number' => $request->has('phone_number') && $request->phone_number != "" ? $request->phone_number : "",
                'city' => $request->has('city') && $request->city != "" ? $request->city : "",
                'state' => $request->has('state') && $request->state != "" ? $request->state : "",
                'zip_code' => $request->has('zip_code') && $request->zip_code != "" ? $request->zip_code : "",
                'password' => Hash::make($request->password)
            ]);

            $data = ([
                'business_name' => $request->business_name,
                'business_Address' => $request->business_address,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone' => $request->phone_number
            ]);
            $token = $user->createToken("API TOKEN")->plainTextToken;
            LoginToken::create([
                'user_id' => $user->id,
                'token' => Hash::make($token)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $token,
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateDeviceToken(Request $request)
    {
        $user = $request->user();
        $token = $request->bearerToken();
        Log::info("Bearer token: $token");
        Log::info("user_id :" . $user->id);
        Log::info("Bearer token (Hash): " . Hash::make($token));
        $id = 0;
        if (LoginToken::where(["user_id" => $user->id])->exists()) {
            foreach (LoginToken::where(["user_id" => $user->id])->get() as $stuff) {
                if (Hash::check($token, $stuff->token)) {
                    $id = $stuff->id;
                    break;
                }
            }
            if ($id != 0) {
                $loginToken = LoginToken::find($id);
                $loginToken->device_token = $request->device_token;
                $loginToken->device_type = $request->device_type;
                $loginToken->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Device Token Updated Successfully',
                    'data' => $loginToken
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Token does not exist',
                ], 204);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Token does not exist',
            ], 204);
        }
    }

    public function createRetailerUser(Request $request)
    {
        try {
            $request->validate([
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = time() . '.' . $request->banner_image->extension();
            $request->banner_image->move(public_path('images/retailers'), $imageName);
            //Validated
            $request->validate(
                [
                    'business_name' => 'required',
                    'business_address' => 'required',
                    'type_of_business' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|email|unique:retailers,email',
                    'phone_number' => 'required|phone|unique:retailers,phone_number',
                ]
            );

            /*if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }*/

            $user = User::where(["email"=>$request->email,"phone_number"=>$request->phone_number])->first();
            $password = "";
            if($user){
                $password = $user->password;
            }else{
                $password = Hash::make($request->password);
            }

            $retailer = Retailer::create([
                'banner_image' => $imageName,
                'business_name' => $request->business_name,
                'business_address' => $request->business_address,
                'type_of_business' => $request->type_of_business,
                'email_address' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'type_of_business' => $request->type_of_business,
                'business_description' => $request->business_description,
                'email' => $request->email,
                'business_hours_open' => $request->business_hours_open,
                'business_hours_close' => $request->business_hours_close,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'web_url' => $request->web_url,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'password' => $password,
                'from_mobile' => 1,
                'created_by' => 1,
                'modified_by' => 1
            ]);

            $data = ([
                'business_name' => $request->business_name,
                'business_address' => $request->business_address,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'phone' => $request->phone_number
            ]);

            Mail::send('emails.welcome', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['business_name'])
                    ->cc('info@thedashshop.com')
                    ->subject('Welcome to The DashShop')
                    ->from('support@thedashshop.com', 'The DashShop');
            });


            //            $admin = User::where('admin', 1)->first();
            //            if ($admin) {
            //                $admin->notify(new NewUser($retailer));
            //            }

            return response()->json([
                'status' => true,
                'message' => 'Retailer Created Successfully',
                'token' => $retailer->createToken("API TOKEN")->plainTextToken,
                "data" => $retailer
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function validatePhoneAuth(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'phone_number' => 'required',
                    'token' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }
            $token = $request->input('token');
            $phoneNumber = $request->input('phone_number');
            $defaultAuth = Firebase::auth();
            $user_phone = "";

            $verifiedIdToken = $defaultAuth->verifyIdToken($token);
            $uid = $verifiedIdToken->claims()->get('sub');

            $user = $defaultAuth->getUser($uid);
            $user_phone = $user->phoneNumber;
            //var_dump($user);
            if ($user_phone != $phoneNumber) {
                return response()->json([
                    'status' => false,
                    'message' =>
                    "Phone number does not match token supplied, please resend OTP"
                ], 400);
            }
            if (!User::where('phone_number', $request->phone_number)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User record does not exist in our database, If you signed up as a retailer, kindly wait for the admin\'s approval.',
                ], 400);
            }
            return response()->json([
                'status' => true,
                'message' => 'Phone number has been validated successfully'
            ], 200);
        } catch (FailedToVerifyToken $e) {
            return response()->json([
                'status' => false,
                'message' =>
                $e->getMessage()

            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function verifyPhoneNumber(Request $request)
    {

        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'phone_number' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            if (!User::where('phone_number', $request->phone_number)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User record does not exist in our database, If you signed up as a retailer, kindly wait for the admin\'s approval.',
                ], 400);
            }


            return response()->json([
                'status' => true,
                'message' => 'Phone number exist'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request)
    {

        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'phone_number' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            if (!User::where('phone_number', $request->phone_number)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User record does not exist in our database, If you signed up as a retailer, kindly wait for the admin\'s approval.',
                ], 400);
            }

            if (!Auth::attempt($request->only(['phone_number', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone number & Password does not match with our record.',
                ], 400);
            }

            $user = User::where('phone_number', $request->phone_number)->first();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            $token = $this->strright($token);
            Log::info("logging token: " . $token);
            LoginToken::create([
                'user_id' => $user->id,
                'token' => Hash::make($token)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $token,
                "data" => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The Retailer
     * @param Request $request
     * @return JsonResponse
     */
    public function loginRetailer(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $retailer = Retailer::where('email', $request->email)->first();
            if ($retailer) {
                if ($retailer->approval_status != "Approved") {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your account has not been approved yet.',
                    ], 401);
                }
                if (Hash::check($request->password, $retailer->password)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Retailer Logged In Successfully',
                        'token' => $retailer->createToken("API TOKEN")->plainTextToken,
                        "data" => $retailer
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email & Password does not match with our record.',
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'A retailer account is not associated with the email provided. Kindly created a new account.',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
