<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Template\mailController;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class authController extends Controller
{

    public function userRegister($user_id, $otp, $ip)
    {
        try {
            $response = [];
            $getTempUser = DB::table('users_temp')->where([
                ['deletes', '=', '0'],
                ['id', '=', $user_id],
            ])->first();

            if ($getTempUser->id != '') {
                $getTempUser = json_decode(json_encode($getTempUser), true);

                unset($getTempUser['id']);
                $getTempUser['created_at'] = date("Y-m-d H:i:s");
                $getTempUser['IBAN_code'] = '';
                $getTempUser['currency_code'] = '';
                // TODO: not finished ssd
                dd($getTempUser);

                $user_register_insert = DB::table('user_register')->insert($getTempUser);

                dd($user_register_insert);

                if ($user_register_insert) {
                    $last_ins_ID = DB::getPdo()->lastInsertId();

                    $user_register_user_check = select_query($this->con, "user_register", "", "`id`='$last_ins_ID' and `status`='0'  and `otp`='$otp'   and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register_user_check['nr'] > 0) {
                        $user_id_s = $user_register_user_check['result'][0]['id'];

                        $mobile = select_top_name($this->con, "user_register", "mobile", "`id`='$user_id_s' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "mobile", "");
                        $name = select_top_name($this->con, "user_register", "name", "`id`='$user_id_s' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "name", "");
                        $email = select_top_name($this->con, "user_register", "email", "`id`='$user_id_s' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "email", "");

                        if (substr($mobile, 0, 3) == "971") {
                            $messages = "Congratulation!!! You have successfully created Little Draw account.";
                            $templateid = "";
                            // sendsms($this->con, $mobile, $messages, $templateid);
                        }

                        $subject = 'Congratulation!!! You have successfully created Little Draw account';

                        $messages = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

                                                            <html xmlns="http://www.w3.org/1999/xhtml">

                                                            <head>

                                                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                                                            <meta http-equiv="X-UA-Compatible" content="IE=edge" />

                                                            <meta name="viewport" content="width=device-width, initial-scale=1.0">

                                                            <title>Ticket Purchase OTP Registered Template</title>

                                                            <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=6uwSMFAkZPgrNvkHDa5A-2G2mC7d8O0zslNZ97rd3ooPL2OKZv2GxCk1VHcTHqeq7-bFOP--dprL0GEc99h-FZL_gJhGqMo1pe1DBAT3R9NjPNbBHiVJIC7CeHsdymQ0" charset="UTF-8"></script><style type="text/css">



                                                              @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");

                                                              body {

                                                                margin: 0;

                                                              }

                                                              .wrapper {



                                                                background:#CCC;



                                                                }

                                                              .main {



                                                                background:#FFF;

                                                                max-width:600px;



                                                                }



                                                              table {

                                                                border-spacing: 0;

                                                              }

                                                              td {

                                                                padding: 3px;

                                                              }

                                                              img {

                                                                border: 0;

                                                              }

                                                              .column-one {



                                                                text-align:center;

                                                                margin:0 auto;

                                                                }

                                                              .column-one .column {



                                                                width:100%;

                                                                  margin:0 auto;



                                                                }







                                                            </style>

                                                            </head>

                                                            <body>



                                                              <center class="wrapper">



                                                                        <table class="main" width="100%">

                                                                            <!-- BORDER -->

                                                                            <tr><td class="column-one" style="background: #29377d; height:50px;">





                                                                            </td></tr>
                                                                            <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">


                                                                            </td></tr>









                                                                  <tr><td class="column-one" >

                                                                  <table class="column"> <tr><td valign="top"  style="padding: 16px 0 12px 0;">

                                                                  <center>

                                                                    <img src="' . $baseurl . 'assets/images/mailtemplate/logo1.png" style="border: 0px;"  >



                                                                  </center>



                                                                    </td></tr></table>



                                                                      </td></tr>



                                                                              <tr>


                                                                                  <td class="column-one" >

                                                                                    <table align="center" class="column" style="
                                                                              background: url(' . $baseurl . 'assets/images/mailtemplate/new_mantony.png)no-repeat;
                                                                              height:429px;background-position: center;    margin: 0px 0 0 0 !important;"> <tbody><tr><td colspan="3" valign="top" style="padding:10px 0px 0px 10px;">


                                                                          <h3 class="demoname" style="color: #be1e2d;  font-family: Arial Narrow;font-style: italic;font-size: 32px; margin: 0px 0px 0px 24px; text-align: center;">Hi, ' . $name . '


                                                                                              </h3>


                                                                                          </td></tr><tr>
                                                                                            <td>


                                                                                          </td>


                                                                          </tr>


                                                                                    </tbody></table>


                                                                                  </td>




                                                                    </tr>



                                                              <tr>

                                                                                <td class="column-one" >

                                                                      <table align="center" class="column"> <tr>

                                                                        <td valign="top" >

                                                                          <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

                                                                    <tbody>



                                                                              <tr>

                                                                        <td style="color: #666666; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">

                                                                          <strong><p class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 26px; margin: 10px 0px 0px 0px; text-align: center;">Are you excited to <span style="color:#be1e2d;">Participate?</span>

                                                                                  </p></strong>

                                                                          <p style="color: #29377d;  font-size:152%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;margin: 22px 0px 13px 0px;font-weight: 600;">You are one step away<br>from changing your Life</p>

                                                                        </td>

                                                                              </tr>

                                                                              <tr>
                                                                          <td style=" border-radius: 4px 4px 0px 0px; color: #111111; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">
                                                                            <h3 style="color: #ffffff; font-size: 22px; margin: 0px 0px 9px 0px; font-style: italic; font-family: Arial Narrow; padding: 10px 8px 8px 8px; background: #be1e2d; width: 230px; line-height: 1; border-radius: 10px;"><a style=" color:#fff;" href="' . $baseurl . 'play">PARTICIPATE NOW !</a></h3>
                                                                          </td>
                                                                        </tr>

                                                                    </tbody>

                                                                  </table>

                                                                  <br>



                                                                  <table style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

                                                                    <tbody>

                                                                      <tr>

                                                                        <td class="gmail-line" style="box-sizing: border-box; width: 8px;">

                                                                          <img  style="width:489px !important;" src="' . $baseurl . 'assets/images/mailtemplate/center_img2.png">

                                                                        </td>

                                                                      </tr>

                                                                    </tbody>

                                                                  </table>
                                                                  <br>



                                                                  <p style="color: #29377d;  font-size: 152%; margin: 0px; background-color: #fbfbfb; text-align: center;font-style: italic;font-family: Arial Narrow;margin: 8px 0px 0px 0px;">Little Draw</p>


                                                                  <p style="color: #29377d;  font-size: 152%; margin: 0px; background-color: #fbfbfb; text-align: center;font-style: italic;font-family: Arial Narrow;margin: 8px 0px 0px 0px;">Office 202 H, lbn Battuta Gate Offices,

                                                                    <br>P.O.Box:451394, Dubai, UAE.

                                                                  </p>
                                                                   <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>

                                                                   For Clarification



                                                                          <br>

                                                                   Call 04 33 98880 Whatsapp +971 56 199 1271

                                                                   <br>

                                                                   or email support@littledraw.ae</p>
                                                                        </td></tr></table>



                                                                      </td></tr>


                                                                  </table>
                                                                </center>



                                                              </body>

                                                              </html>';

                        sendemail($this->con, $email, $subject, $messages, 'welcome');

                        $result['type'] = '1';
                        $result['result'] = 'Verified successfully, login to continue';
                        return $result;
                    }
                } else {
                    $response = ['status' => 'failed', 'message' => 'User Creation has been failed!', 'error' => 'Insert query has been Failed'];
                    goto returnFVI;
                }
            } else {
                $response = ['status' => 'failed', 'message' => 'User account not found!', 'error' => 'The user account not found in the temp.'];
                goto returnFVI;
            }

            returnFVI:
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }

    // Register Email OTP Send
    public function register(Request $request)
    {
        try {
            $response = [];
            $input = $request->all();
            $validator = Validator::make($input, [
                'first_name' => ['required', 'max:70'],
                // 'last_name' => ['required'],
                'mobile' => ['required', 'integer', 'unique:user_register'],
                'dialCode' => ['required', 'integer'],
                'email' => ['required', 'email', 'unique:user_register', 'max:70'],
                'password' => ['required', Password::min(6)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'c_password' => ['required', Password::min(6)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), 'same:password'],
                'deviceType' => ['required', 'in:MOBILE,APP,DESKTOP,BROWSER', 'max:10'],
                'building_name' => ['max:50'],
                'country' => ['required', 'integer'],
                'state' => ['required', 'integer'],
                'city' => ['required', 'integer'],
            ]);
            if (!$validator->fails()) {
                $pass = md5($request->password);

                $countryName = DB::table('countries')->where('flag', 1)
                    ->where('id', $request->country)
                    ->where('name', '!=', '')
                    ->orderByDesc('id')->limit(1)
                    ->value('name');

                $stateName = DB::table('states')->where('flag', 1)
                    ->where('id', $request->state)
                    ->where('name', '!=', '')
                    ->orderByDesc('id')->limit(1)
                    ->value('name');

                $cityName = DB::table('cities')->where('flag', 1)
                    ->where('id', $request->city)
                    ->where('name', '!=', '')
                    ->orderByDesc('id')->limit(1)
                    ->value('name');

                $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));

                $checkRepeat = DB::table('users_temp')
                    ->where('email', '=', $request->email)
                    ->where('status', '=', '0')
                    ->where('deletes', '=', '1')
                    ->where('created_at', '>=', $oneHourAgo)
                    ->get();

                if ($checkRepeat->count() > 4) {
                    $response = ['status' => 'failed', 'message' => 'Try Again After 1 Hour', 'error' => 'Try after some Time'];
                    goto returnFVI;
                }

                $randotp = Controller::generateOTP(4);

                $arr = [
                    'building_name' => ($request->building_name != '' && $request->building_name != 'null') ? $request->building_name : '',
                    'city' => $cityName,
                    'name' => $request->first_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'address' => $stateName,
                    'nationality' => $countryName,
                    'pass' => $pass,
                    'deletes' => '1',
                    'dialCode' => $request->dialCode,
                    'otp' => $randotp,
                    'ip' => $request->ip(),
                    'deviceType' => $request->deviceType,
                    'roll_id' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'password' => $request->password,
                ];

                $tempINS = DB::table('users_temp')->insert($arr);
                $insertedId = DB::getPdo()->lastInsertId();
                if ($tempINS) {

                    $subject = "Little Draw | OTP to Verify Email - " . date("d-m-Y g:i a");
                    $requestArr = [
                        'name' => $request->first_name,
                        'randotp' => $randotp,
                    ];

                    $message = mailController::signUPotp($requestArr);
                    $sendEmail = Controller::composeEmail($request->ip(), $request->email, $subject, $message, $frmID = '');
                    if ($sendEmail) {
                        $response = ['status' => 'success', 'message' => 'Email OTP Send Successfully!', 'data' => ['tempID' => (int) $insertedId]];
                        goto returnFVI;
                    } else {
                        $response = ['status' => 'failed', 'message' => 'Email OTP Failed!', 'error' => $sendEmail];
                        goto returnFVI;
                    }
                } else {
                    $response = ['status' => 'failed', 'message' => 'Insert Failed!', 'error' => $tempINS];
                    goto returnFVI;
                }
            } else {
                $response = ['status' => 'failed', 'message' => 'Validation Error!', 'error' => [$validator->errors()]];
                goto returnFVI;
            }

            returnFVI:
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }

    // Get Country, City, State, collection API
    public function getWorld()
    {
        try {
            $response = [];
            $countries = DB::table('countries')->select([
                'countries.id as id',
                'countries.name as name',
                DB::raw('COUNT(states.id) as statecount'),
            ])
                ->join('states', 'countries.id', '=', 'states.country_id')
                ->where('countries.flag', 1)
                ->groupBy('countries.id')
                ->havingRaw('statecount > 0')
                ->orderBy('name', 'ASC')
                ->get();

            $states = DB::table('states')->select([
                'states.id as id',
                'states.name as name',
                'states.country_id as countryID',
                DB::raw('COUNT(cities.id) as citycount'),
            ])
                ->join('cities', 'states.id', '=', 'cities.state_id')
                ->where('states.flag', 1)
                ->groupBy('states.id')
                ->havingRaw('citycount > 0')
                ->orderBy('name', 'ASC')
                ->get();

            $cities = DB::table('cities')->select([
                'id',
                'name',
                'country_id as countryID',
                'country_id as countryID',
                'state_id as stateID',
            ])
                ->where('flag', 1)
                ->orderBy('name', 'ASC')
                ->get();

            $response = ['status' => 'success', 'message' => 'Country, State and City has been get successfully.', 'data' => ['countries' => $countries, 'states' => $states, 'cities' => $cities]];
            goto returnFVI;

            returnFVI:
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }

    //  The Mobile/Email OTP Verification API
    public function signupOTPVerify(Request $request)
    {
        try {
            $response = [];
            $input = $request->all();
            $validator = Validator::make($input, [
                'tempID' => ['required'],
                'method' => ['required', 'in:EMAIL,MOBILE', 'max:10'],
                'OTP' => ['required', 'max:4'],
            ]);
            if (!$validator->fails()) {

                if ($request->method == 'EMAIL') {
                    $bresult = DB::table('users_temp')->where('id', $request->tempID)
                        ->where('status', 0)
                        ->where('otp', $request->OTP)
                        ->where('deletes', 1)
                        ->orderBy('id', 'DESC')
                        ->limit(1)
                        ->get();
                    if ($bresult->count() > 0) {
                        $mobile = $bresult[0]->mobile;
                        $email_verify = DB::table('users_temp')->where('id', $request->tempID)->update(['email_verify' => 'YES']);
                        if ($email_verify) {
                            if (substr($mobile, 0, 3) == "971") {
                                // Todo: pending SMS - OTP
                            } else {

                                $delete_update = DB::table('users_temp')->where('id', $request->tempID)->update(['deletes' => '0']);

                                if ($delete_update) {

                                    $result = authController::userRegister($request->tempID, $request->OTP, $request->ip());
                                    goto returnFVI;
                                }
                            }
                        } else {
                            $response = ['status' => 'failed', 'message' => 'Invalid OTP!', 'error' => 'OTP Verification failed.'];
                            goto returnFVI;
                        }
                    } else {
                        $response = ['status' => 'failed', 'message' => 'Invalid OTP!', 'error' => 'OTP Verification failed.'];
                        goto returnFVI;
                    }
                }
            } else {
                $response = ['status' => 'failed', 'message' => 'Validation Error!', 'error' => [$validator->errors()]];
                goto returnFVI;
            }

            returnFVI:
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }
}
