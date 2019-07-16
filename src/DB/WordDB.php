<?php


namespace Fikusas\DB;

use PDOException;

class WordDB
{
    private $dbConfig;

    /**
     * PatternDB constructor.
     * @param DatabaseConnector $dbConfig
     */
    public function __construct(DatabaseConnector $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function writeWordsToDB (array $words, array $hyphenatedWords):void
    {

        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare("INSERT INTO Words(words, hyphenatedWords) VALUES (?,?)");


        try {
            $pdo->beginTransaction();
            for ($i=0; $i<count($words);$i++) {
                $stmt->execute([$words[$i],$hyphenatedWords[$i]]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

}