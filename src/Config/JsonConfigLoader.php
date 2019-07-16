<?php

namespace Fikusas\Config;

class JsonConfigLoader
{
    public static function load(string $path): ConfigInterface
    {
        $contentsRaw = file_get_contents($path);
        $valuesDecoded = json_decode($contentsRaw, true);
        return new ArrayConfig($valuesDecoded);
    }
}
