<?php

namespace Fikusas\Config;

class JsonConfigLoader
{
    public static function load(string $path): ConfigInterface
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Config file '{$path}' does not exist!");
        }
        $contentsRaw = file_get_contents($path);
        $valuesDecoded = json_decode($contentsRaw, true);
        return new ArrayConfig($valuesDecoded);
    }
}
