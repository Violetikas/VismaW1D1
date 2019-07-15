<?php


namespace Fikusas\DB;

use Fikusas\FileRead\FileRead;
use Config\DBConfig;
use PDO;


class PatternDB
{

    private $fileRead;
    private $dbConfig;
    private $pdo;


    /**
     * PatternDB constructor.
     * @param FileRead $fileRead
     * @param DBConfig $dbConfig
     * @param PDO $pdo
     */
    public function __construct(FileRead $fileRead, DBConfig $dbConfig, PDO $pdo)
    {
        $this->fileRead = $fileRead;
        $this->dbConfig = $dbConfig;
        $this->pdo = $pdo;
    }


    public function writePatternsToDB(){

        $this->dbConfig->connectToDB();
        $this->pdo->query("CREATE TABLE `Visma1`.`Words_from_file` ( `id` INT(6) NOT NULL AUTO_INCREMENT ,
        `words` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`(6))) ENGINE = InnoDB;");



    }
}