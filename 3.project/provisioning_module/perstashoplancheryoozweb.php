<?php


use WHMCS\Module\Server\PerstaShoplancherYoozweb\Whm;
use WHMCS\Module\Server\PerstaShopLancherYoozweb\Lancher;
use WHMCS\Module\Server\PerstaShopLancherYoozweb\Config;

use \Exception;

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}


function perstashoplancheryoozweb_MetaData()
{
    return array(
        'DisplayName' => 'راه‌انداز خودکار پرستاشاپ',
        'APIVersion' => '1.1',
        'RequiresServer' => true,
        'DefaultNonSSLPort' => '2082',
        'DefaultSSLPort' => '2083'
    );
}

function perstashoplancheryoozweb_GetAllPackages($params)
{

    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    $whm = new Whm($prefix, $host, $ip, $user, $token);


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
            'Default' => '4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54',
            'SimpleMode' => true,

        ),
        'lancher_private_key' => array(
            'FriendlyName' => 'کلید خصوصی رمزنگاری',
            'Type' => 'text',
            'Size' => '255',
            'SimpleMode' => true,
            'Default' => '8de0b01b2d7f049b62cd7d655fef0181d698f1638ef430d010598515bcb5279a'

        ),
        'lancher_secret_key' => array(
            'FriendlyName' => 'کلید مخفی رمزنگاری',
            'Type' => 'text',
            'Size' => '255',
            'SimpleMode' => true,
            'Default' => 'a6b1fd524b580a5c7d37b061e2b6c13fe81b396c66b1108a6343615d6d177482'

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

    $whm = new Whm($prefix, $host, $ip, $user, $token);

    $lancher = new Lancher($secret_key, $secret_iv, $ip, $domain);

    try {

        // // Create account
        $whm->createacct($username, $domain, $plan, false);

        // // Create database
        $whm->cpanelMysqlCreateDatabase($username, $database, false);

        // // Create database user
        $whm->cpanelMysqlCreateUser($username, $database_user, $password, false);

        // // Set privilege all
        $whm->cpanelMysqlSetPrivilegesOnDatabase($username, $database_user, $database, false);



        // Config perstashop
        $start = time();
        $difference = 0;
        $caught = true;
        while ($caught && $difference <= Config::$maximumWaitingTimeForCopying) {
            try {
                $lancher->lanch(
                    $lancher_token,
                    $database,
                    $database_user,
                    $password,
                    $domain,
                    $theme,
                    $firstname,
                    $lastname,
                    $email
                );

                $caught = false;
            } catch (Exception $e) {
                $errorMsg = $e->getMessage();
                if (strpos($errorMsg, '404')) {
                    $caught = true;
                    $difference = time() - $start;
                } else {
                    $caught = false;
                }
            }
        }


        // Delete config API file
        $lancher->deleteApi($lancher_token);

        // Set php version 7.3 cpanel
        $whm->cpanelLangPhpSetVhostVersions($username, $domain);




        // Set php version 7.3 cloudlinux
        $decoded_response = $whm->CreateUserSession($username);

        $session_url = $decoded_response['data']['url'];
        $cookie_jar = 'cookie.txt';

        $curl = $whm->getCurl();

        @curl_setopt($curl, CURLOPT_HTTPHEADER, null);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);


        $whm->resolveCurl($session_url, false);
        $session_url = preg_replace('{/login(?:/)??.*}', '', $session_url);

        $query = $session_url . '/frontend/paper_lantern/lveversion/php_selector.live.pl?cgiaction=sendRequest';


        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'command=cloudlinux-selector&method=set&params[current-version]=7.3&params[interpreter]=php');

        $whm->resolveCurl($query);
    } catch (Exception $e) {

        // Delete cpanel account 
        $whm->removeacct($username, false);

        return $e->getMessage();
    }




    return 'success';
}



function perstashoplancheryoozweb_SuspendAccount($params)
{

    $username = $params['username'];

    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    $reason = $params['suspendreason'];

    $whm = new Whm($prefix, $host, $ip, $user, $token);

    try {
        // Suspend account
        $whm->suspendacct($username, $reason);
    } catch (Exception $e) {
        return $e->getMessage();
    }



    return 'success';
}


function perstashoplancheryoozweb_UnsuspendAccount($params)
{

    $username = $params['username'];

    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    

    $whm = new Whm($prefix, $host, $ip, $user, $token);

    try {

        // Unsuspend account
        $whm->unsuspendacct($username);
    } catch (Exception $e) {
        return $e->getMessage();
    }

    return 'success';
}


function perstashoplancheryoozweb_TerminateAccount($params)
{

    $username = $params['username'];

    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    

    $whm = new Whm($prefix, $host, $ip, $user, $token);

    try {
        // Terminate account
        $whm->removeacct($username, false);
    } catch (Exception $e) {
        return $e->getMessage();
    }

    return 'success';
}



function perstashoplancheryoozweb_ChangePackage($params)
{

    $username = $params['username'];

    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $ip = $params['serverip'];
    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];

    

    $plan = $params['configoption1'];


    $whm = new Whm($prefix, $host, $ip, $user, $token);
    try {
        // Change WHM package
        $whm->changepackage($username, $plan);
    } catch (Exception $e) {
        return $e->getMessage();
    }



    return 'success';
}




function perstashoplancheryoozweb_TestConnection($params)
{

    $token = $params['serveraccesshash'];
    $user = $params['serverusername'];
    $prefix = $params['serverhttpprefix'];
    $host = $params['serverhostname'];
    $ip = $params['serverip'];
    

    $whm = new Whm($prefix, $host, $ip, $user, $token);


    try {


        // Testing connection
        $whm->accountsummary($user);


        $success = true;
        $errorMsg = '';
    } catch (Exception $e) {

        $success = false;
        $errorMsg = $e->getMessage();
    }





    return array(
        'success' => $success,
        'error' => $errorMsg
    );
}
