<?php


namespace Fikusas\FileRead;


use Fikusas\UserInteraction\InputParameters;

class FileReadFromInput
{

    public function fileReadFromInput(InputParameters $userInput)
    {

        $whatever = file_get_contents($userInput->getUserInput());

        $array = explode("\n",$whatever);

        return $array;
    }

}


