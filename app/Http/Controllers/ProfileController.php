<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //

    public function update(Request $request,$retailer_id){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
      Retailer::findOrFail($retailer_id)-> update([
          'banner_image' => $imageName,
           'city' => $request->city,
           'state' => $request->state,
            'zip_code' => $request->zip_code,
            'business_hours_open' => $request->business_hours_open_1 && $request->business_hours_open_2,
            'business_hours_close' => $request->business_hours_close_1 && $request->business_hours_close_2,
            'web_url' => $request->web_url,
            'business_description' => $request->business_description,
            'modified_at' => now()
        ]);
        return redirect()->back()->withMessage('Profile Updated Successfully');
    }
}
