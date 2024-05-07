<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Validation\Rules\Password;
use DB;
use Carbon\Carbon;
use App\Models\User;
//use Mail;
use PHPMailer;
use Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function ForgetPassword() {
        return view('auth.forget-password');
    }

    public function ForgetPasswordStore(Request $request) {
        $input = $request->all();
        $request->validate([
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'The provided email does not exist in our records.',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $username = User::select('name','email')->where('users.email',$request->email)->first();
        $year = date("Y");
        $str="";
        $str .='Hello '.$username->name.',<br><br>';
        $str .='You asked to reset your password.<br><br>';
        $str .='Please click on the link below to reset your password:<br><br>';
        $str .='<a href="'. url('reset-password' , $token). '"> Reset Your Password.</a><br><br>';
        $str .='Choose a new password and keep it safe.<br /><br><br>';
        $str .='<div class="invoice-logo"><img src="'.url('public/img/logo.png').'" alt=""  height="50" style="height: 50px;"></div><br><br>';
        $str .='© Copyright '. $year.' Education International - All rights reserved.';
        echo $str; exit;
        define('from_name', "Cyblance");
        define('from_email', "info@cyblance.com");

        require (base_path().'/class.phpmailer.php') ;
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "laraveldevelopmentservices.com";
        $mail->SMTPAuth = true;
        //$mail->SMTPSecure = "ssl";
        $mail->Port = 587;
        $mail->Username = "info@laraveldevelopmentservices.com";
        $mail->Password = "Fs]}d$5f41?v";
        $mail->From = constant("from_email");
        $mail->FromName = constant("from_name");

        $mail->AddAddress($username->name);
        $mail->AddAddress("cyblance.nigam@gmail.com");

        $mail->IsHTML(true);
        $mail->Body = $str;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

       if(!$mail->Send()){
            // echo "Message could not be sent. <p>";
            // echo "Mailer Error: " . $mail->ErrorInfo;
            // echo 0;
            // exit;
            return redirect()->back()->with('error', 'Failed to send the password reset email.');
        }else{
            // echo 1;
            // exit;
            return redirect()->back()->with('success', 'Password reset email has been sent successfully.');
        }
    }

    public function ResetPassword($token) {
        return view('auth.forget-password-link', ['token' => $token]);
    }

    public function ResetPasswordStore(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => ['required', 'confirmed', Password::min(6)->mixedCase()->letters()->numbers()->symbols()],
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'The provided email does not exist.',
            'email.regex' => 'The email format is invalid.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least :min characters long.',
            'password.mixed_case' => 'The password must contain both uppercase and lowercase letters.',
            'password.letters' => 'The password must contain at least one letter.',
            'password.numbers' => 'The password must contain at least one number.',
            'password.symbols' => 'The password must contain at least one symbol.',
        ]);


        $update = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();

        if(!$update){
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        // Delete password_resets record
        DB::table('password_resets')->where(['email'=> $request->email])->delete();
        return redirect()->route('login')->with('success', 'Password updated successfully');
    }
}
