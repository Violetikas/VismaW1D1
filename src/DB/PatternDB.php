<?php


namespace Fikusas\DB;

use PDOException;


class PatternDB
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

    public function writePatternsToDB(array $patterns): void
    {
        $pdo = $this->dbConfig->getConnection();

        $stmt = $pdo->prepare("INSERT INTO Patterns (patterns) VALUES (?)");

        //                              INSERT INTO `Patterns`(`id`, `patterns`) VALUES ([value-1],[value-2]
        try {
            $pdo->beginTransaction();
            foreach ($patterns as $row) {
                $stmt->execute([$row]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }
}
