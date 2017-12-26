<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 20/12/2017
 * Time: 22:30
 */

use Doctrine\Common\Cache\CacheProvider;
use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\Cache\RegionsConfiguration;
use \Doctrine\ORM\Cache\DefaultCacheFactory;
use \Doctrine\DBAL\Logging\SQLLogger;

use \Application\Routing\RouteFactory;
use \Application\Command\RequestDataFactory;

use \Interop\Container\ContainerInterface;

return [
    EntityManager::class => function (ContainerInterface $c) : EntityManager {
        $paths = [
            ROOT.'/.runtime_generated/doctrine/cache'
        ];
        $dev_mode = FALSE;
        $config = Setup::createAnnotationMetadataConfiguration($paths, $dev_mode);
        $cache = $c->get(CacheProvider::class);
        $regionConfig = new RegionsConfiguration;
        $factory = new DefaultCacheFactory($regionConfig, $cache);
        $config->setSecondLevelCacheEnabled();
        $config->getSecondLevelCacheConfiguration()->setCacheFactory($factory);
        $config->setQueryCacheImpl($cache);
        $config->setResultCacheImpl($cache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__ . '/Application/Entity', $dev_mode));
        $config->setMetadataCacheImpl($cache);
        $config->setProxyDir(__DIR__ . '/../.runtime_generated/doctrine/proxy');
        $config->setAutoGenerateProxyClasses(FALSE);

        $config->setSQLLogger(new class implements SQLLogger {
            public function startQuery($sql, array $params = null, array $types = null)
            {
                $event = '';
                $event .= $sql.PHP_EOL;

                if ($params) {
                    $event .= 'params : '.print_r($params, TRUE).PHP_EOL;
                }

                if ($types) {
                    $event .= 'types : '.print_r($types, TRUE).PHP_EOL;
                }

                $f = fopen(ROOT.\date("y-m-d",time()).".sql.log","a+");
                fwrite($f,\date("h:m:s",time()) .' :'.PHP_EOL.$event.PHP_EOL.PHP_EOL);
                fclose($f);
            }

            public function stopQuery()
            {
            }
        });
        return EntityManager::create($c->get('data-unit'), $config);
    },
    RouteFactory::class => function(ContainerInterface $container): RouteFactory {
        return RouteFactory::create($container->get(CacheProvider::class), $container->get('mvc')['controllers-path']);
    },
    RequestDataFactory::class => function(ContainerInterface $container): RequestDataFactory {
        return RequestDataFactory::create($container->get(CacheProvider::class));
    }
];