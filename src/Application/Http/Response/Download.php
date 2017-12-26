<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 26/07/2016
 * Time: 23:53
 */

namespace Application\Http\Response;

class Download extends Response {
    private $content,
            $meta;
    
    public function __construct(string $content, string $meta){
        $this->content = $content;
        $this->meta = $meta;
    }

    public function __toString(): string{
        header('Content-Type: application/x-download');
        header('Content-Disposition: attachment; '.$this->meta);
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        return $this->content;
    }
}