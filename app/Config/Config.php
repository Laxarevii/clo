<?php

namespace App\Config;

class Config
{
    public function __construct(protected array $settings)
    {
    }

    public function get($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    public function all()
    {
        return $this->settings;
    }
}
