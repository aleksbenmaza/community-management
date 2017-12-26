<?php

declare(strict_types=1);

namespace Application;

use Application\Http\Response\Response;
use Application\Routing\Dispatcher;
use DI\ContainerBuilder;
use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\Yaml\Yaml;

final class Application
{
    private $container;

    private $dispatcher;

    public function __construct() {
        $cache = require __DIR__ . '/../../src/cache.php';
        $container_builder = new ContainerBuilder;
        $container_builder->useAnnotations(true);
        $container_builder->useAutowiring(true);
        $container_builder->setDefinitionCache($cache);
        $container_builder->addDefinitions(require __DIR__ . '/../../src/bean_factory.php');
        $this->container = $container_builder->build();

        if(!$this->container->has('FILLED')) {
            $this->container->set('FILLED', TRUE);
            foreach (Yaml::parseFile(__DIR__ . '/../application.yml') as $key => $value)
                if (!$this->container->has($key))
                    $this->container->set($key, $value);

            $this->container->set(CacheProvider::class, $cache);
            $this->dispatcher = $this->container->get(Dispatcher::class);
        }
    }

    public function output(string $request_uri, string $request_method, array $request_data) : Response {
        $this->dispatcher->dispatch($this->container, $request_uri, $request_method, $request_data);
        $output = $this->dispatcher->deliver();
        $this->dispatcher->close();
        return $output;
    }

    /**
     * @return \DI\Container
     */
    public function getContainer(): \DI\Container {
        return $this->container;
    }
}
