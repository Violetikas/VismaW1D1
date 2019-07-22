<?php

namespace Fikusas\DB;

use Fikusas\Config\ConfigInterface;
use PDO;

interface DatabaseConnectorInterface
{
    /**
     * DatabaseConnector constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config);

    /**
     * @return PDO
     * @throws \Fikusas\Config\Error\ParameterNotFoundError
     */
    public function getConnection(): PDO;
}