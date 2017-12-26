<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 13/01/2017
 * Time: 18:30
 */

namespace Application\Http\Exception;

class RouteNotFoundException extends NotFoundException {

    public function __construct(string $className){
        parent::__construct(sprintf(
            'Route "%s" does not exist',
            $className
        ));
    }
}