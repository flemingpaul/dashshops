<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;


class ForgotPasswordController extends Controller
{
    //
    public function showForgetPasswordForm()
    {
        return view('auth.forgotPassword');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }
    public function forgetPasswordFormAPI(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response()->json([
            "status" => true,
            "message" => "Rest Password Email Sent Successfully"
        ]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token) {
        return view('auth.forgetPasswordLink', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
    }
    public function resetPasswordFormAPI(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return \response()->json([
                'status' => false,
                "message" => 'Invalid token!']);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response()->json([
            "status" => true,
            "message" => 'Your password has been changed!'
        ]);
    }
    public function resetPasswordFirebase(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);

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

        $user = User::where('phone_number', $phoneNumber)
            ->update(['password' => Hash::make($request->password)]);


        return response()->json([
            "status" => true,
            "message" => 'Your password has been changed!'
        ]);
    }
}
