<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 12:00
 */
namespace Application\Repository;

use Doctrine\ORM\EntityManager;

abstract class BaseDAO {

    /**
     * @var EntityManager
     * @\DI\Annotation\Inject
     */
    protected $entity_manager;

}