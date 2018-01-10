<?php namespace Core\Services;


class Csrf
{
    public function set(string $name)
    {
        return $name;
    }

    public function token(string $name)
    {
        return $name;
    }

    public function resolve()
    {
        return true;
    }
}