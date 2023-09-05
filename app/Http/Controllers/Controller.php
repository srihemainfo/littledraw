<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function generateOTP($len)
    {
        try {
            $generator = "135792468";
            $result = "";
            for ($i = 1; $i <= $len; $i++) {
                $result .= substr($generator, (rand() % (strlen($generator))), 1);
            }
            return $result;
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }
}
