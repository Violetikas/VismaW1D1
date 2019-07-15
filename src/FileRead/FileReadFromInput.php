<?php


namespace Fikusas\FileRead;

use PDO;

class FileReadFromInput
{
    private $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

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


