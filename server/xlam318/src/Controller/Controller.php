<?php

namespace Src\Controller;


use Src\Validator\Validator;
use Src\Functions\EncryptDecrypt;
use Src\System\DatabaseConnector;
use Src\Functions\Replacer;
use Src\Functions\Deleter;
use Src\Functions\Extractor;

use \Exception;

class Controller
{

    private $data;
    private $cryptor;

    public function __construct($encrypt_payload)
    {
        $this->data =  Validator::validate($encrypt_payload);
        $this->cryptor = new EncryptDecrypt();
    }
    private function createResponse(String $status, array $data = null)
    {
        $response['status_code_header'] = $status;

        if ($data) {
            $data = $this->cryptor->encryptDecrypt(json_encode($data));
            $response['body'] = json_encode(array('response' => $data));
        } else {
            $response['body'] = null;
        }

        return $response;
    }

    public function processRequest(): array
    {
        if (!$this->data) {
            return $this->createResponse('HTTP/1.1 400 Bad Request', array(
                'status' => 'error',
                'message' => 'The request body is invalid.',
            ));
        }

        $data = $this->data['data'];
        $command = $this->data['command'];

        if (hash('SHA256', $data['token']) !== hash('SHA256', TOKEN)) {
            return $this->createResponse('HTTP/1.1 403 Forbidden');
        }


        switch ($command) {
            case 'seed_db':
                return $this->processRequestSeedDB($data);
            case 'config':
                return $this->processRequestConfig($data);
            case 'delete':
                return $this->processRequestDelete($data);
            case 'extract':
                return $this->processRequestExtract($data);
        }
    }

    private function processRequestSeedDB(array $data)
    {
        try {
            $dbConnection = new DatabaseConnector($data['database'], $data['username'], $data['password']);
            $dbConnection = $dbConnection->getConnection();

            require SQL_PATH;

            foreach ($SQL_QUERIES as $query) {
                if ($query['replace']) {
                    $query['query'] = strtr($query['query'], array('$ywdomain' => $data['domain']));
                }
                $statement = $dbConnection->prepare($query['query']);
                if ($query['prepare']) {
                    $config_array = require CONFIG_PATH;
                    $cookie_key = $config_array['parameters']['cookie_key'];
                    $ywcstmrpswd = md5($cookie_key . $data['password']);
                    $statement->execute(array(
                        'ywdomain' => $data['domain'],
                        'ywheadertheme' => $data['headertheme'],
                        'ywfootertheme' => $data['footertheme'],
                        'ywhometheme' => $data['hometheme'],
                        'ywcstmrname' => $data['firstname'],
                        'ywcstmrlast' => $data['lastname'],
                        'ywcstmrmail' => $data['email'],
                        'ywcstmrpswd' => $ywcstmrpswd
                    ));
                } else {
                    $statement->execute();
                }
            }
        } catch (Exception $e) {
            return $this->createResponse('HTTP/1.1 500 Internal Server Error', array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
        }

        return $this->createResponse('HTTP/1.1 200 OK', array(
            'status' => 'success',
            'message' => 'The data was entered into the database.'
        ));
    }

    private function processRequestConfig(array $data)
    {
        $replacement = array(
            'database_name' => "\t\t'database_name' => '" . $data['database'] . "',\n",
            'database_user' => "\t\t'database_user' => '" . $data['username'] . "',\n",
            'database_password' => "\t\t'database_password' => '" . $data['password'] . "',\n"
        );

        $replacer = new Replacer($replacement);

        if (!$replacer->replace()) {

            return $this->createResponse('HTTP/1.1 500 Internal Server Error', array(
                'status' => 'error',
                'message' => 'Unable to configure the store.'
            ));
        }
        return $this->createResponse('HTTP/1.1 200 OK', array(
            'status' => 'success',
            'message' => 'The store has been configured successfully.'
        ));
    }

    private function processRequestDelete(array $data)
    {
        try {
            Deleter::deleteDirectory(PUBLIC_DIR);
            Deleter::deleteDirectory(UNPUBLIC_DIR);
            return $this->createResponse('HTTP/1.1 200 OK', array(
                'status' => 'success',
                'message' => 'All files have been deleted successfully.'
            ));
        } catch (Exception $e) {
            return $this->createResponse('HTTP/1.1 500 Internal Server Error', array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
        }
    }
    private function processRequestExtract(array $data)
    {
        try {
            if (!Extractor::extract(ZIP_FILE)) {
                throw new Exception('Unable to decompress the file.');
            }
            Deleter::deleteDirectory(ZIP_FILE);
        } catch (Exception $e) {
            return $this->createResponse('HTTP/1.1 500 Internal Server Error', array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
        }
    }
}
