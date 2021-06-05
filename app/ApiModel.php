<?php

namespace App;

class ApiModel
{
    protected array $properties = [];

    public function __set(string $key, $content)
    {
        $this->properties[$key] = $content;
    }

    public function __get(string $key)
    {
        return $this->properties[$key] ?? null;
    }
}