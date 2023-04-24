<?php

namespace WHMCS\Module\Server\PerstaShopLancherYoozweb;

use \Exception;

class Lancher
{
    const CURL_TIMEOUT = 6300;
    const CONNECT_TIMEOUT = 60;
    private $secret_key;
    private $secret_iv;
    private $curl;
    private $url;
    public function __construct(String $secret_key, String $secret_iv, String $ip, String $domain)
    {
        $this->secret_key = $secret_key;
        $this->secret_iv = $secret_iv;
        $this->url = 'http://' . $domain . '/xlam318/x245fam.php';

        $curl_resolve = [$domain . ':80:' . $ip];

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
        curl_setopt($this->curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($this->curl, CURLOPT_RESOLVE, $curl_resolve);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_ENCODING, '');
        curl_setopt($this->curl, CURLOPT_TCP_FASTOPEN, true);
        curl_setopt($this->curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POST, 1);
    }
    private function encryptDecrypt(String $string, String $action = 'encrypt')
    {
        $encrypt_method = 'AES-256-CBC';

        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
        if ($action === 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action === 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    private function resolveCurl(array $data, bool $response = true)
    {
        $payload = json_encode($data);
        $payload = $this->encryptDecrypt($payload);





        curl_setopt(
            $this->curl,
            CURLOPT_POSTFIELDS,
            json_encode(array(
                'payload' => $payload
            ))
        );
        $result = curl_exec($this->curl);
        if (curl_error($this->curl)) {
            throw new Exception('Unable to connect: ' . curl_errno($this->curl) . ' - ' . curl_error($this->curl));
        } elseif (empty($result) && $response) {
            throw new Exception('Empty response for ' . $this->url);
        }
        $result = json_decode($result, TRUE);
        if (is_null($result) && $response) {
            throw new Exception('Invalid response format for ' . $this->url);
        }
        if (isset($result['response']) && $result['response'])
            $result = json_decode($this->encryptDecrypt($result['response'], 'decrypt'), TRUE);

        $http_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        if ($http_status != 200) {
            $message = null;
            if (isset($result['message']))
                $message = $result['message'];
            elseif (isset($result['Database connection']))
                $message = 'Database connection: ' . $result['Database connection']['message'];
            elseif (isset($result['Insert data']))
                $message = 'Insert data: ' . $result['Insert data']['message'];
            elseif (isset($result['Edit file']))
                $message = 'Edit file';
            if ($message)
                throw new Exception("Status code $http_status. $message");

            throw new Exception("error $http_status");
        }
        return $result;
    }

    public function lanch($lancher_token, $database, $database_user, $password, $domain, $theme, $firstname, $lastname, $email)
    {
        return $this->resolveCurl(array(
            'token' => $lancher_token,
            'database' => $database,
            'username' => $database_user,
            'password' => $password,
            'domain' => $domain,
            'theme' => $theme,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email
        ), false);
    }
    public function deleteApi($lancher_token)
    {
        return $this->resolveCurl(array(
            'token' => $lancher_token,
            'delete' => true
        ), false);
    }
}
