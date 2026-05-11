<?php

namespace App\Encoder;

class JsonEncoder
{
    public function encode(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}