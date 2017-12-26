<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 13/01/2017
 * Time: 20:35
 */

namespace Application\Http\Exception;

class MethodNotAllowedException extends \RuntimeException {

    public function __construct(
        string $controller,
        string $action,
        array $expected_methods,
        string $request_method
    ){
        parent::__construct(
            sprintf(
                '%s::%s() expects %s method : %s given.',
                $controller,
                $action,
                implode(' or ', $expected_methods),
                $request_method
            )
        );
    }
}