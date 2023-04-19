<?php

namespace Src\Validator;

use Src\Functions\EncryptDecrypt;

class Validator
{

    const allowed_commands = array('seed_db', 'config', 'delete');


    static private function validateData(string $command, array $data)
    {

        switch ($command) {
            case 'seed_db':
                return self::validateSeedDB($data);


            case 'config':
                return self::validateConfig($data);

            case 'delete':
                return self::validateDelete($data);
        }
        return null;
    }
    static private function validateSeedDB(array $data)
    {
        $validated_data = null;

        if (
            isset($data['token']) &&
            isset($data['database']) &&
            isset($data['username']) &&
            isset($data['password']) &&
            isset($data['query'])
        ) {

            if (
                in_array($data['query'], QUERY_REPLACE) &&
                isset($data['domain'])
            ) {
                $validated_data['domain'] = $data['domain'];
            } elseif (
                $data['query'] == '7' &&
                isset($data['domain']) &&
                isset($data['theme']) &&
                in_array($data['theme'], ALLOWED_HOME_PAGES)
            ) {
                $validated_data['domain'] = $data['domain'];
                $theme = HOME_PAGES['home_' . $data['theme']];

                $validated_data['headertheme'] = $theme['header_style'];
                $validated_data['footertheme'] = $theme['footer_style'];
                $validated_data['hometheme'] = $theme['home_page'];
            } elseif (
                $data['query'] == '8' &&
                isset($data['firstname']) &&
                isset($data['lastname']) &&
                isset($data['email'])

            ) {
                $validated_data['firstname'] = $data['firstname'];
                $validated_data['lastname'] = $data['lastname'];
                $validated_data['email'] = $data['email'];
            } elseif (
                $data['query'] == '9' &&
                isset($data['domain'])

            ) {
                $validated_data['domain'] = $data['domain'];
            } else {
                return null;
            }

            $validated_data['token'] = $data['token'];
            $validated_data['database'] = $data['database'];
            $validated_data['username'] = $data['username'];
            $validated_data['password'] = $data['password'];
            $validated_data['query'] = $data['query'];
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
    static private function validateDelete(array $data)
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
