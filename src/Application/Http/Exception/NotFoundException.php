<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 30/07/2016
 * Time: 13:24
 */
namespace Application\Http\Exception;

abstract class NotFoundException extends \RuntimeException {
    public function __construct(
        string $message, int $code = 0, ? \Throwable $previous = NULL){
        parent::__construct($message, $code, $previous);
    }
}