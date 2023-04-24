<?php

namespace Src\Functions;

class EncryptDecrypt
{
    private $encrypt_method = 'AES-256-CBC';
    private $secret_key = null;
    private $secret_iv = null;

    public function __construct()
    {
        $this->secret_key = getenv('PRIVATE_KEY');
        $this->secret_iv = getenv('SECRET_KEY');
    }

    public function encryptDecrypt(String $string, String $action = 'encrypt')
    {

        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
        if ($action === 'encrypt') {
            $output = openssl_encrypt($string, $this->encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action === 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $this->encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}
