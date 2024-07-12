<?php

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    $message = "Error [$errno] $errstr on line $errline in file $errfile";
    error_log($message);

    if (ini_get('display_errors')) {
        echo "<p>An error occurred. Please try again later.</p>";
    }
}

set_error_handler("customErrorHandler");
