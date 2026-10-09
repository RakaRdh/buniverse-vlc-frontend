<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Api extends BaseConfig
{
    /**
     * Base URL for the VLC Backend/CMS REST API
     */
    public string $baseURL = 'http://localhost:8082/api/';

    /**
     * Request Timeout in seconds
     */
    public int $timeout = 10;
}
