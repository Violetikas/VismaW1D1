<?php


namespace Fikusas\DB;


use PDOException;
use PDO;

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
        $query = $pdo->prepare("SELECT `word_id` FROM `Words` WHERE `word` =:word;");
        if (!$query->execute(array('word' => $word))) {
            return false;
        }
        return $query->rowCount() == 1;
    }

    public function writeToDB(string $word): void
    {

        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare("REPLACE INTO Words(word) VALUES (?)");
        try {
            $pdo->beginTransaction();
            if (!$this->isSavedToDB($word)) {
                $stmt->execute([$word]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

    public function writeWordsToDB(array $words): void
    {

        $pdo = $this->dbConfig->getConnection();

        $stmt = $pdo->prepare("REPLACE INTO Words(word) VALUES (?)");
        try {
            $pdo->beginTransaction();

            for ($i = 0; $i < count($words); $i++) {
                if (!$this->isSavedToDB($words[$i])) {

                    $stmt->execute([$words[$i]]);
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
        $query = $pdo->prepare("DELETE FROM `Words` WHERE `word`=?");
        $query->execute([$word]);
    }

    public function writeWordsPatternsIDs(string $word, array $patterns): void
    {
        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare('INSERT INTO WordsAndPatternsID (word_id, pattern_id)
        VALUES ((SELECT word_id FROM Words WHERE word = ?), (SELECT pattern_id FROM Patterns WHERE pattern = ?))');//iskelt atskirus kintamuosius

        try {
            $pdo->beginTransaction();
            foreach ($patterns as $pattern) {

                $stmt->execute([$word, $pattern]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

}