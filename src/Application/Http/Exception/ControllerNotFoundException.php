<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 30/07/2016
 * Time: 13:18
 */

namespace Application\Http\Exception;

class ControllerNotFoundException extends NotFoundException{
    
    public function __construct(string $className){
        parent::__construct(sprintf(
            'Controller "%s" does not exist',
            $className
        ));
    }
}