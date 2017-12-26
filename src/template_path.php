<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 12/01/2017
 * Time: 10:21
 */

function path(string $template): string {
    if(!file_exists($path = TEMPLATE_ROOT . '/' . $template . '.' . TEMPLATE_EXT))
        throw new class(sprintf('%s does not exist', $path))
            extends \Application\Exception\NotFoundException {
            public function __construct($message, $code = 0, $previous = NULL){
                parent::__construct($message, $code, $previous);
            }
        };
    return $path;
}