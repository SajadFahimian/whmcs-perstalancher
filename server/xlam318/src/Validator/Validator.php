<?php

namespace Src\Validator;

use Src\Functions\EncryptDecrypt;

class Validator
{

    const allowed_commands = array('seed_db', 'config', 'delete', 'extract');


    static private function validateData(string $command, array $data)
    {
        $validated_data = null;
        switch ($command) {
            case 'seed_db':
                $validated_data = self::validateSeedDB($data);
                break;

            case 'config':
                $validated_data = self::validateConfig($data);
                break;
            case 'delete':
            case 'extract':
                $validated_data = self::validateGeneral($data);
                break;


        }
        return $validated_data;
    }
    static private function validateSeedDB(array $data)
    {
        $validated_data = null;

        if (
            isset($data['token']) &&
            isset($data['database']) &&
            isset($data['username']) &&
            isset($data['password']) &&
            isset($data['domain']) &&
            isset($data['theme']) &&
            isset($data['firstname']) &&
            isset($data['lastname']) &&
            isset($data['email']) &&
            in_array($data['theme'], ALLOWED_HOME_PAGES)
        ) {
            $validated_data['token'] = $data['token'];
            $validated_data['database'] = $data['database'];
            $validated_data['username'] = $data['username'];
            $validated_data['password'] = $data['password'];
            $validated_data['domain'] = $data['domain'];
            $validated_data['firstname'] = $data['firstname'];
            $validated_data['lastname'] = $data['lastname'];
            $validated_data['email'] = $data['email'];

            $theme = HOME_PAGES['home_' . $data['theme']];

            $validated_data['headertheme'] = $theme['header_style'];
            $validated_data['footertheme'] = $theme['footer_style'];
            $validated_data['hometheme'] = $theme['home_page'];
        }

        return $validated_data;
    }
    static private function validateConfig(array $data)
    {
        $validated_data = null;

        if (
            isset($data['token']) &&
            isset($data['database']) &&
            isset($data['username']) &&
            isset($data['password'])
        ) {
            $validated_data['token'] = $data['token'];
            $validated_data['database'] = $data['database'];
            $validated_data['username'] = $data['username'];
            $validated_data['password'] = $data['password'];
        }

        return $validated_data;
    }
    static private function validateGeneral(array $data)
    {
        $validated_data = null;

        if (
            isset($data['token'])
        ) {
            $validated_data['token'] = $data['token'];
        }

        return $validated_data;
    }

    static public function validate(string $encrypt_payload)
    {
        $cryptor = new EncryptDecrypt();
        $encrypt_payload = urldecode($encrypt_payload);
        $decrypt_payload = (array) json_decode($cryptor->encryptDecrypt($encrypt_payload, 'decrypt'), TRUE);

        $validated_data = null;
        $return_data = null;

        $data = isset($decrypt_payload['data']) ? $decrypt_payload['data'] : null;
        $command = isset($decrypt_payload['command']) ? $decrypt_payload['command'] : null;

        if ($command && in_array($command, self::allowed_commands) && $data && is_array($data)) {
            $return_data = self::validateData($command, $data);
        } 

        if ($return_data) {
            $validated_data['data'] = $return_data;
            $validated_data['command'] = $command;

        } 
        return $validated_data;
    }
}
