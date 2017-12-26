<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 17/01/2017
 * Time: 12:12
 */

namespace Application\Http\Exception;

class BadRequestException extends \RuntimeException {
    public function __construct(
        string $controller,
        string $action,
        string $post_request
    ) {
        parent::__construct(
            sprintf(
                '%s::%s() expects %s request : unkown given.',
                $controller,
                $action,
                $post_request
            )
        );
    }
}