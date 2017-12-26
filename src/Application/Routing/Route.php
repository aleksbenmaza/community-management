<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 13/01/2017
 * Time: 15:38
 */

namespace Application\Routing;

final class Route {
    const
        GET    = 'GET',
        POST   = 'POST',
        PUT    = 'PUT',
        DELETE = 'DELETE',
        HEAD   = 'HEAD';
    private
        $pattern      = '',
        $methods      = [],
        $parameters   = [],
        $controller   = '',
        $action       = '';

    public function match(string $url): ? array {
        if($url === $this->pattern)
            return [];
        else if (($url !== $this->pattern) && !$this->pattern)
            return NULL;
        $pattern = str_replace('-','\-',$this->pattern);
        if(!preg_match('#^'.$pattern.'$#', $url, $matches))
            return NULL;
        $tmp_matches = $matches;
        foreach($tmp_matches as $key => $value)
            if(is_int($key))
                unset($tmp_matches[$key]);
        $params = [];
        foreach($matches as $match)
            $params[] = trim($match[0],'/');
        if(!$params)
            return NULL;
        return $params;
    }

    public function setPattern(string $pattern): void {
        $this->pattern = $pattern;
    }

    public function getPattern(): string {
        return $this->pattern;
    }

    public function setMethods(array $methods): void {
        $this->methods = $methods;
    }

    public function getMethods(): array {
        return $this->methods;
    }

    public function setParameters(array $parameters): void {
        $this->parameters = $parameters;
    }

    public function getParameters(): array {
        return $this->parameters;
    }

    public function setController(string $controller): void {
        $this->controller = $controller;
    }

    public function getController(): string {
        return $this->controller;
    }

    public function setAction(string $action): void {
        $this->action = $action;
    }

    public function getAction(): string {
        return $this->action;
    }
}