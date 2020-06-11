<?php

namespace App\Utils;

use Illuminate\Http\Response;

class CustomResponse
{

    public static function setSuccessResponse($code, $message = null, $objName = null, $data = null)
    {
        $params = array(
            'success' => true,
            'status_code' => $code,
        );
        if ($objName) {
            $params['data'] = [$objName => $data];
        }
        if ($message) {
            $params['message'] = $message;
        }
        return response()->json($params, $code);
    }


    public static function setFailResponse($message, $code, $errors = null)
    {

        return response()->json([
            'message' => $message,
            'success' => false,
            'errors' => $errors,
            'status_code' => $code,
        ], $code);
    }
}
