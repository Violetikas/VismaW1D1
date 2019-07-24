<?php


namespace Fikusas\Hyphenation;


use Fikusas\Cache\FileCache;
use Fikusas\Config\JsonConfigLoader;
use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\Log\Logger;
use Fikusas\Patterns\PatternLoaderFile;
use Fikusas\TimeKeeping\TimeKeeping;
use Fikusas\UserInteraction\InputOptionParser;

class ProxyHyphenator implements WordHyphenatorInterface
{
    private $timeKeeping;
    private $logger;
    private $input;
    Private $loader;
    private $config;
    private $db;
    private $wdb;
    private $hdb;
    private $pdb;

    public function __construct()
    {
        $this->input = new InputOptionParser();
        $this->timeKeeping = new TimeKeeping();
        $this->logger = new Logger();
        $this->config = JsonConfigLoader::load('config.json');
        $this->loader = new PatternLoaderFile($this->config->getParameter('patterns_file'));
        $this->db = new DatabaseConnector($this->config);
        $this->wdb = new WordDB($this->db);
        $this->hdb = new HyphenatedWordsDB($this->db);
        $this->pdb = new PatternDB($this->db);
    }

    public function hyphenate(string $word): string
    {
        $cache = new FileCache('cache', 86400);

//        $result = new CachingHyphenator(new DBHyphenator(new WordHyphenator($this->loader, $this->db, , ,),
//                    $this->hdb, $this->wdb, $this->pdb, ),
//            $cache, $this->wdb, $this->hdb, $this->pdb, $this->loader);




       return $result;
    }
}