<?php

namespace backend\modules\click\components;


class ClickData
{
    const ACTION_PREPARE = 0;
    const ACTION_COMPLETE = 1;

    const ERROR_SUCCESS = 0;
    const ERROR_FAILED_SIGN = -1;
    const ERROR_INCORRECT_AMOUNT = -2;
    const ERROR_ACTION_NOT_FOUND = -3;
    const ERROR_ALREADY_PAID = -4;
    const ERROR_USER_NOT_FOUND = -5;
    const ERROR_TRANSACTION_NOT_FOUND = -6;
    const ERROR_FAILED_UPDATE_USER = -7;
    const ERROR_ERROR_REQUEST_CLICK = -8;
    const ERROR_TRANSACTION_CANCELLED = -9;
    const ERROR_UNKNOWN = -10;

    public static $secretKey = "lvqjHotdwbv"; //drammebel.uz

    const MERCHANT_ID = "21077";
    const MERCHANT_USER_ID = "33811";

    const SERVICE_ID = "28694"; //Service


    public static $minAmount = 1000;
    public static $maxAmount = 100000000;


    public static function getMessage($value): array
    {
        $messages = [
            self::ERROR_SUCCESS => ["error" => 0, "error_note" => "Success"],
            self::ERROR_FAILED_SIGN => ["error" => -1, "error_note" => "SIGN CHECK FAILED!"],
            self::ERROR_INCORRECT_AMOUNT => ["error" => -2, "error_note" => "Incorrect parameter amount"],
            self::ERROR_ACTION_NOT_FOUND => ["error" => -3, "error_note" => "Action not found"],
            self::ERROR_ALREADY_PAID => ["error" => -4, "error_note" => "Already paid"],
            self::ERROR_USER_NOT_FOUND => ["error" => -5, "error_note" => "User does not exist"],
            self::ERROR_TRANSACTION_NOT_FOUND => ["error" => -6, "error_note" => "Transaction does not exist"],
            self::ERROR_FAILED_UPDATE_USER => ["error" => -7, "error_note" => "Failed to update user"],
            self::ERROR_ERROR_REQUEST_CLICK => ["error" => -8, "error_note" => "Error in request from click"],
            self::ERROR_TRANSACTION_CANCELLED => ["error" => -9, "error_note" => "Transaction cancelled"],
            self::ERROR_UNKNOWN => ["error" => -10, "error_note" => "Unknown Error"],
        ];
        return $messages[$value] ?? $messages[self::ERROR_UNKNOWN];
    }
}


?>
