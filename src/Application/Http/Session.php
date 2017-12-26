<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 28/09/2016
 * Time: 08:33
 */

namespace Application\Http;

class Session {
    private static $instance = NULL;

    private $data     = [];

    public static function getInstance(): Session {
        return self::$instance ?? self::$instance = new Session;
    }

    private function __construct(){
        session_start();
        foreach($_SESSION as $key => $data)
            $this->data[$key] = $data;
        session_unset();
    }

    public function __destruct() {

        foreach($this->data as $key => $data)
            $_SESSION[$key] = $data;
    }

    public function __clone(){
        throw new \RuntimeException(self::class.' is not clonable !');
    }

    public function invalidate(): void{
        $this->data = [];
        session_destroy();
    }

    public static function save(): void{
        if(!self::$instance)
            return;
        self::$instance->__destruct();
    }

    public function unset(string $key): bool{
        if(!isset($this->data[$key]))
            return FALSE;
        unset($this->data[$key]);
        return TRUE;
    }

    public function flush(): array{
        $data = $this->data;
        $this->data = [];
        return $data;
    }

    public function clean(): void{
        $this->data = [];
    }

    public function set(string $key, $data): Session{
        $this->data[$key] = $data;
        return $this;
    }

    public function get(string $key) {
        return $this->data[$key] ?? NULL;
    }
}