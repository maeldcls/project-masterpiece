<?php
// src/Service/MyService.php

namespace App\Service;

class MyService
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
}
