<?php

function respond(array $arr, $status = 200)
{
    echo json_encode($arr, JSON_PRETTY_PRINT);
    http_response_code(200);
    exit(0);
}
