<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/07/2016
 * Time: 19:33
 */

namespace Application\Routing;

use Application\Http\Exception\BadRequestException;
use Application\Http\Exception\MethodNotAllowedException;
use Application\Http\Exception\RouteNotFoundException;
use Application\Http\Response\Forwarding;
use Application\Http\Response\Response;
use Interop\Container\ContainerInterface;

/**
 * Class Dispatcher
 * @\DI\Annotation\Injectable
 */
final class Dispatcher {
    /**
     * @var \Application\Routing\RouteFactory
     * @\DI\Annotation\Inject
     */
    private $route_factory;

    /**
     * @var \Application\Command\RequestDataFactory
     * @\DI\Annotation\Inject
     */
    private $request_data_factory;

    private $current_route;

    private $current_method;

    private $current_parameters;

    private $current_session;

    private $container;

    public function dispatch(ContainerInterface $container, string $url, string $method, array $request_data): void {
        $this->container = $container;
        $this->current_method = $method;
        foreach ($this->route_factory->getRoutes() as $route)
            if(($this->current_parameters = $route->match($url)) !== NULL){
                $this->current_route = $route;

                if(($methods = $route->getMethods()) && !in_array($method, $methods))
                    throw new MethodNotAllowedException(
                        $route->getController(),
                        $route->getAction(),
                        $methods,
                        $method
                    );
                foreach ($route->getParameters() as $parameter) {
                    if(!$parameter->getType()->isBuiltin()) {
                        if($parameter->getType()->__toString() == \Application\Http\Session::class)
                            $this->current_parameters[] = $this->current_session = \Application\Http\Session::getInstance();
                        else {
                            if(!($command_object = $this->request_data_factory->map($parameter->getType()->__toString(), $request_data)))
                                throw new BadRequestException(
                                    $route->getController(),
                                    $route->getAction(),
                                    $route->getPostRequest()
                                );
                            $this->current_parameters[] = $command_object;
                        }
                    }
                }
                return;
            }

        throw new RouteNotFoundException($url);
    }

    public function deliver(): Response {
        $response = NULL;
        $controller = $this->current_route->getController();
        $controller = $this->container->get($controller);
        $action = $this->current_route->getAction();
        $params = $this->current_parameters;

        if($params)
            $response = (new \ReflectionMethod($controller, $action))->invokeArgs($controller, $params);
        else
            $response = $controller->$action();

        if($response instanceof Forwarding) {
            $this->dispatch($this->container, $response->getPath(), $this->current_method, $response->getRequestParams());
            $response = $this->deliver();
        }
        return $response;
    }

    public function close(): void {
    }
}