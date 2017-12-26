<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 12/01/2017
 * Time: 14:36
 */

namespace Application\Routing;
use \Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\VarDumper\VarDumper;

class RouteFactory {
    private static $created = FALSE;
    private $cache;
    private $controllers_path;
    private $routes;

    public static function create(CacheProvider $cache, string $controllers_path) : RouteFactory {
        if(self::$created)
            throw new \RuntimeException(sprintf('%s cannot be instantiated more than once !', self::class));
        self::$created = TRUE;
        return new RouteFactory($cache, $controllers_path);
    }

    private function __construct(CacheProvider $cache, string $controllers_path) {
        $this->cache = $cache;
        $this->controllers_path = $controllers_path;
        $this->loadControllers();
        if($this->cache->contains('routes')) {
            $this->routes = $this->cache->fetch('routes');
        }else{
            $this->parseMetadata();
            $this->cache->save('routes', $this->routes);
        }
    }

    public function __clone(){
        throw new \RuntimeException;
    }

    public function getRoutes(): array {
        return $this->routes;
    }

    private function loadControllers(): void{
        foreach (new \DirectoryIterator(ROOT . $this->controllers_path) as $file) {
            if ($file->isDot() || strpos($file->getFilename(), '.') == 0)
                continue;
            require_once $file->getPathname();
        }
    }

    private function parseMetadata(): void{
        foreach (get_declared_classes() as $class) {
            $reflection_class = new \ReflectionClass($class);
            if (!$reflection_class->isAbstract()) {
                $methods = $reflection_class->getMethods();

                foreach ($methods as $method) {
                    $doc_comment = $method->getDocComment();
                    if (!($doc_comment = trim($doc_comment)))
                        continue;
                    $doc_comment = trim($doc_comment, '/');
                    $doc_comment = str_replace('**', '', $doc_comment);
                    $doc_comment = str_replace('*', ';', $doc_comment);
                    $doc_comment = trim($doc_comment);
                    $doc_comment = trim($doc_comment, ';');
                    $doc_comment = trim($doc_comment);
                    $annotations = explode(';', $doc_comment);
                    $route = NULL;
                    foreach ($annotations as $i => $annotation) {
                        $annotation = trim($annotation);
                        if (preg_match_all('/^(?P<annotation>@[\s\S]+)$/', $doc_comment, $_)) {
                            if (!isset($annotation[0]) || $annotation[0] != '@')
                                continue;
                            $annotation = trim($annotation, '@');
                            preg_match_all('/\w\(([\s\S]*)\)/', $annotation, $matches);
                            $values = $matches[1][0] ?? '';
                            if($values)
                                $annotation = str_replace('('.$values.')', '', $annotation);

                            switch ($annotation) {
                                case 'Route':
                                    $route = new Route;
                                    $route->setAction($method->getName());
                                    $route->setController($reflection_class->getName());
                                    $this->routes[] = $route;
                                    $fields = explode(',', $values, 2);
                                    $route->setParameters($method->getParameters());
                                    foreach($fields as $field){
                                        list($name, $value) = explode('=', $field);
                                        $name  = trim($name);
                                        $value = trim($value);
                                        switch($name) {
                                            case 'pattern':
                                                $route->setPattern(trim($value, '"'));
                                                break;

                                            case 'methods':
                                                $values = trim($value, '{}');
                                                $values = explode(',', $values);
                                                array_walk($values, function(string & $value): void{
                                                    $value = trim($value, ' "');
                                                });
                                                $route->setMethods($values);
                                                break;

                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
}