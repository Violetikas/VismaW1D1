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

    public function writeWordsToDB (array $words):void
    {

        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare("INSERT INTO Words_from_file (words) VALUES (?)");


        try {
            $pdo->beginTransaction();
            foreach ($words as $word) {
                $stmt->execute([$word]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

}