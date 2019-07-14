<?php


namespace Fikusas\FileRead;


use Fikusas\UserInteraction\InputParameters;

class FileReadFromInput
{

    public function fileReadFromInput(string $filePath)
    {

        $whatever = file_get_contents($filePath);

        $array = explode("\n",$whatever);

        return $array;
    }

}


