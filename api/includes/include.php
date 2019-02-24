<?php
header('Content-Type: application/json');
require "../../vendor/autoload.php";
use function Sentry\init;
$dotenv = Dotenv\Dotenv::create(dirname(__DIR__, 2));
$dotenv->load();
require "response.php";
require "errorHandler.php";
if (isset($_ENV["SENTRY"]) && (strlen($_ENV[""]) > 0)) {
    \Sentry\init(['dsn' => 'https://cc56931d7f874dab8e11829822f92db5@sentry.io/1401460']);
}
