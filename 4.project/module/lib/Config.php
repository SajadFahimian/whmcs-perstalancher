<?php


namespace WHMCS\Module\Server\PerstaShopLancherYoozweb;

class Config
{
    public static $token = '4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54';
    public static $private_key = '8de0b01b2d7f049b62cd7d655fef0181d698f1638ef430d010598515bcb5279a';
    public static $secret_key = 'a6b1fd524b580a5c7d37b061e2b6c13fe81b396c66b1108a6343615d6d177482';

    public static $statusCodes = array(200, 307);
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
    public static $archive_file = 'files.tar.gz';
}
