<?php
use function Sentry\captureException;
function errorHandler($errno, $errstr, $errfile, $errline)
{

    if (!(error_reporting() & $errno)) {
        $errno = 0;
        // // This error code is not included in error_reporting, so let it fall
        // // through to the standard PHP error handler
        // return false;
    }
    $err = "";

    switch ($errno) {
        case E_USER_ERROR:
            $err = "ERROR: [$errno] $errstr.";
            $err .= "Fatal error on line $errline in file $errfile";
            $err .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ").";
            break;

        case E_USER_WARNING:
            $err = "WARNING: [$errno] $errstr";
            break;

        case E_USER_NOTICE:
            $err = "Notice [$errno] $errstr";
            break;

        default:
            $err = "Unknown error type: [$errno] $errstr";
            break;
    }
    logErr($err);
    /* Don't execute PHP internal error handler */
    return true;
}

function logErr($err)
{
    if (isset($_ENV["SENTRY"]) && (strlen($_ENV[""]) > 0)) {
        captureException($err);
    }
    if (!file_exists((dirname(__DIR__, 2) . "/logs"))) {
        mkdir((dirname(__DIR__, 2) . "/logs"), 0777, true);
    }
    file_put_contents((dirname(__DIR__, 2) . "/logs/error.log"), "\n" . (string) $err, FILE_APPEND | LOCK_EX);
    respond(["error" => "An unknown error occured. Try again?"], 500);
}

set_error_handler("errorHandler");
set_exception_handler("logErr");
