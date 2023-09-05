<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Exception;
use Illuminate\Http\Request;
use DB;

class authController extends Controller
{
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

                    
              
                dd($cityName);
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
}
