<?php

use Application\Http\Exception\BadRequestException;
use Application\Http\Exception\MethodNotAllowedException;
use Application\Http\Exception\NotFoundException;
use Application\Http\Response\ErrorView;
use \Application\Http\Response\Response;
use Application\Http\Session;
use Application\Logger;


require_once 'src/globals.php';
require_once 'vendor/autoload.php';

if(!PROD)
    (require_once ROOT . '/src/cache.php')->flushAll();

if(strpos($_SERVER['REQUEST_URI'] , 'index.php') !== FALSE){
    echo new ErrorView(Response::NOT_FOUND);
    exit;
}

try{
    $request_data = array_merge($_GET, $_POST, $_FILES);
    $app = new \Application\Application;

    echo $app->output($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $request_data);

}catch(PDOException | \Doctrine\DBAL\Query\QueryException | \Doctrine\ORM\Query\QueryException $t){
    Session::getInstance()->save();
    Logger::write($t->getMessage().PHP_EOL.$t->getTraceAsString());

} catch(Throwable $t) {
    Session::getInstance()->save();
    if (!PROD)
        throw $t;

    else if ($t instanceof TypeError && strpos($t->getTrace()[0]['file'], 'Dispatcher.php'))
        echo new ErrorView(Response::SERVICE_UNAVAILABLE);

    else if($t instanceof MethodNotAllowedException)
        echo new ErrorView(Response::METHOD_NOT_ALLOWED);

    else if($t instanceof BadRequestException)
        echo new ErrorView(Response::BAD_REQUEST);

    else if ($t instanceof NotFoundException)
        echo new ErrorView(Response::NOT_FOUND);
    else
        echo new ErrorView(Response::SERVICE_UNAVAILABLE);
}
