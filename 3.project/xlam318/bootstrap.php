<?php

require 'vendor/autoload.php';
require 'sql.php';
require 'config.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
