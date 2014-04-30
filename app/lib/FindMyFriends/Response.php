<?php namespace FindMyFriends;

use Illuminate\Support\Facades\Response as BaseResponse;

class Response extends BaseResponse {

    public static function ok($data)
    {
        return BaseResponse::json($data);
    }

    public static function error($message, $code = 500)
    {
        return BaseResponse::json([ 'status' => $code, 'message' => $message], $code);
    }

}