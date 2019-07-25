<?php


namespace Fikusas\DB;


use PDOException;

class WordDB
{
    private $dbConfig;

    public function __construct(DatabaseConnectorInterface $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function isSavedToDB(string $word): bool
    {
        $pdo = $this->dbConfig->getConnection();
        $selector = $pdo->prepare("SELECT `word_id` FROM `Words` WHERE `word` =:word;");
        if (!$selector->execute(array('word' => $word))) {
            return false;
        }
        return $selector->rowCount() == 1;
    }

    public function writeToDB(string $word): void
    {

        $pdo = $this->dbConfig->getConnection();
        $writer = $pdo->prepare("REPLACE INTO Words(word) VALUES (?)");
        if (!$this->isSavedToDB($word)) {
            $writer->execute([$word]);
        }
    }

    public function writeWordsToDB(array $words): void
    {

        $pdo = $this->dbConfig->getConnection();

        $writer = $pdo->prepare("REPLACE INTO Words(word) VALUES (?)");
        try {
            $pdo->beginTransaction();

            for ($i = 0; $i < count($words); $i++) {
                if (!$this->isSavedToDB($words[$i])) {

                    $writer->execute([$words[$i]]);
                }
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

    public function deleteFromDB(string $word)
    {
        $pdo = $this->dbConfig->getConnection();
        $deleter = $pdo->prepare("DELETE FROM `Words` WHERE `word`=?");
        $deleter->execute([$word]);
    }

    public function writeWordsPatternsIDs(string $word, array $patterns): void
    {
        $pdo = $this->dbConfig->getConnection();
        $writer = $pdo->prepare('INSERT INTO WordsAndPatternsID (word_id, pattern_id)
        VALUES ((SELECT word_id FROM Words WHERE word = ?), (SELECT pattern_id FROM Patterns WHERE pattern = ?))');

        foreach ($patterns as $pattern) {
            $writer->execute([$word, $pattern]);
        }
    }

}