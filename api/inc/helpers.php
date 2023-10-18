<?php

function jsonResponse($response, $data, $status = 200) {
    $response
        ->getBody()
        ->write(json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        ));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
}