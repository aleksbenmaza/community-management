<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 19/06/2016
 * Time: 16:28
 */

namespace Application\Http\Response;

require_once ROOT . '/src/template_path.php';

class View extends Response {
    protected 
        $template_path = '',
        $data          = [];
    
    public function __construct(string $template){
        $this->template_path = \path($template);
    }

    public function set(string $key, $data): View{
        $this->data[$key] = $data;
        return $this;
    }
    
    public function __toString(): string{
        return $this->render();
    }

    public static function toJson($var): string{
        return json_encode($var);
    }

    public function debug(){
        exit($this->render());
    }
    
    private function render(): string{
        extract($this->data);
        ob_start();
        require_once $this->template_path;
        $rendering = ob_get_clean();
        return $rendering;
    }
}