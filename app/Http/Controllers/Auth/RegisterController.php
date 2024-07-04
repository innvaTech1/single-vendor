<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Rules\Captcha;
use Auth;
use App\Mail\UserRegistration;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\TwilioSms;
use App\Models\Setting;
use App\Models\BiztechSms;

use Str;
use Exception;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class RegisterController extends Controller
{

    use RegistersUsers;


    protected $redirectTo = RouteServiceProvider::HOME;

    public function storeRegister(Request $request)
    {
        $rules = [
            'name' => 'required',
            'password' => 'required|min:4|confirmed'
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password must be 4 characters'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),

        ];
        $this->validate($request, $rules, $customMessages);

        $login = $request->username;

        $field = null;
        if (is_numeric($login)) {
            $field = 'phone';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }



        $check = User::where($field, $login)->first();

        if ($check) {
            return redirect()->back()->with([
                'messege' => 'User already exists',
                'alert-type' => 'error'
            ]);
        }

        // create user
        $user = User::create([
            'name' => $request->name,
            $field => $request->username,
            'password' => Hash::make($request->password),
            'agree_policy' => 1,
            'email_verified_at' => now(),
            'email_verified' => 1,
        ]);


        // MailHelper::setMailConfig();

        // $template=EmailTemplate::where('id',4)->first();
        // $subject=$template->subject;
        // $message=$template->description;
        // $message = str_replace('{{user_name}}',$request->name,$message);
        // Mail::to($user->email)->send(new UserRegistration($message,$subject,$user));

        // if($enable_phone_required == 1){
        //     $template=SmsTemplate::where('id',1)->first();
        //     $message=$template->description;
        //     $message = str_replace('{{user_name}}',$user->name,$message);
        //     $message = str_replace('{{otp_code}}',$user->verify_token,$message);

        //     $twilio = TwilioSms::first();
        //     if($twilio->enable_register_sms == 1){
        //         try{
        //             $account_sid = $twilio->account_sid;
        //             $auth_token = $twilio->auth_token;
        //             $twilio_number = $twilio->twilio_phone_number;
        //             $recipients = $user->phone;
        //             $client = new Client($account_sid, $auth_token);
        //             $client->messages->create($recipients,
        //                     ['from' => $twilio_number, 'body' => $message] );
        //         }catch(Exception $ex){

        //         }
        //     }


        //     $biztech = BiztechSms::first();
        //     if($biztech->enable_register_sms == 1){
        //         if($user->phone){
        //             try{
        //                 $apikey = $biztech->api_key;
        //                 $clientid = $biztech->client_id;
        //                 $senderid = $biztech->sender_id;
        //                 $senderid = urlencode($senderid);
        //                 $message = $message;
        //                 $msg_type = true;  // true or false for unicode message
        //                 $message  = urlencode($message);
        //                 $mobilenumbers = $user->phone; //8801700000000 or 8801700000000,9100000000
        //                 $url = "https://api.smsq.global/api/v2/SendSMS?ApiKey=$apikey&ClientId=$clientid&SenderId=$senderid&Message=$message&MobileNumbers=$mobilenumbers&Is_Unicode=$msg_type";
        //                 $ch = curl_init();
        //                 curl_setopt ($ch, CURLOPT_URL, $url);
        //                 curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //                 curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //                 curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //                 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        //                 curl_setopt($ch, CURLOPT_NOBODY, false);
        //                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //                 $response = curl_exec($ch);
        //                 $response = json_decode($response);
        //             }catch(Exception $ex){}
        //         }
        //     }
        // }



        $notification = trans('user_validation.Register Successfully');
        return redirect()->back()->with(['messege' => $notification,'alert-type'=>'success']);
    }

    public function resendRegisterCode(Request $request)
    {
        $setting = Setting::first();
        $enable_phone_required = $setting->phone_number_required;

        $rules = [
            'email' => 'required',
            'phone' => $enable_phone_required == 1 ? 'required' : '',
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'phone.required' => trans('user_validation.Phone number is required'),
            'phone.unique' => trans('user_validation.Phone number already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->email_verified == 0) {
                MailHelper::setMailConfig();

                $template = EmailTemplate::where('id', 4)->first();
                $subject = $template->subject;
                $message = $template->description;
                $message = str_replace('{{user_name}}', $user->name, $message);
                Mail::to($user->email)->send(new UserRegistration($message, $subject, $user));

                if ($enable_phone_required == 1) {
                    $template = SmsTemplate::where('id', 1)->first();
                    $message = $template->description;
                    $message = str_replace('{{user_name}}', $user->name, $message);
                    $message = str_replace('{{otp_code}}', $user->verify_token, $message);

                    $twilio = TwilioSms::first();
                    if ($twilio->enable_register_sms == 1) {
                        try {
                            $account_sid = $twilio->account_sid;
                            $auth_token = $twilio->auth_token;
                            $twilio_number = $twilio->twilio_phone_number;
                            $recipients = $user->phone;
                            $client = new Client($account_sid, $auth_token);
                            $client->messages->create(
                                $recipients,
                                ['from' => $twilio_number, 'body' => $message]
                            );
                        } catch (Exception $ex) {
                        }
                    }

                    $biztech = BiztechSms::first();
                    if ($biztech->enable_register_sms == 1) {
                        if ($user->phone) {
                            try {
                                $apikey = $biztech->api_key;
                                $clientid = $biztech->client_id;
                                $senderid = $biztech->sender_id;
                                $senderid = urlencode($senderid);
                                $message = $message;
                                $msg_type = true;  // true or false for unicode message
                                $message  = urlencode($message);
                                $mobilenumbers = $user->phone; //8801700000000 or 8801700000000,9100000000
                                $url = "https://api.smsq.global/api/v2/SendSMS?ApiKey=$apikey&ClientId=$clientid&SenderId=$senderid&Message=$message&MobileNumbers=$mobilenumbers&Is_Unicode=$msg_type";
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_NOBODY, false);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                $response = curl_exec($ch);
                                $response = json_decode($response);
                            } catch (Exception $ex) {
                            }
                        }
                    }
                }

                $notification = trans('user_validation.Register Successfully. Please Verify your email');
                return response()->json(['notification' => $notification]);
            } else {
                $notification = trans('user_validation.Already verfied your account');
                return response()->json(['notification' => $notification], 402);
            }
        } else {
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['notification' => $notification], 402);
        }
    }


    public function userVerification($token)
    {
        $user = User::where('verify_token', $token)->first();
        if ($user) {
            $user->verify_token = null;
            $user->status = 1;
            $user->email_verified = 1;
            $user->save();
            $notification = trans('user_validation.Verification Successfully');
            return response()->json(['notification' => $notification], 200);
        } else {
            $notification = trans('user_validation.Invalid token');
            return response()->json(['notification' => $notification], 400);
        }
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
