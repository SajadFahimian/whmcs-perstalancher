<?php

namespace WHMCS\Module\Server\PerstaShopLancherYoozweb;

use WHMCS\Module\Server\PerstaShopLancherYoozweb\Config;
use \Exception;


class Whm
{
    const CURL_TIMEOUT = 6300;
    const CONNECT_TIMEOUT = 60;
    private $port = '2087';
    private $url;
    private $curl;
    public function __construct($prefix, $host, $ip, $user, $token)
    {
        $curl_resolve = [$host . ':' . $this->port . ':' . $ip];
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        curl_setopt($this->curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
        curl_setopt($this->curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($this->curl, CURLOPT_RESOLVE, $curl_resolve);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        curl_setopt($this->curl, CURLOPT_ENCODING, '');
        curl_setopt($this->curl, CURLOPT_TCP_FASTOPEN, true);
        curl_setopt($this->curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($this->curl, CURLOPT_POST, false);

        $header[0] = 'Authorization: whm ' . $user . ':' . $token;
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        $this->url = $prefix . '://' . $host . ':' . $this->port;
    }
    public function resolveCurl($url, $response = true)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        $result = curl_exec($this->curl);
        if (curl_error($this->curl)) {
            throw new Exception('Unable to connect: ' . curl_errno($this->curl) . ' - ' . curl_error($this->curl));
        } elseif (empty($result)) {
            throw new Exception('Empty response for' . $url);
        }

        $http_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        if (!in_array($http_status, Config::$statusCodes)) {
            throw new Exception('error ' . $http_status . '. for ' . $url);
        }


        $result_array = json_decode($result, TRUE);
        if (is_null($result_array) && $response) {
            throw new Exception('Invalid response format for ' . $url);
        }

        return $result_array;
    }
    public function getCurl()
    {
        return $this->curl;
    }
    public function listpkgs()
    {
        $query = $this->url . '/json-api/listpkgs?api.version=1&want=creatable';
        return $this->resolveCurl($query);
    }
    public function createacct($username, $domain, $plan, $response = true)
    {
        $query = $this->url . '/json-api/createacct?api.version=1&username=' . urlencode($username) . '&domain=' . urlencode($domain) . '&plan=' . urlencode($plan);
        return $this->resolveCurl($query, $response);
    }
    public function removeacct($username, $response = true)
    {
        $query = $this->url . '/json-api/removeacct?api.version=1&username=' . urlencode($username);
        return $this->resolveCurl($query, $response);
    }
    public function suspendacct($username, $reason)
    {
        $query = $this->url . '/json-api/suspendacct?api.version=1&user=' . urlencode($username) . '&reason=' . urlencode($reason) . '&leave-ftp-accts-enabled=0';
        return $this->resolveCurl($query);
    }
    public function unsuspendacct($username)
    {
        $query = $this->url . '/json-api/unsuspendacct?api.version=1&user=' . urlencode($username);
        return $this->resolveCurl($query);
    }
    public function changepackage($username, $plan)
    {
        $query = $this->url . '/json-api/changepackage?api.version=1&user=' . urlencode($username) . '&pkg=' . urlencode($plan);
        return $this->resolveCurl($query);
    }
    public function accountsummary($username)
    {
        $query = $this->url . '/json-api/accountsummary?api.version=1&user=' . urlencode($username);
        return $this->resolveCurl($query);
    }
    public function CreateUserSession($username)
    {
        $query = $this->url . '/json-api/create_user_session?api.version=1&user=' . urlencode($username) . '&service=cpaneld';
        return $this->resolveCurl($query);
    }
    public function cpanelMysqlCreateDatabase($username, $database, $response = true)
    {
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_database&cpanel_jsonapi_apiversion=3&name=' . urlencode($database);
        return $this->resolveCurl($query, $response);
    }
    public function cpanelMysqlCreateUser($username, $database_user, $password, $response = true)
    {
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_user&cpanel_jsonapi_apiversion=3&name=' . urlencode($database_user) . '&password=' . urlencode($password);
        return $this->resolveCurl($query, $response);
    }
    public function cpanelMysqlSetPrivilegesOnDatabase($username, $database_user, $database, $response = true)
    {
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=set_privileges_on_database&cpanel_jsonapi_apiversion=3&user=' . urlencode($database_user) . '&database=' . urlencode($database) . '&privileges=ALL';
        return $this->resolveCurl($query, $response);
    }
    public function cpanelLangPhpSetVhostVersions($username, $domain, $version = 'alt-php73')
    {

        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=LangPHP&cpanel_jsonapi_func=php_set_vhost_versions&cpanel_jsonapi_apiversion=3&version=' . urlencode($version) . '&vhost=' . urlencode($domain);
        return $this->resolveCurl($query);
    }
}
