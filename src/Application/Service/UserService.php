<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 21/12/2017
 * Time: 23:37
 */

namespace Application\Service;

use Application\Command\Login;
use Application\Entity\User;
use Application\Repository\UserDAO;
use DI\Annotation\Inject;
use DI\Annotation\Injectable;

/**
 * Class UserService
 * @package Application\Service
 * @Injectable
 */
class UserService {

    /**
     * @var UserDAO
     * @Inject
     */
    private $user_dao;

    public function getUser($id) : ? User {
        return $this->user_dao->findOne($id);
    }

    public function getUserByLogin(Login $login): ? User {
        return $this->user_dao->findOneByEmailAddressAndHash($login->getEmailAddress(), $this->hashedStr($login->getEmailAddress() . 'toto' . $login->getPassword()));
    }


    private function hashedStr(string $str): string {
        return hash('sha256', $str);
    }
}