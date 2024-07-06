<?php

namespace api\services;

use api\utils\MessageConst;

class ResponseService
{
    public static function getResponse($banners): array
    {
        if (empty($banners)) {
            return self::message(MessageConst::NOT_FOUND_MESSAGE, false);
        }

        return self::message(MessageConst::GET_SUCCESS, true, $banners);
    }

    private static function message($message = null, $success = true, $data = null): array
    {
        return [
            'message' => $message,
            'success' => $success,
            'data' => $data,
        ];
    }
}

