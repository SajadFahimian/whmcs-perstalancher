<?php

namespace WHMCS\Module\Server\PerstaShopLancherYoozweb;

use WHMCS\Module\Server\PerstaShopLancherYoozweb\Config;
use \Exception;


class Whm
{
    const CURL_TIMEOUT = 6300;
    const CONNECT_TIMEOUT = 60;
    protected $url;
    protected $curl;

    protected $host;
    protected $port;
    protected $ip;
    protected $user;
    protected $token;

    public function __construct($prefix, $host, $port, $ip, $user, $token)
    {
        $this->host = $host;
        $this->port = $port;
        $this->ip = $ip;
        $this->user = $user;
        $this->token = $token;

        $this->curl = $this->createCurl();

        $this->url = $prefix . '://' . $host . ':' . $port;
    }
    protected function createCurl()
    {
        $curl_resolve = [$this->host . ':' . $this->port . ':' . $this->ip];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($curl, CURLOPT_RESOLVE, $curl_resolve);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $header[0] = 'Authorization: whm ' . $this->user . ':' . $this->token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        return $curl;
    }
    public function resolveCurl($url)
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


        return $result_array;
    }

    public function listpkgs()
    {
        $query = $this->url . '/json-api/listpkgs?api.version=1&want=creatable';
        return $this->resolveCurl($query);
    }
    public function createacct($username, $domain, $plan)
    {
        $query = $this->url . '/json-api/createacct?api.version=1&username=' . urlencode($username) . '&domain=' . urlencode($domain) . '&plan=' . urlencode($plan);
        return $this->resolveCurl($query);
    }
    public function cpanelMysqlCreateDatabase($username, $database)
    {
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_database&cpanel_jsonapi_apiversion=3&name=' . urlencode($database);
        return $this->resolveCurl($query);
    }
    public function getQueryCpanelMysqlCreateDatabase($username, $database)
    {
        return $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_database&cpanel_jsonapi_apiversion=3&name=' . urlencode($database);
    }
    public function cpanelMysqlCreateUser($username, $database_user, $password)
    {
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_user&cpanel_jsonapi_apiversion=3&name=' . urlencode($database_user) . '&password=' . urlencode($password);
        return $this->resolveCurl($query);
    }
    public function getQueryCpanelMysqlCreateUser($username, $database_user, $password)
    {
        return $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=create_user&cpanel_jsonapi_apiversion=3&name=' . urlencode($database_user) . '&password=' . urlencode($password);
    }
    public function cpanelMysqlSetPrivilegesOnDatabase($username, $database_user, $database)
    {
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Mysql&cpanel_jsonapi_func=set_privileges_on_database&cpanel_jsonapi_apiversion=3&user=' . urlencode($database_user) . '&database=' . urlencode($database) . '&privileges=ALL';
        return $this->resolveCurl($query);
    }
    public function cpanelBackupRestoreFiles($username)
    {
        $backup_file = '/home/' . $username . '/' . Config::$archive_file;
        $unzip_directory = '/home/' . $username . '/public_html/';
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Backup&cpanel_jsonapi_func=restore_files&cpanel_jsonapi_apiversion=3&backup=' . urlencode($backup_file) . '&directory=' . urlencode($unzip_directory);
        return $this->resolveCurl($query);
    }
    public function getQueryCpanelBackupRestoreFiles($username)
    {
        $backup_file = '/home/' . $username . '/' . Config::$archive_file;
        $unzip_directory = '/home/' . $username . '/public_html/';
        return $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Backup&cpanel_jsonapi_func=restore_files&cpanel_jsonapi_apiversion=3&backup=' . urlencode($backup_file) . '&directory=' . urlencode($unzip_directory);
    }
    public function cpanelBackupRestoreDatabases($username)
    {
        $backup_database = '/home/' . $username . '/' . Config::$postfix . '.sql';
        $query = $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Backup&cpanel_jsonapi_func=restore_databases&cpanel_jsonapi_apiversion=3&backup=' . urlencode($backup_database);
        return $this->resolveCurl($query);
    }
    public function getQueryCpanelBackupRestoreDatabases($username)
    {
        $backup_database = '/home/' . $username . '/' . Config::$postfix . '.sql';
        return $this->url . '/json-api/cpanel?api.version=1&cpanel_jsonapi_user=' . urlencode($username) . '&cpanel_jsonapi_module=Backup&cpanel_jsonapi_func=restore_databases&cpanel_jsonapi_apiversion=3&backup=' . urlencode($backup_database);
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }
}
