<?php

use WHMCS\Module\Server\PerstaShopLancherYoozweb\Config;
use WHMCS\Module\Server\PerstaShoplancherYoozweb\Whm;
use WHMCS\Module\Server\PerstaShoplancherYoozweb\ASWhm;

use \Exception;

// $ch = curl_init();
// $data = array('Frome' => 'GetAllPackages', 'Data' => $params);
// curl_setopt($ch, CURLOPT_URL, 'https://my.youzweb.ir/cv654kh/');
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// curl_exec($ch);

// curl_close($ch);


if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}


function perstashoplancheryoozweb_MetaData()
{
    return array(
        'DisplayName' => 'راه‌انداز خودکار پرستاشاپ',
        'APIVersion' => '1.1',
        'RequiresServer' => true,
        'DefaultNonSSLPort' => '2086',
        'DefaultSSLPort' => '2087'
    );
}


function perstashoplancheryoozweb_GetAllPackages($params)
{

    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $port = $params['serverport'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    $whm = new Whm($prefix, $host, $port, $ip, $user, $token);


    $result = $whm->listpkgs();

    $list = array();

    foreach ($result['data']['pkg'] as $pkg) {
        $list[$pkg['name']] = ucfirst($pkg['name']);
    }

    return $list;
}



function perstashoplancheryoozweb_ConfigOptions($params)
{
    return array(
        'lancher_plan' => array(
            'FriendlyName' => 'بسته راه‌انداز',
            'Type' => 'text',
            'Size' => '25',
            'Loader' => 'perstashoplancheryoozweb_GetAllPackages',
            'SimpleMode' => true,
            'Description' => 'نام بسته قابل ایجاد بر روی سرور'

        ),
        'lancher_token' => array(
            'FriendlyName' => 'توکن',
            'Type' => 'text',
            'Size' => '255',
            'Default' => Config::$token,
            'SimpleMode' => true,

        ),
        'lancher_private_key' => array(
            'FriendlyName' => 'کلید خصوصی رمزنگاری',
            'Type' => 'text',
            'Size' => '255',
            'SimpleMode' => true,
            'Default' => Config::$private_key

        ),
        'lancher_secret_key' => array(
            'FriendlyName' => 'کلید مخفی رمزنگاری',
            'Type' => 'text',
            'Size' => '255',
            'SimpleMode' => true,
            'Default' => Config::$secret_key

        ),
    );
}


function perstashoplancheryoozweb_CreateAccount(array $params)
{

    $username = $params['username'];
    $domain = $params['domain'];
    $password = $params['password'];

    $lancher_token = $params['configoption2'];
    $secret_key = $params['configoption3'];
    $secret_iv = $params['configoption4'];


    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $port = $params['serverport'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    $plan = $params['configoption1'];
    $database = $username . '_' . Config::$postfix;
    $database_user = $username . '_' . Config::$postfix;

    $theme = Config::$themes[$params['configoptions']['قالب سایت']];

    $firstname = $params['model']['client']['firstname'];
    $lastname = $params['model']['client']['lastname'];
    $email = $params['model']['client']['email'];

    $aswhm = new ASWhm($prefix, $host, $port, $ip, $user, $token);

    // $lancher = new Lancher($secret_key, $secret_iv, $ip, $domain);

    try {

        // Create account
        $aswhm->createacct($username, $domain, $plan);

        // Async create database, create user, restore file and restore database
        $aswhm
        ->addHandleCpanelMysqlCreateDatabase($username, $database)
        ->addHandleCpanelMysqlCreateUser($username, $database_user, $password)
        ->addHandleCpanelBackupRestoreFiles($username)
        ->addHandleCpanelBackupRestoreDatabases($username)
        ->resolveCurlMulti();

        // Create database
        // $aswhm->cpanelMysqlCreateDatabase($username, $database);

        // Create database user
        // $aswhm->cpanelMysqlCreateUser($username, $database_user, $password);

        // Restore file in public directory
        // $aswhm->cpanelBackupRestoreFiles($username);

        // Restore database in postfix database
        // $aswhm->cpanelBackupRestoreDatabases($username);

        // Set privilege all
        // $aswhm->cpanelMysqlSetPrivilegesOnDatabase($username, $database_user, $database);


        // Config perstashop
        // $start = time();
        // $difference = 0;
        // $caught = true;
        // while ($caught && $difference <= Config::$maximumWaitingTimeForCopying) {
        //     try {
        //         $lancher->lanch(
        //             $lancher_token,
        //             $database,
        //             $database_user,
        //             $password,
        //             $domain,
        //             $theme,
        //             $firstname,
        //             $lastname,
        //             $email
        //         );

        //         $caught = false;
        //     } catch (Exception $e) {
        //         $errorMsg = $e->getMessage();
        //         if (strpos($errorMsg, '404')) {
        //             $caught = true;
        //             $difference = time() - $start;
        //         } else {
        //             $caught = false;
        //         }
        //     }
        // }


        // Delete config API file
        // $lancher->deleteApi($lancher_token);

        // Set php version 7.3 cpanel
        // $whm->cpanelLangPhpSetVhostVersions($username, $domain);




        // Set php version 7.3 cloudlinux
        // $decoded_response = $whm->CreateUserSession($username);

        // $session_url = $decoded_response['data']['url'];
        // $cookie_jar = 'cookie.txt';

        // $curl = $whm->getCurl();

        // @curl_setopt($curl, CURLOPT_HTTPHEADER, null);
        // curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        // curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
        // curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);


        // $whm->resolveCurl($session_url, false);
        // $session_url = preg_replace('{/login(?:/)??.*}', '', $session_url);

        // $query = $session_url . '/frontend/paper_lantern/lveversion/php_selector.live.pl?cgiaction=sendRequest';


        // curl_setopt($curl, CURLOPT_POST, 1);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, 'command=cloudlinux-selector&method=set&params[current-version]=7.3&params[interpreter]=php');

        // $whm->resolveCurl($query);
    } catch (Exception $e) {

        // Delete cpanel account 
        // $whm->removeacct($username, false);

        logModuleCall(
            basename(__FILE__, '.php'),
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }




    return 'success';
}
