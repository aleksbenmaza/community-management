<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 24/10/2016
 * Time: 14:33
 */

namespace Application\Http\Response;

final class JsonResponse extends Response{
    private
        $data = [],
        $http_code;

    public function __construct($data, int $http_code = 200){
        assert(isset(self::$codes[$http_code]));
        $this->data = $data ;
        $this->http_code = $http_code;
    }

    public function set($key, $data): JsonResponse{
        $this->data[$key] = $data;
        return $this;
    }

    public function __toString(): string{
        header('HTTP/1.0 '.$this->http_code.' '.self::$codes[$this->http_code], TRUE, $this->http_code);
        return json_encode($this->data);
    }
}