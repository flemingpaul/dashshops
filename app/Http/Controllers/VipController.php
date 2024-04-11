<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vip;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Nikolag\Square\Facades\Square;
use Nikolag\Square\Models\Customer;

class VipController extends Controller
{
    //
    public function getAll()
    {
        $states = Vip::all();
        return response()->json([
            "message" => "Vip Fetched Successfully",
            "data" => $states
        ]);
    }

    public function getByUserId($id)
    {
        if (Vip::where('user_id', $id)->exists()) {
            $vip = Vip::findorfail($id);
            return response()->json([
                "message" => "Vip Fetch Successful",
                "data" => $vip
            ]);
        } else {
            return response()->json([
                "message" => "Vip not Found"
            ], 404);
        }
    }

    public function isUserVip($id)
    {
        if (Vip::where('user_id', $id)->exists()) {
            $vip = Vip::where('user_id', $id)
                ->where('expiry_date', '>=', date('Y-m-d H:i:s'))
                ->first();
            if ($vip) {
                return response()->json([
                    "message" => "Vip Fetch Successful",
                    "data" => "1"
                ]);
            }
        }
        return response()->json([
            "data" => "0"
        ], 200);
    }

    public function create(Request $request)
    {
       
        $user = User::where('id', $request->user_id);
        $user->update([
            'business_address' => $request->business_address,
            'city'=>$request->city,
            'state'=>$request->state,
            'zip_code'=>$request->zip_code,
            'is_vip'=>1
        ]);
        $user=$user->first();
        

       
        if(Customer::where('email',$user->email)->exists()){
            $customer=Customer::where('email',$user->email)->first();
            Log::info("updating customer === ");
        }else{
            Log::info("creating xxx customer === ");
            $customer = new Customer([
                "first_name" => $user->firstname,
                "last_name" => $user->lastname,
                "email" => $user->email,
                "address" => $user->business_address,
                
            ]);
        
        }
        Log::info("part one===== cc =");
        //Square::setCustomer($customer);
       Log::info(json_encode($customer));

        $transaction = Square::setCustomer($customer)
        ->charge([
            'amount'=>1.99,
            'currency'=>'USD',
            'source_id'=>$request->nonce,
            'location_id'=>env('SQUARE_LOCATION'),
        ]);
         Log::info("part Two=====");

        Log::info("part Three =====");
        if (Vip::where('user_id', $request->user_id)->exists()) {
            $vip = Vip::where('user_id', $request->user_id)->first();
            $vip->expiry_date = is_null($request->expiry_date) ? $vip->expiry_date : $request->expiry_date;

            $vip->save();
        } else {
            $vip = new Vip();
            $vip->user_id = $request->user_id;
            $vip->expiry_date = $request->expiry_date;
            $vip->save();
        }


        return response()->json([
            "message" => "Vip Created",
            "data" => ['vip'=>$vip,'transaction'=>$transaction]
        ], 201);
    }


    public function destroy($id)
    {
        if (Vip::where('id', $id)->exists()) {
            $state = Vip::find($id);
            $state->delete();


            return response()->json([
                "message" => "Vip Deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "Vip Not Found"
            ], 404);
        }
    }
}
