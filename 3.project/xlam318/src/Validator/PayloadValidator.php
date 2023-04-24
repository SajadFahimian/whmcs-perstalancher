<?php

namespace Src\Validator;


class PayloadValidator
{
    public $status;
    public $message = null;
    public $data = null;
    public function __construct(array $input)
    {
        if (!is_array($input) || !isset($input['payload'])) {
            $this-> status = 'error';
            $this-> message='The request body is invalid.';
        } else {
            $this-> status = 'success';
            $this-> data = $input['payload'];
        }

    }
}
