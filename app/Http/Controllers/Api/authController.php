<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Exception;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Template\mailController;

class authController extends Controller
{
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
                'city' => ['required', 'integer']
            ]);
            if (!$validator->fails()) {
                $pass = md5($request->password);

                $countryName = DB::table('countries')->where('flag', 1)
                    ->where('id',  $request->country)
                    ->where('name', '!=', '')
                    ->orderByDesc('id')->limit(1)
                    ->value('name');

                $stateName = DB::table('states')->where('flag', 1)
                    ->where('id',  $request->state)
                    ->where('name', '!=', '')
                    ->orderByDesc('id')->limit(1)
                    ->value('name');

                $cityName = DB::table('cities')->where('flag', 1)
                    ->where('id',  $request->city)
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
                    $response = ['status' => 'failed', 'message' => 'Try Again After 1 Hour',  'error' => 'Try after some Time'];
                    goto returnFVI;
                }

                $randotp = Controller::generateOTP(4);

                $arr = [
                    'building_name' => ($request->building_name != '' && $request->building_name != 'null') ? $request->building_name : '',
                    'city' => $cityName,
                    'name' => $request->first_name,
                    'email' => $request->email,
                    'mobile' =>  $request->mobile,
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
                    'password' => $request->password
                ];

                $tempINS =  DB::table('users_temp')->insert($arr);
                $insertedId = DB::getPdo()->lastInsertId();
                if ($tempINS) {


                    $subject = "Little Draw | OTP to Verify Email - " . date("d-m-Y g:i a");
                    $requestArr = [
                        'name' => $request->first_name,
                        'randotp' => $randotp
                    ];

                    $message = mailController::signUPotp($requestArr);
                    $sendEmail = Controller::composeEmail($request->ip(), $request->email, $subject, $message, $frmID = '');
                    if ($sendEmail) {
                        $response = ['status' => 'success', 'message' => 'Email OTP Send Successfully!',  'data' => ['tempID' =>   $insertedId]];
                        goto returnFVI;
                    } else {
                        $response = ['status' => 'failed', 'message' => 'Email OTP Failed!',  'error' => $tempINS];
                        goto returnFVI;
                    }
                } else {
                    $response = ['status' => 'failed', 'message' => 'Insert Failed!',  'error' => $tempINS];
                    goto returnFVI;
                }
            } else {
                $response = ['status' => 'failed', 'message' => 'Validation Error!',  'error' => [$validator->errors()]];
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
            $countries =  DB::table('countries')->select([
                'countries.id as id',
                'countries.name as name',
                DB::raw('COUNT(states.id) as statecount')
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
                DB::raw('COUNT(cities.id) as citycount')
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
                'state_id as stateID'
            ])
                ->where('flag', 1)
                ->orderBy('name', 'ASC')
                ->get();

            $response = ['status' => 'success', 'message' => 'Country, State and City has been get successfully.',  'data' => ['countries' =>   $countries, 'states' => $states, 'cities' => $cities]];
            goto returnFVI;

            returnFVI:
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }

    
}
