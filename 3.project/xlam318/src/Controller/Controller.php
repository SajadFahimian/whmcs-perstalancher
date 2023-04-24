<?php

namespace Src\Controller;

use Src\Functions\Deleter;
use Src\Functions\EncryptDecrypt;
use Src\Functions\Replacer;

use Src\Validator\PayloadValidator;
use Src\Validator\DataValidator;
use Src\System\DatabaseConnector;

use \PDO;
use \PDOException;


class Controller
{
    private $token;
    private $cryptor;
    private $data;
    private $response = null;
    private $path;
    private $SQL;
    private $HOME_PAGES;
    private $ALLOWED_HOME_PAGES;
    public function __construct($path, $SQL_STR, $HOME_PAGES, $ALLOWED_HOME_PAGES)
    {
        $this->cryptor = new EncryptDecrypt();
        $this->data = (array) json_decode(file_get_contents('php://input'), TRUE);
        $this->path = $path;
        $this->token = hash('SHA256', getenv('TOKEN'));
        $this->SQL = $SQL_STR;
        $this->HOME_PAGES = $HOME_PAGES;
        $this->ALLOWED_HOME_PAGES = $ALLOWED_HOME_PAGES;
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
    private function validate($vlidator)
    {
        if ($vlidator->status === 'error') {
            $this->response = $this->createResponse('HTTP/1.1 422 Unprocessable Entity', array(
                'status' => $vlidator->status,
                'message' => $vlidator->message
            ));
        } else {
            $this->data = $vlidator->data;
        }
    }
    public function processRequest()
    {
        $this->validate(new PayloadValidator($this->data));
        if ($this->response) {
            return $this->response;
        }
        $this->validate(new DataValidator($this->data, $this->ALLOWED_HOME_PAGES));
        if ($this->response) {
            return $this->response;
        }

        if ($this->token !== hash('SHA256', $this->data['token'])) {
            return $this->createResponse('HTTP/1.1 403 Forbidden');
        }


        if (isset($this->data['delete'])) {
            Deleter::deleteDirectory($this->path);
            return $this->createResponse('HTTP/1.1 200 OK');
        } else {
            $config_path = dirname($this->path, 1) . getenv('CONFIG_PATH');

            if (!file_exists($config_path)) {
                return $this->createResponse('HTTP/1.1 500 Internal Server Error', array(
                    'status' => 'error',
                    'message' => "Cannot find configuration file in $config_path"
                ));
            }

            $database = $this->data['database'];
            $username = $this->data['username'];
            $password = $this->data['password'];

            $theme = $this->HOME_PAGES['home_' . $this->data['theme']];

            $ywheadertheme = $theme['header_style'];
            $ywfootertheme = $theme['footer_style'];
            $ywhometheme = $theme['home_page'];

            $ywcstmrname = $this->data['firstname'];
            $ywcstmrlast = $this->data['lastname'];
            $ywcstmrmail = $this->data['email'];

            $config_array = include ($config_path);
            $cookie_key = $config_array['parameters']['cookie_key'];

            $ywcstmrpswd = md5($cookie_key . $password);

            $ywdomain = $this->data['domain'];

            $sql = strtr($this->SQL, array('$ywdomain' => $ywdomain));


            // TODO
            $dbConnection = new DatabaseConnector($database, $username, $password);


            if ($dbConnection->status === 'error') {
                $response['Database connection'] = array(
                    'status' => $dbConnection->status,
                    'message' => $dbConnection->message
                );
                return $this->createResponse('HTTP/1.1 500 Internal Server Error', $response);
            }
            $dbConnection = $dbConnection->getConnection();
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $response['Database connection'] = array(
                'status' => 'success',
                'message' => null
            );

            try {

                $statement = $dbConnection->prepare($sql);
                $statement->execute(array(
                    'ywdomain' => $ywdomain,
                    'ywheadertheme' => $ywheadertheme,
                    'ywfootertheme' => $ywfootertheme,
                    'ywhometheme' => $ywhometheme,
                    'ywcstmrname' => $ywcstmrname,
                    'ywcstmrlast' => $ywcstmrlast,
                    'ywcstmrmail' => $ywcstmrmail,
                    'ywcstmrpswd' => $ywcstmrpswd
                ));

                $response['Insert data'] = array(
                    'status' => 'success',
                    'message' => null
                );
            } catch (PDOException $e) {
                $response['Insert data'] = array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                );

                return $this->createResponse('HTTP/1.1 500 Internal Server Error', $response);
            }

            $replacement = array(
                'database_name' => "'database_name' => '$database',\n",
                'database_user' => "'database_user' => '$username',\n",
                'database_password' => "'database_password' => '$password',\n"
            );

            $replacer = new Replacer($this->path, $replacement);

            if (!$replacer->replace()) {
                $response['Edit file'] = array(
                    'status' => 'error',
                    'message' => null
                );

                return $this->createResponse('HTTP/1.1 500 Internal Server Error', $response);
            }
            $response['Edit file'] = array(
                'status' => 'success',
                'message' => null
            );

            return $this->createResponse('HTTP/1.1 200 OK', $response);
        }
    }
}
