<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 21/12/2017
 * Time: 23:48
 */

namespace Application\Controller;


use Application\Command\Login;
use Application\Http\Exception\BadRequestException;
use Application\Http\Exception\ResourceForbiddenException;
use Application\Http\Response\RedirectView;
use Application\Http\Response\Response;
use Application\Http\Session;
use Application\Service\UserService;
use DI\Annotation\Inject;

class LoginController {

    /**
     * @var UserService
     * @Inject
     */
    private $user_service;

    /**
     * @return Response
     * @Route(pattern="/login", methods={"POST"})
     */
    public function signIn(Login $login, Session $session): Response {
        if($session->get('user_id'))
            throw new ResourceForbiddenException;
        $user = $this->user_service->getUserByLogin($login);
        if($user != NULL)
            $session->set('user_id', $user->getId());
        return new RedirectView('/');
    }

}