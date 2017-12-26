<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 29/07/2016
 * Time: 12:00
 */

namespace Application\Http\Response;

final class ErrorView extends View{
    
    public function __construct(int $code){
        assert(array_key_exists($code,self::$messages));
        $message = self::$messages[$code];
        $status = self::$codes[$code];
        $this->set('code',$code)
             ->set('status',$status)
             ->set('head_title','Erreur ' . $code)
             ->set('message',$message);
        parent::__construct(ERROR_TEMPLATE);
    }

    public function __toString(): string{
        header('HTTP/1.0 '.$this->data['code'].' '.$this->data['status'], TRUE, $this->data['code']);
        unset($this->data['code'],$this->data['status']);
        return parent::__toString();
    }
}