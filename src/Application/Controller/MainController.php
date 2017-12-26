<?php
use Application\Http\Response\Response;

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 21/12/2017
 * Time: 22:50
 */

namespace Application\Controller;

use Application\Entity\Attendee;
use Application\Entity\Organizer;
use Application\Http\Response\Response;
use Application\Http\Response\View;
use Application\Http\Session;
use Application\Service\UserService;
use DI\Annotation\Inject;

class MainController {

    /**
     * @var UserService
     * @Inject
     */
    private $user_service;

    /**
     * @return Response
     * @Route(pattern="/", methods={"GET"})
     */
    public function getMainPage(Session $session): Response {
        $user_id = $session->get('user_id');
        if(!$user_id || !($user = $this->user_service->getUser($user_id)))
            return new View('login', Response::FORBIDDEN);
        else if($user instanceof Attendee)
            return new View('meetings.attendee');
        else if($user instanceof Organizer)
            return new View('meetings.organizer');
    }

}