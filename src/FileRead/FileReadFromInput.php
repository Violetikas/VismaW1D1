<?php


namespace Fikusas\FileRead;


class FileReadFromInput
{
    /**
     * @param string $filePath
     * @return array
     */
    public function fileReadFromInput(string $filePath)
    {
        $contents = file_get_contents($filePath);

        $array = explode("\n", $contents);

        return $array;
    }
}


