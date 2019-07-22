<?php


namespace Fikusas\DB;

use Fikusas\Cache\FileCache;
use PDOException;
use PDO;
use Fikusas\Hyphenation\WordHyphenator;
use Psr\SimpleCache\CacheInterface;

class WordDB
{
    private $dbConfig;


    public function __construct(DatabaseConnector $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function isWordSavedToDB(string $word): bool
    {
        $pdo = $this->dbConfig->getConnection();
        $query = $pdo->prepare("SELECT `word_id` FROM `Words` WHERE `word` =:word;");
        if (!$query->execute(array('word' => $word))) {
            return false;
        }
        return $query->rowCount() == 1;
    }

    public function getHyphenatedWordFromDB(string $word): ?string
    {

        $pdo = $this->dbConfig->getConnection();

        $query = $pdo->prepare('SELECT HyphenatedWords.hyphenatedWord
        FROM HyphenatedWords INNER JOIN Words ON HyphenatedWords.word_id = Words.word_id WHERE word=:word;');

        if ($query->execute(array('word' => $word))) {
            return $query->fetch(PDO::FETCH_ASSOC)['hyphenatedWord'];
        }

    }

    public function writeWordToDB(string $word): void
    {

        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare("REPLACE INTO Words(word) VALUES (?)");
        try {
            $pdo->beginTransaction();
            if (!$this->isWordSavedToDB($word)) {
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
                if (!$this->isWordSavedToDB($words[$i])) {

                    $stmt->execute([$words[$i]]);
                }
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

    public function writeHyphenatedWordToDB($word, $hyphenatedWord)
    {
        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare('REPLACE INTO HyphenatedWords (word_id, hyphenatedWord)
        VALUES ((SELECT word_id FROM Words WHERE word = ?), ?)');
        try {
            $pdo->beginTransaction();
            $stmt->execute([$word, $hyphenatedWord]);
            $pdo->commit();

        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }


    }

    public function storeWordsPatternsIDs(string $word, array $syllables): void
    {
        $pdo = $this->dbConfig->getConnection();
        $stmt = $pdo->prepare('INSERT INTO WordsAndPatternsID (word_id, pattern_id)
        VALUES ((SELECT word_id FROM Words WHERE word = ?), (SELECT pattern_id FROM Patterns WHERE pattern = ?))');

        try {
            $pdo->beginTransaction();
            foreach ($syllables as $pattern) {
                $stmt->execute([$word, $pattern]);
            }
            $pdo->commit();
        } catch (PDOException $exception) {
            $pdo->rollback();
            throw $exception;
        }
    }

    public function selectPatternsUsed($word): array
    {
        $pdo = $this->dbConfig->getConnection();
        $query = $pdo->prepare("select pattern from Words
        inner join WordsAndPatternsID on Words.word_id = WordsAndPatternsID.word_id
        inner join Patterns on WordsAndPatternsID.pattern_id = Patterns.pattern_id
        where word = ?");
        $query->execute([$word]);

        return $query->fetchAll();
    }

    public function deleteWord(string $word)
    {
        $pdo = $this->dbConfig->getConnection();
        $query = $pdo->prepare("delete from Words
        where word = ?");
        $query->execute([$word]);
    }
}