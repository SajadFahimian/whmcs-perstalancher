<?php


namespace WHMCS\Module\Server\PerstaShopLancherYoozweb;

class Config
{
    public static $themes = array(
        'قالب 1' => '1', // Ok
        'قالب 2' => '2', // Ok
        'قالب 3' => '3', // Ok
        'قالب 4' => '5', // Ok
        'قالب 5' => '6', // Slider error
        'قالب 6' => '7', // Ok
        'قالب 7' => '8', // Ok
        'قالب 8' => '14', // Google map not work
        'قالب 9' => '15', // Ok
        'قالب 10' => '36', // Ok
        'قالب 11' => '37', // Ok
        'قالب 12' => '41', // Slider error
        'قالب 13' => '42', //
        'قالب 14' => '43', //
        'قالب 15' => '44', //
        'قالب 16' => '45' //
    );
    public static $postfix = 'perstashoplancher';
    public static $statusCodes = array(200, 307);
    public static $maximumWaitingTimeForCopying = 85;
}
