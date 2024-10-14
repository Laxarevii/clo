<?php

namespace App\Config;

class Config
{
    public function __construct(protected array $settings)
    {
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }

    public function all()
    {
        return $this->settings;
    }
}
