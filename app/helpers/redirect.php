<?php

function redirect($response, $to, $status = 200)
{
    return $response->withHeader('Location', $to)->withStatus(302);    
}