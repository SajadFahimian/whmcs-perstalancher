<?php


namespace WHMCS\Module\Server\PerstaShopLancherYoozweb;

use WHMCS\Module\Server\PerstaShopLancherYoozweb\Whm;

use \Exception;


// $ch = curl_init();
// $data = array('Frome' => 'GetAllPackages', 'Data' => $params);
// curl_setopt($ch, CURLOPT_URL, 'https://my.youzweb.ir/cv654kh/');
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// curl_exec($ch);

// curl_close($ch);


class ASWhm extends Whm
{
    protected $mh;
    protected $handles = array();


    public function __construct($prefix, $host, $port, $ip, $user, $token)
    {
        $mh = curl_multi_init();

        parent::__construct($prefix, $host, $port, $ip, $user, $token);
    }

    public function resolveCurlMulti()
    {
        $results = array();
        $active = null;
        do {
            $status = curl_multi_exec($this->mh, $active);
            if ($active) {
                curl_multi_select($this->mh);
            }
        } while ($active && $status == CURLM_OK);

        foreach ($this->handles as $function => $curl) {
            $result = curl_multi_getcontent($curl);
            $url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

            if (curl_error($curl)) {
                throw new Exception('Unable to connect: ' . curl_errno($curl) . ' - ' . curl_error($curl));
            } elseif (empty($result)) {
                throw new Exception('Empty response for' . $url);
            }

            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (!in_array($http_status, Config::$statusCodes)) {
                throw new Exception('error ' . $http_status . '. for ' . $url);
            }
            $results[$function] = json_decode($result, true);
        }

        return $results;
    }

    protected function addHandle($url, $function)
    {
        $curl = $this->createCurl();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_multi_add_handle($this->mh, $curl);
        $this->handles[$function] = $curl;
    }

    public function addHandleCpanelMysqlCreateDatabase($username, $database)
    {
        $this->addHandle($this->getQueryCpanelMysqlCreateDatabase($username, $database), __FUNCTION__);
        return $this;
    }

    public function addHandleCpanelMysqlCreateUser($username, $database_user, $password)
    {
        $this->addHandle($this->getQueryCpanelMysqlCreateUser($username, $database_user, $password), __FUNCTION__);
        return $this;
    }

    public function addHandleCpanelBackupRestoreFiles($username)
    {
        $this->addHandle($this->getQueryCpanelBackupRestoreFiles($username), __FUNCTION__);
        return $this;
    }

    public function addHandleCpanelBackupRestoreDatabases($username)
    {
        $this->addHandle($this->getQueryCpanelBackupRestoreDatabases($username), __FUNCTION__);
        return $this;
    }

    public function __destruct()
    {
        foreach ($this->handles as $function => $curl) {
            curl_multi_remove_handle($this->mh, $curl);
        }

        curl_multi_close($this->mh);

        parent::__destruct();
    }
}
