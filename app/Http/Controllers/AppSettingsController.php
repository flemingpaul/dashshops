<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\AppSetting;
use App\Models\CouponClicks;
use App\Models\CouponDownloads;
use App\Models\CouponRedeemed;
use http\Env\Response;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function getUserSetting($user_id){
        $app_setting = AppSetting::where('user_id', $user_id)->first();
        if(!$app_setting){
            $app_setting = new AppSetting();
            $app_setting->user_id = $user_id;
            $app_setting->save();
        }
        return response()->json([
            "message" => "App Settings successfully Retrieved",
            "data" => $app_setting
        ]);
    }
    public function saveSettings($user_id,Request $request){
        $app_setting = AppSetting::where('user_id', $user_id)->first();
        if(!$app_setting){
            $app_setting = new AppSetting();
            $app_setting->user_id = $user_id;
        }
        $app_setting->push_notification = is_null($request->push_notification)?$app_setting->push_notification:$request->push_notification;
        $app_setting->disable_caching = is_null($request->disable_caching)?$app_setting->disable_caching:$request->disable_caching;
        $app_setting->location = is_null( $request->location)?$app_setting->location:$request->location;
        $app_setting->save();
        return response()->json([
            "message" => "App Settings successfully Saved",
            "data" => $app_setting
        ]);
    }
}