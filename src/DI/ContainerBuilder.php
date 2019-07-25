<?php


namespace Fikusas\DI;


use Fikusas\Cache\FileCache;
use Fikusas\Config\ArrayConfig;
use Fikusas\Config\ConfigInterface;
use Fikusas\Config\JsonConfigLoader;
use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\DatabaseConnectorInterface;
use Fikusas\Hyphenation\CachingHyphenator;
use Fikusas\Hyphenation\DBHyphenator;
use Fikusas\Hyphenation\WordHyphenatorInterface;
use Fikusas\Log\Logger;
use Fikusas\Patterns\PatternLoaderDb;
use Fikusas\Patterns\PatternLoaderInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class ContainerBuilder
{
    public function build(): Container
    {
        $container = new Container();
        $container->setAlias(PatternLoaderInterface::class, PatternLoaderDb::class);
        $container->setAlias(DatabaseConnectorInterface::class, DatabaseConnector::class);
        $container->setAlias(ConfigInterface::class, ArrayConfig::class);
        $container->setDefinition(ArrayConfig::class, function () {
            return JsonConfigLoader::load(__DIR__ . '/../../config.json');
        });
        $container->setAlias(WordHyphenatorInterface::class, CachingHyphenator::class);
        $container->setAlias(CacheInterface::class, FileCache::class);
        $container->setDefinition(FileCache::class, function () {
            return new FileCache(__DIR__.'/../../cache', 86400);
        });
        $container->setAlias(LoggerInterface::class, Logger::class);
        $container->setArgument(CachingHyphenator::class, 'hyphenator', DBHyphenator::class);
        return $container;
    }
}
