<?php

namespace Src\Validator;

use Src\Functions\EncryptDecrypt;
require('./../../config.php');

class DataValidator
{
    public $status;
    public $message = null;
    public $data = null;

    public function __construct(String $input)
    {
        $cryptor = new EncryptDecrypt();
        $decrypt_data = json_decode($cryptor->encryptDecrypt($input, 'decrypt'), TRUE);

        if ((!is_array($decrypt_data) ||
            !isset($decrypt_data['token']) ||
            !isset($decrypt_data['database']) ||
            !isset($decrypt_data['username']) ||
            !isset($decrypt_data['password']) ||
            !isset($decrypt_data['domain']) ||
            !isset($decrypt_data['theme']) ||
            !isset($decrypt_data['firstname']) ||
            !isset($decrypt_data['lastname']) ||
            !isset($decrypt_data['email']) ||
            !in_array($decrypt_data['theme'], ALLOWED_HOME_PAGES, TRUE)

        ) && (!is_array($decrypt_data) ||
            !isset($decrypt_data['token']) ||
            !isset($decrypt_data['delete'])
        )) {
            $this->status = "error";
            $this->message = "The request body is invalid.";
        } else {
            $this->status = "success";
            $this->data = $decrypt_data;
        }
    }
}
