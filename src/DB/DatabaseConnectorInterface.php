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

    public function getConnection(): PDO;
}