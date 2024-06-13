<?php

namespace App\Functions;

use Illuminate\Http\Exceptions\HttpResponseException;

class ResponseHelper
{
    public static function make($data, $msg = '', $success = true, $statusCode = 200)
    {
        throw new HttpResponseException(response()->json([
            'msg'           => $msg,
            'statusCode'    => $statusCode,
            'success'       => $success,
            'payload'       => $data
        ], $statusCode));
    }
}
