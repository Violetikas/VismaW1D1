<?php


namespace Fikusas\Patterns;


use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\DatabaseConnectorInterface;

class PatternLoaderDb implements PatternLoaderInterface
{
    /** @var DatabaseConnectorInterface */
    private $dbConfig;

    /**
     * PatternLoaderDb constructor.
     * @param DatabaseConnectorInterface $dbConfig
     */
    public function __construct(DatabaseConnectorInterface $dbConfig)
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