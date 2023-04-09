<?php

namespace Src\Controller;


use Src\Validator\Validator;
use Src\Functions\EncryptDecrypt;



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

    public function processRequest()
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


        switch($command) {
            case 'seed_db':
                return $this->processRequestSeedDB($data);
        }
    }

    private function processRequestSeedDB(array $data) {
        

    }
}
