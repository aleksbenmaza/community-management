<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 07/10/2016
 * Time: 20:10
 */

namespace Application;

class Logger {

    private function __construct() {}

    public static function write(string $event, string $client_ip = ''): bool{
        $f = fopen(ROOT . "/.logging".\date("y-m-d",time()).".log","a+");
        if(!$f)
            return FALSE;

        if(!fwrite($f,\date("h:m:s", time()).' - ' . $client_ip .':' . PHP_EOL . $event . PHP_EOL . PHP_EOL))
            return FALSE;
        fclose($f);
        return TRUE;
    }
}