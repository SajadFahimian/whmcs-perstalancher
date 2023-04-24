<?php

// password user in whmcs "jwjO+Pn%*=Pj"

// use LDAP\Result;

$cpanel_user = "daryates";
$cpanel_user = "daryatest";
$cpanel_user = "dahliayou";
$site_domain = "dahlia.youzwp.ir";

$postfix = 'perstashoplancher';

$ip = '78.157.38.99';

$user = "yoozweb";
$token = "KBJ7DQSGFRBTG5EEPWQ920O7XRAA3H4J";

$host = "https://linux223.talashnet.com";


$port = "2087";

$query = "https://linux223.talashnet.com:2087/json-api/listaccts?api.version=1";
$query = "$host:$port/json-api/list_users?api.version=1";

$database_name = $cpanel_user . "_" . $postfix;

$query_create_database = "$host:$port/json-api/cpanel?api.version=1&cpanel_jsonapi_user=$cpanel_user&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_database&cpanel_jsonapi_apiversion=3&name=$database_name";

$database_user = $cpanel_user . "_" . $postfix;
$database_user_password = "1q$2w3E%gH831";
$query_create_database_user = "$host:$port/json-api/cpanel?api.version=1&cpanel_jsonapi_user=$cpanel_user&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_user&cpanel_jsonapi_apiversion=3&name=$database_user&password=$database_user_password";


$query_related_user_database = "$host:$port/json-api/cpanel?api.version=1&cpanel_jsonapi_user=$cpanel_user&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=set_privileges_on_database&cpanel_jsonapi_apiversion=3&user=$database_user&database=$database_name&privileges=ALL";
$query_change_version = "$host:$port/json-api/cpanel?api.version=1&cpanel_jsonapi_user=$cpanel_user&cpanel_jsonapi_module=LangPHP&cpanel_jsonapi_func=php_set_vhost_versions&cpanel_jsonapi_apiversion=3&version=alt-php73&vhost=$site_domain";


// $database_host = "5.126.199.82";

// $query = "$host:$port/json-api/cpanel?api.version=1&cpanel_jsonapi_user=$cpanel_user&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=add_host&cpanel_jsonapi_apiversion=3&host=$database_host";



function cpanel_query($user, $token, $query)
{
    $curl_resolve = ["linux223.talashnet.com:2087:78.157.38.99", "linux223.talashnet.com:2086:78.157.38.99"];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
    curl_setopt($curl, CURLOPT_TIMEOUT, 6300);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_TCP_FASTOPEN, true);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_POST, false);
    // curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
    // curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);

    // curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);

    // curl_setopt($curl, CURLOPT_TIMEOUT, 3600);
    // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);

    // curl_setopt($curl, CURLOPT_ENCODING, '');

    $header[0] = "Authorization: whm $user:$token";
    // $header[1] = "Content-Type:application/json";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $query);




    // global $site_domain;
    // curl_setopt($curl, CURLOPT_POST, 1);
    // curl_setopt(
    //     $curl,
    //     CURLOPT_POSTFIELDS,
    //     json_encode(

    //        http_build_query(
    //         array(
    //             "domain" => $site_domain,
    //         )
    //        )

    //     )
    // );






    $result = curl_exec($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($http_status != 200) {
        echo "<p style='color: red;'>[!] Error: " . $http_status . " returned</p><b><br><br>";
    } else {
        $json = json_decode($result, TRUE);
        echo "<p style='color: green;'>[+] Current cPanel users on the system:</p><br>";

        print_r($json);

        echo "<br><br>";

        // foreach ($json->{'data'}->{'users'} as $user) {
        //     echo $user . "<br>";
        // }
    }

    curl_close($curl);
}



function cpanel_query_rc($user, $token, $query)
{
    $curl_resolve = ["linux223.talashnet.com:2087:78.157.38.99", "linux223.talashnet.com:2086:78.157.38.99"];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
    curl_setopt($curl, CURLOPT_TIMEOUT, 6300);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_TCP_FASTOPEN, true);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_POST, false);

    $header[0] = "Authorization: whm $user:$token";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $query);

    return $curl;
}




// cpanel_query($user, $token, $query_create_database);

// cpanel_query($user, $token, $query_create_database_user);

// cpanel_query($user, $token, $query_related_user_database);



$query = "$host:$port/json-api/php_set_vhost_versions?api.version=1&version=alt-php74&vhost=$site_domain";
$query = "$host:$port/json-api/listpkgs?api.version=1&want=creatable";
$query = "$host:$port/json-api/removeacct?api.version=1&username=$cpanel_user";
$query = "$host:$port/json-api/suspendacct?api.version=1&user=$cpanel_user&reason=Nonpayment&leave-ftp-accts-enabled=0";
$query = "$host:$port/json-api/unsuspendacct?api.version=1&user=$cpanel_user";
$query = "$host:$port/json-api/changepackage?api.version=1&user=$cpanel_user&pkg=yoozweb_5";
$query = "$host:$port/json-api/accountsummary?api.version=1&user=$user";
$query_create_account = "$host:$port/json-api/createacct?api.version=1&username=$cpanel_user&domain=$site_domain&plan=yoozweb_4";

// $start_time = microtime(TRUE);
// cpanel_query($user, $token, $query);
// $end_time = microtime(TRUE);
// echo '<br>';
// echo $end_time - $start_time;




// $curl = curl_init();
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// $header[0] = "Authorization: whm $user:$token";
// curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
// curl_setopt($curl, CURLOPT_URL, $query);

// $result = curl_exec($curl);


// $json = json_decode($result, TRUE);
// print_r($json);

// // $list = array();

// // foreach ($json['data']['pkg'] as $pkg) {
// //     $list[$pkg['name']] = $pkg['name'];
// // }


// print_r($list);
































// $host = 'linux223.talashnet.com';
// $user = 'ftpusere@demo.youzweb.ir';
// $password = 'fX1#W+P~,A(?';
// $ftpConn = ftp_connect($host);
// $login = ftp_login($ftpConn, $user, $password);
// if ((!$ftpConn) || (!$login)) {
//     echo 'FTP connection has failed! Attempted to connect to ' . $host . ' for user ' . $user . '.';
// } else {
//     echo 'FTP connection was a success.';
//     $directory = ftp_nlist($ftpConn, '');
//     print_r($directory);
// }


// ftp_close($ftpConn);



// $curl = curl_init();
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// $header[0] = "Authorization: whm $user:$token";
// curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
// curl_setopt($curl, CURLOPT_URL, $query);

// $result = curl_exec($curl);

// $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
// if ($http_status != 200) {
//     echo "<p style='color: red;'>[!] Error: " . $http_status . " returned</p><b>";
// } else {
//     $json = json_decode($result);
//     echo "<p style='color: green;'>[+] Current cPanel users on the system:</p><br>";

//     print_r($json);

//     // foreach ($json->{'data'}->{'users'} as $user) {
//     //     echo $user . "<br>";
//     // }
// }

// curl_close($curl);






















































class EncryptDecrypt
{
    private $encrypt_method = 'AES-256-CBC';
    private $secret_key = null;
    private $secret_iv = null;

    public function __construct()
    {
        $this->secret_key = "8de0b01b2d7f049b62cd7d655fef0181d698f1638ef430d010598515bcb5279a";
        $this->secret_iv = "a6b1fd524b580a5c7d37b061e2b6c13fe81b396c66b1108a6343615d6d177482";
    }

    public function encryptDecrypt(String $string, String $action = 'encrypt')
    {

        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $this->encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $this->encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}



// cpanel_query($user, $token, $query_related_user_database);



$start_time = microtime(TRUE);

// $cryptor = new EncryptDecrypt();

// ============================================================================================

// $payload = json_encode(array(
//     'command' => 'extract',
//     'data' => array(
//         "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54"
//     )
// ));

$payload_delete = json_encode(array(
    'command' => 'delete',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54"
    )
));

$payload_config = json_encode(array(
    'command' => 'config',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password
    )
));



$payload_insert_data = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        "domain" => $site_domain,
        "theme" => "45",
        "firstname" => "سجاد",
        "lastname" => "فهیمیان",
        "email" => "s.fahimian0040@gmail.com"
    )
));

$cryptor = new EncryptDecrypt();

function lancher_query($payload, $cryptor, $site_domain)
{

    $payload = $cryptor->encryptDecrypt($payload);


    $curl = curl_init();

    // $query = 'http://localhost:8080/xlam318/?payload=' . urlencode($payload);
    // $query = 'http://localhost/demo/lancher/lancher/server/public_html/xlam318/index.php?payload=' . urlencode($payload);

    $curl_resolve = ["$site_domain:80:78.157.38.99"];
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
    $query = 'http://' . $site_domain . '/xlam318/?payload=' . urlencode($payload);


    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $query);


    $result = curl_exec($curl);
    $result = json_decode($result, TRUE);

    $err = curl_errno($curl);
    print_r($err);
    echo "<br>";

    $errmsg = curl_error($curl);
    print_r($errmsg);
    echo "<br>";

    $header = curl_getinfo($curl);
    print_r($header);
    echo "<br>";


    print_r($result);
    echo "<br>";


    if (isset($result['response'])) {
        $result = json_decode($cryptor->encryptDecrypt($result['response'], 'decrypt'), TRUE);
    }
    print_r($result);
    echo "<br>";


    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    echo "<br>" . $http_status;

    curl_close($curl);

    echo "<br>";
}



function lancher_query_rc($payload, $cryptor, $site_domain)
{

    $payload = $cryptor->encryptDecrypt($payload);


    $curl = curl_init();
    $curl_resolve = ["$site_domain:80:78.157.38.99"];
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
    $query = 'http://' . $site_domain . '/xlam318/?payload=' . urlencode($payload);


    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $query);

    return $curl;
}


// ============================================================================================



// $data = json_encode(array(
//     "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
//     "database" => $database_name,
//     "username" => $database_user,
//     "password" => $database_user_password,
//     "domain" => $site_domain,
//     "theme" => "2",
//     "firstname" => "سجاد",
//     "lastname" => "فهیمیان",
//     "email" => "s.fahimian0040@gmail.com"
//     // 'delete' => true

// ));

// $cryptor = new EncryptDecrypt();

// $payload = $cryptor->encryptDecrypt($data);



// $curl_resolve = ["$site_domain:80:78.157.38.99"];

// $curl = curl_init();
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
// curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);

// curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
// curl_setopt($curl, CURLOPT_URL, "http://$site_domain/xlam318/x245fam.php");

// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt(
//     $curl,
//     CURLOPT_POSTFIELDS,
//     json_encode(array(
//         'payload' => $payload
//     ))
// );

// $result = curl_exec($curl);
// $result = json_decode($result, TRUE);

// $err = curl_errno($curl);
// print_r($err);
// echo "<br>";

// $errmsg = curl_error($curl);
// print_r($errmsg);
// echo "<br>";

// $header = curl_getinfo($curl);
// print_r($header);
// echo "<br>";


// print_r($result);
// echo "<br>";


// if (isset($result['response'])) {
//     $result = json_decode($cryptor->encryptDecrypt($result['response'], 'decrypt'), TRUE);
// }
// print_r($result);

// $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// echo "<br>" . $http_status;

// curl_close($curl);
















// $result = curl_exec($curl);


// $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);


// echo $http_status;

// echo $result;

// curl_close($curl);

// cpanel_query($user, $token, $query_change_version);



// if ($http_status === 200) {

//     $curl = curl_init();
//     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

//     curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
//     curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);

//     curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
//     curl_setopt($curl, CURLOPT_URL, "http://daryatest.com/xlam318/");

//     curl_setopt($curl, CURLOPT_POST, 1);
//     curl_setopt(
//         $curl,
//         CURLOPT_POSTFIELDS,
//         http_build_query(
//             array(
//                 "token" => 'kjh*(kjaju#98oUjhd&r5ym738',
//                 "delete" => 1



//             )
//         )
//     );


//     $result = curl_exec($curl);


//     $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);


//     echo "<br><span style='color: red;'>$http_status</span>";


//     curl_close($curl);
// }













// $data = json_encode(array(
//     "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
//     "database" => "perstashop",
//     "username" => "root",
//     "password" => "",
//     "domain" => "sajad.com",
//     // "database" => $database_name,
//     // "username" => $database_user,
//     // "passord" => $database_user_password,
//     // "domain" => $site_domain,
//     // 'delete' => true
// ));


// $cryptor = new EncryptDecrypt();

// $payload = $cryptor->encryptDecrypt($data);


// $curl = curl_init();
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// curl_setopt($curl, CURLOPT_URL, "http://localhost/demo/lancher/xlam318/x245fam.php");

// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt(
//     $curl,
//     CURLOPT_POSTFIELDS,
//     json_encode(array(
//         'payload' => $payload
//     ))
// );

// $result = curl_exec($curl);

// $result = json_decode($result, TRUE);

// if (isset($result['response'])) {
//     $result = json_decode($cryptor->encryptDecrypt($result['response'], 'decrypt'), TRUE);
// }
// print_r($result);

// $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// echo "<br>" . $http_status;

// curl_close($curl);

// use \PDO;


// include('sql.php');
// include('sql2.php');

// $sql = strtr($SQL_STR, array('$ywdomain' => $site_domain));


// $dbConnection = new PDO("mysql:host=localhost;port=3307;dbname=perstashop", 'root', '');
// $dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
// $statement = $dbConnection->prepare($SQL_STR);
// $statement->execute(array(
//     'ywdomain' => $site_domain,
//     'ywheadertheme' => 'style-2',
//     'ywfootertheme' => 'style-2',
//     'ywhometheme' => '7',
//     'ywcstmrname' => 'سجاد',
//     'ywcstmrlast' => 'فهیمان',
//     'ywcstmrmail' => 's.fahimian0040@gmail.com',
//     'ywcstmrpswd' => $database_user_password

// ));



$payload_insert_query1 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '1',
        "domain" => $site_domain
    )
));
$payload_insert_query2 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '2',
        "domain" => $site_domain
    )
));
$payload_insert_query3 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '3',
        "domain" => $site_domain
    )
));
$payload_insert_query4 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '4',
        "domain" => $site_domain
    )
));
$payload_insert_query5 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '5',
        "domain" => $site_domain
    )
));
$payload_insert_query6 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '6',
        "domain" => $site_domain
    )
));
$payload_insert_query7 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '7',
        "domain" => $site_domain
    )
));
$payload_insert_query8 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '7',
        "domain" => $site_domain,
        "theme" => "45"
    )
));
$payload_insert_query9 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '8',
        "firstname" => "سجاد",
        "lastname" => "فهیمیان",
        "email" => "s.fahimian0040@gmail.com"
    )
));
$payload_insert_query10 = json_encode(array(
    'command' => 'seed_db',
    'data' => array(
        "token" => "4e813e6d8d9d5b4d311469711d95d2c141f43eb929bc46824e44fde5e3119a54",
        "database" => $database_name,
        "username" => $database_user,
        "password" => $database_user_password,
        'query' => '9',
        "domain" => $site_domain
    )
));


$backup_file = '/home/' . $cpanel_user . '/files.tar.gz';
$unzip_directory = '/home/' . $cpanel_user . '/public_html/';
$query_restore_files = $host . ':' . $port . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($cpanel_user) . '&cpanel_jsonapi_module=Backup&cpanel_jsonapi_func=restore_files&cpanel_jsonapi_apiversion=3&backup=' . urlencode($backup_file) . '&directory=' . urlencode($unzip_directory);
$backup_database = '/home/' . $cpanel_user . '/' . $postfix . '.sql';
$query_restore_database = $host . ':' . $port . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($cpanel_user) . '&cpanel_jsonapi_module=Backup&cpanel_jsonapi_func=restore_databases&cpanel_jsonapi_apiversion=3&backup=' . urlencode($backup_database);





cpanel_query($user, $token, $query_create_account);

$curl1 = cpanel_query_rc($user, $token, $query_create_database);
$curl2 = cpanel_query_rc($user, $token, $query_create_database_user);
$curl3 = cpanel_query_rc($user, $token, $query_restore_files);
$curl4 = cpanel_query_rc($user, $token, $query_restore_database);


$mh = curl_multi_init();

curl_multi_add_handle($mh, $curl1);
curl_multi_add_handle($mh, $curl2);
curl_multi_add_handle($mh, $curl3);
curl_multi_add_handle($mh, $curl4);

$active = null;
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);


echo $result1 = curl_multi_getcontent($curl1);
echo '<br>';
echo $result2 = curl_multi_getcontent($curl2);
echo '<br>';
echo $result3 = curl_multi_getcontent($curl3);
echo '<br>';
echo $result4 = curl_multi_getcontent($curl4);
echo '<br>';





curl_multi_remove_handle($mh, $curl1);
curl_multi_remove_handle($mh, $curl2);
curl_multi_remove_handle($mh, $curl3);
curl_multi_remove_handle($mh, $curl4);
curl_multi_close($mh);

cpanel_query($user, $token, $query_related_user_database);




// lancher_query($payload_insert_data, $cryptor, $site_domain);
// lancher_query($payload_insert_query1, $cryptor, $site_domain);
// lancher_query($payload_insert_query2, $cryptor, $site_domain);
// lancher_query($payload_insert_query3, $cryptor, $site_domain);
// lancher_query($payload_insert_query4, $cryptor, $site_domain);
// lancher_query($payload_insert_query5, $cryptor, $site_domain);
// lancher_query($payload_insert_query6, $cryptor, $site_domain);
// // lancher_query($payload_insert_query7, $cryptor, $site_domain);
// lancher_query($payload_insert_query8, $cryptor, $site_domain);
// lancher_query($payload_insert_query9, $cryptor, $site_domain);
// lancher_query($payload_insert_query10, $cryptor, $site_domain);
// lancher_query($payload_config, $cryptor, $site_domain);


// $curl5 = lancher_query_rc($payload_insert_query1, $cryptor, $site_domain);
// $curl6 = lancher_query_rc($payload_insert_query2, $cryptor, $site_domain);
// $curl7 = lancher_query_rc($payload_insert_query3, $cryptor, $site_domain);
// $curl8 = lancher_query_rc($payload_insert_query4, $cryptor, $site_domain);
// $curl9 = lancher_query_rc($payload_insert_query5, $cryptor, $site_domain);
// $curl10 = lancher_query_rc($payload_insert_query6, $cryptor, $site_domain);
// $curl11 = lancher_query_rc($payload_insert_query7, $cryptor, $site_domain);
// $curl12 = lancher_query_rc($payload_insert_query8, $cryptor, $site_domain);
// $curl13 = lancher_query_rc($payload_insert_query9, $cryptor, $site_domain);
// $curl14 = lancher_query_rc($payload_insert_query10, $cryptor, $site_domain);
// $curl15 = lancher_query_rc($payload_config, $cryptor, $site_domain);


// $mh = curl_multi_init();

// curl_multi_add_handle($mh, $curl5);
// curl_multi_add_handle($mh, $curl6);
// curl_multi_add_handle($mh, $curl7);
// curl_multi_add_handle($mh, $curl8);
// curl_multi_add_handle($mh, $curl9);
// curl_multi_add_handle($mh, $curl10);
// curl_multi_add_handle($mh, $curl11);
// curl_multi_add_handle($mh, $curl12);
// curl_multi_add_handle($mh, $curl13);
// curl_multi_add_handle($mh, $curl14);
// curl_multi_add_handle($mh, $curl15);

// $active = null;
// do {
//     $status = curl_multi_exec($mh, $active);
//     if ($active) {
//         curl_multi_select($mh);
//     }
// } while ($active && $status == CURLM_OK);





// echo curl_multi_getcontent($curl12);
// echo '<br>';
// $header = curl_getinfo($curl12);
// echo "<div style='color:red;'>";
// print_r($header);
// echo "</div>";
// echo "<br>";


// echo  curl_multi_getcontent($curl13);
// echo '<br>';
// $header = curl_getinfo($curl13);
// echo "<div style='color:red;'>";
// print_r($header);
// echo "</div>";
// echo "<br>";

// echo  curl_multi_getcontent($curl14);
// echo '<br>';
// $header = curl_getinfo($curl14);
// echo "<div style='color:red;'>";
// print_r($header);
// echo "</div>";
// echo "<br>";





// curl_multi_remove_handle($mh, $curl5);
// curl_multi_remove_handle($mh, $curl6);
// curl_multi_remove_handle($mh, $curl7);
// curl_multi_remove_handle($mh, $curl8);
// curl_multi_remove_handle($mh, $curl9);
// curl_multi_remove_handle($mh, $curl10);
// curl_multi_remove_handle($mh, $curl11);
// curl_multi_remove_handle($mh, $curl12);
// curl_multi_remove_handle($mh, $curl13);
// curl_multi_remove_handle($mh, $curl14);
// curl_multi_remove_handle($mh, $curl15);
// curl_multi_close($mh);






// lancher_query($payload_delete, $cryptor, $site_domain);




// cpanel_query($user, $token, $query_change_version);





// test cloud linux




// define("WHM_TOKEN", "$token");
// define("WHM_DOMAIN", "linux223.talashnet.com");

// // The user on whose behalf the API call runs.
// // For webmaild sessions, use the cPanel user or their full email address
// $cpanel_user = $cpanel_user;

// $query = "https://" . WHM_DOMAIN . ":2087/json-api/create_user_session?api.version=1&user=$cpanel_user&service=cpaneld";

// $ch = curl_init();                                     // Create Curl Object.
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);       // Allow self-signed certificates...
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);       // and certificates that don't match the hostname.
// curl_setopt($ch, CURLOPT_HEADER, false);               // Do not include header in output
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        // Return contents of transfer on curl_exec.
// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: whm $user:" . WHM_TOKEN));
// curl_setopt($ch, CURLOPT_URL, $query);                 // Execute the query.
// $result = curl_exec($ch);
// if ($result == false) {
//     error_log("curl_exec threw error \"" . curl_error($ch) . "\" for $query");
//     // log error if curl exec fails
// }


// $decoded_response = json_decode($result, true);

// $session_url = $decoded_response['data']['url'];
// $cookie_jar = 'cookie.txt';
// var_dump($decoded_response);


// @curl_setopt($ch, CURLOPT_HTTPHEADER, null);             // Unset the authentication header.
// curl_setopt($ch, CURLOPT_COOKIESESSION, true);          // Initiate a new cookie session.
// curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);       // Set the cookie jar.
// curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);      // Set the cookie file.
// curl_setopt($ch, CURLOPT_URL, $session_url);            // Set the query url to the session login url.

// $result = curl_exec($ch);                               // Execute the session login call.
// if ($result == false) {
//     error_log("curl_exec threw error \"" . curl_error($ch) . "\" for $query");
//     // Log an error if curl_exec fails.
// }

// $session_url = preg_replace('{/login(?:/)??.*}', '', $session_url);  // make $session_url = https://10.0.0.1/$session_key

// $query = "$session_url/frontend/jupiter/lveversion/php_selector.live.pl?cgiaction=sendRequest";


// var_dump($query);
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_URL, $query);  // Change the query url to use the UAPI call.
// curl_setopt($ch, CURLOPT_POSTFIELDS, "command=cloudlinux-selector&method=set&params[current-version]=7.3&params[interpreter]=php");
// $result = curl_exec($ch);               // Execute the UAPI call.
// if ($result == false) {
//     error_log("curl_exec threw error \"" . curl_error($ch) . "\" for $query");
//     // log error if curl exec fails
// }

// curl_close($ch);

// print $result;





// =======================================================================================================




// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, 'https://my.youzweb.ir/cv654kh/');
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('Hello')));

// curl_exec($ch);

// curl_close($ch);

// echo json_last_error();

// echo basename(__FILE__, '.php');

echo __FUNCTION__ ;
echo __METHOD__;

echo "<br>";

function test(): void
{
    echo __FUNCTION__;
    echo __METHOD__;
}

test();


// =======================================================================================================


$end_time = microtime(TRUE);
echo '<br>';
echo $end_time - $start_time;












// echo time() . "<br>";

// sleep(100);

// echo time() . "<br>";





// $str = "\u0642\u0627\u0644\u0628 \u0633\u0627\u06cc\u062a";
// $str = "\u0642\u0627\u0644\u0628 1";
// $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
//     return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
// }, $str);

// echo $str;

// print_r(json_decode('{"t":"\u0642\u0627\u0644\u0628 \u0633\u0627\u06cc\u062a"}', TRUE));



// if(strpos("error 404", "403")) {
//     echo 'yes';
// }



// Create   Load
// 2:50      20







// $data = json_encode(array(
//     'where' => 'GetAllPackages',
//     'data' => $params
// ));
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($curl, CURLOPT_URL, 'http://localhost/demo/api.php');
// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
// curl_exec($curl);
// curl_close($curl);


// echo DIRECTORY_SEPARATOR;


// echo '<hr>';


// include './includes/include.php';

// echo "<br>";
// echo PATHINFO_DIRNAME;

// echo PATH;

// echo DIRECTORY_SEPARATOR;
