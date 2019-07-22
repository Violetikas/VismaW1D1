<?php


namespace Fikusas\Patterns;


use Fikusas\DB\DatabaseConnector;

class PatternLoaderDb implements PatternLoaderInterface
{
    /** @var DatabaseConnector */
    private $dbConfig;

    /**
     * PatternLoaderDb constructor.
     * @param DatabaseConnector $dbConfig
     */
    public function __construct(DatabaseConnector $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function loadPatterns(): array
    {
        $db = $this->dbConfig->getConnection();
        $stmt = $db->prepare('select pattern from Patterns');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}