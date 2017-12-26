<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 19/12/2017
 * Time: 21:59
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Application\Application;

$em = $app->getContainer()->get(\Doctrine\ORM\EntityManager::class);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);



