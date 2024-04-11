<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Retailer;
use App\Models\State;
use App\Models\User;
use App\Models\Vip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNull('approved_at')->get();

        return view('pages.users', compact('users'));
    }

    //    public function approve($user_id)
    //    {
    //        $user = User::findOrFail($user_id);
    //        $user->update(['approved_at' => now()]);
    //
    //        return redirect()->route('admin.users.index')->withMessage('User approved successfully');
    //    }
    //
    public function showAddUser()
    {
        $categories = Category::all();
        $user = User::all();
        $states = State::all();
        $total_members = User::where('user_type', '=', 'Consumer')->count();
        $total_app_users = User::where('user_type', '=', 'Consumer')->get();
        $total_sales_users = User::where('user_type', '=', 'Sales User')->get();
        $total_vip = Vip::where('expiry_date', '>=',date('Y-m-d 23:59:59'))->count();
        $total_retailers = Retailer::count();
        return view('pages.addUser', compact(['categories', 'user', 'states', 'total_retailers', 'total_vip', 'total_members', 'total_app_users', 'total_sales_users']));
    }
    public function showAdminPortal()
    {
        $categories = Category::all();
        $user = User::all();
        $states = State::all();
        $total_members = User::where('user_type', '=', 'Consumer')->count();
        $total_vip = Vip::where('expiry_date', '>=',date('Y-m-d 23:59:59'))->count();
        $total_retailers = Retailer::count();
        return view('pages.adminPortal', compact(['categories', 'user', "states", 'total_retailers', 'total_vip', 'total_members']));
    }

    public function showEditUser()
    {

        $categories = Category::all();
        $user = User::all();
        $states = State::all();
        $total_members = User::where('user_type', '=', 'Consumer')->count();
        $total_vip = Vip::where('expiry_date', '>=',date('Y-m-d 23:59:59'))->count();
        $total_retailers = Retailer::count();
        return view('pages.edit_user', compact(['categories', 'user', "states", 'total_retailers', 'total_vip', 'total_members']));
    }

    public function showMembers()
    {
        $users = User::all();
        $states = State::all();
        $state = DB::table('users')
            ->join('states', 'states.id', '=', 'users.state')
            ->select('users.id', 'users.firstname', 'states.name', 'states.abbreviation')
            ->get();
        $total_members = User::where('user_type', '=', 'Consumer')->count();
        $total_app_users = User::where('user_type', '=', 'Consumer')->get();

        $total_vip_users = DB::table('vips')
        ->join('users', 'users.id', '=', 'vips.user_id')
        ->select('users.*', 'vips.expiry_date', 'vips.updated_at as last_subscription')->get();
        $total_sales_users = User::with('state')->where('admin', '=', 2)->get();
        $total_vip = Vip::where('expiry_date', '>=',date('Y-m-d 23:59:59'))->count();
        $total_retailers = Retailer::count();
        return view('pages.members', compact(['users', 'state', 'states', 'total_retailers', 'total_vip', 'total_members', 'total_app_users','total_vip_users', 'total_sales_users']));
    }

    public function getAll()
    {
        $users = User::all();
        return response()->json($users);
    }


    public function create(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed:min:6',
            ]
        );

        if ($validateUser->fails()) {
            //var_dump($validateUser->errors());
            //die();
            return Redirect::back()->withErrors($validateUser)->withInput();
        }
        $user = new User();
        $user->business_name = "";
        $user->business_address = "";
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = "";
        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip_code = $request->zip_code;
        $user->admin = $request->admin;
        if($request->admin == 1){
            $user->user_type = 'Admin';
        }else{
            $user->user_type = 'Sales User';
        }
        $user->password = Hash::make($request->password);
        $user->user_status = 0;
        $user->save();

        $data = ([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            "user_type"=>$user->admin==1?"an admin":"a sales user",
            'password' => $request->password,
            'email' => $request->email,
        ]);

        Mail::send('emails.sales_user', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['firstname']. ' '.$data['lastname'])
                ->cc('info@thedashshop.com')
                ->subject('Welcome to The DashShop')
                ->from('support@thedashshop.com', 'The DashShop');
        });

        return redirect()->back()->withMessage('User Created successfully');
    }

    public function createAPI(Request $request)

    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'business_name' => 'required',
                'business_address' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users,email',
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
        $user = new User();
        $user->business_name = "";
        $user->business_address = "";
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip_code = $request->zip_code;
        $user->password = Hash::make($request->password);
        $user->user_type = $request->user_type;
        $user->user_status = 0;


        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'date' => $user
        ], 200);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        //User::find();
        if ($user) {
            return response()->json([
                "message" => "Fetch Successful",
                "data" => $user
            ],200);
        }else{
            return response()->json([
                "message" => "User Not Found"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->business_name = is_null($request->business_name) ? $user->business_name : $request->business_name;
            $user->business_address = is_null($request->business_address) ? $user->business_address : $request->business_address;
            $user->firstname = is_null($request->firstname) ? $user->firstname : $request->firstname;
            $user->lastname = is_null($request->lastname) ? $user->lastname : $request->lastname;
            $user->email = is_null($request->email) ? $user->email : $request->email;
            $user->city = is_null($request->city) ? $user->city : $request->city;
            $user->state = is_null($request->state) ? $user->state : $request->state;
            $user->zip_code = is_null($request->zip_code) ? $user->zip_code : $request->zip_code;
            $user->phone_number = is_null($request->phone_number) ? $user->phone_number : $request->phone_number;
            $user->password = is_null($request->password) ? $user->password : $request->password;
            $user->user_type = is_null($request->user_type) ? $user->user_type : $request->user_type;
            $user->admin = is_null($request->admin) ? $user->admin : $request->admin;
            $user->user_status = is_null($request->user_status) ? $user->user_status : $request->user_status;
            $user->save();
            return redirect()->back()->withMessage('User Updated Successfully');
        } else {
            return redirect()->back()->withMessage('User not found');
        }
    }
    public function updateAPI(Request $request, $id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->business_name = is_null($request->business_name) ? $user->business_name : $request->business_name;
            $user->business_address = is_null($request->business_address) ? $user->business_address : $request->business_address;
            $user->firstname = is_null($request->firstname) ? $user->firstname : $request->firstname;
            $user->username = is_null($request->username) ? $user->username : $request->username;
            $user->lastname = is_null($request->lastname) ? $user->lastname : $request->lastname;
            $user->email = is_null($request->email) ? $user->email : $request->email;
            $user->city = is_null($request->city) ? $user->city : $request->city;
            $user->state = is_null($request->state) ? $user->state : $request->state;
            $user->zip_code = is_null($request->zip_code) ? $user->zip_code : $request->zip_code;
            $user->phone_number = is_null($request->phone_number) ? $user->phone_number : $request->phone_number;
            $user->password = is_null($request->password) ? $user->password : $request->password;
            $user->user_type = is_null($request->user_type) ? $user->user_type : $request->user_type;
            $user->admin = is_null($request->admin) ? $user->admin : $request->admin;
            $user->user_status = is_null($request->user_status) ? $user->user_status : $request->user_status;
            $user->save();

            return response()->json([
                "message" => "User Updated",
                $user
            ], 201);
        } else {
            return response()->json([
                "message" => "User Not Found"
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->delete();

            echo ("User Record deleted successfully.");
            return redirect()->back()
                ->withMessage('User deleted successfully');
        } else {
            return redirect()->back()->withMessage('User Not Found');
        }
    }
    public function destroyAPI($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->delete();

            return response()->json([
                "message" => "User Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "User Not Found"
            ], 404);
        }
    }
}
