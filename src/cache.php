<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 19/12/2017
 * Time: 22:20
 */

if(extension_loaded('apcu'))
    $cache = new \Doctrine\Common\Cache\ApcuCache;
else
    try{
        $cache = new \Doctrine\Common\Cache\FilesystemCache(__DIR__ . '../.runtime_generated/common/cache');
    } catch (Exception $exception){
        //Log::write($exception->getMessage());
        $cache = new \Doctrine\Common\Cache\ArrayCache;
    }

return $cache;
