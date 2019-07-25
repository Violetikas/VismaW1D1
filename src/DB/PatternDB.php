<?php


namespace Fikusas\DB;

use PDOException;


class PatternDB
{
    private $dbConfig;

    public function __construct(DatabaseConnectorInterface $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function writeToDB(array $patterns): void
    {
        $pdo = $this->dbConfig->getConnection();

        $writer = $pdo->prepare("INSERT INTO Patterns (pattern) VALUES (?)");

        try {
            $pdo->beginTransaction();
            $pdo->exec("DELETE FROM `Patterns`");
            $pdo->exec("DELETE FROM `Words`");
            foreach ($patterns as $row) {
                $writer->execute([$row]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }


    public function getFromDB($word): array
    {
        $pdo = $this->dbConfig->getConnection();
        $selector = $pdo->prepare("select pattern from Words
        inner join WordsAndPatternsID on Words.word_id = WordsAndPatternsID.word_id
        inner join Patterns on WordsAndPatternsID.pattern_id = Patterns.pattern_id
        where word = ?");
        $selector->execute([$word]);

        return $selector->fetchAll();
    }




}
