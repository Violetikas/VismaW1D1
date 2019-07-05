<?php

declare(strict_types=1);

namespace Fikusas;

use RuntimeException;

class FileRead
{
    const FILEPATH = "tex-hyphenation-patterns.txt";

    public function read_values($path = self::FILEPATH)
    {
        if (false !== ($contents = file_get_contents($path))) {
            return explode("\n", $contents);
        } else {
            throw new RuntimeException('Failed to read ' . $path);
        }
    }
}



