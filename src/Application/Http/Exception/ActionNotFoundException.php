<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 30/07/2016
 * Time: 13:19
 */

namespace Application\Http\Exception;

class ActionNotFoundException extends NotFoundException{

    public function __construct(string $methodName, string $className){
        parent::__construct(sprintf(
            'Action "%s" does not exist in %s',
            $methodName, $className
        ));
    }
}