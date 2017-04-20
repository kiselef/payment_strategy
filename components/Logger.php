<?php
namespace app\components;


class Logger
{
    const CATEGORY_PAYMENT = 'payment';

    private function __construct() {}
    private function __clone() {}

    public static function log($message, $title = '')
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }

        $message = $title ? $title . ': ' . $message : $message;
        \Yii::info($message, self::CATEGORY_PAYMENT);
    }
}