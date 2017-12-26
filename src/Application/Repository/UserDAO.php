<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 15:57
 */

namespace Application\Repository;

use Application\Entity\User;
use \DI\Annotation\Injectable;
/**
 * Class UserDAO
 * @Injectable
 */
class UserDAO extends BaseDAO {

    public function findAll(): array {
        $stm = "FROM " . User::class;
        $query = $this->entity_manager->createQuery($stm);
        return $query->getArrayResult();
    }

    public function findMany(int... $ids): array {
        $stm = "FROM " . User::class . " "
            . "WHERE id IN :ids";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("ids", $ids);
        return $query->getArrayResult();
    }

    public function findOne(int $id): ? User {
        $stm = "FROM " . User::class . " "
            . "WHERE id=:id";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("id", $id);
        return $query->getOneOrNullResult();
    }

    public function save(User $meeting): void {
        if(!$this->entity_manager->contains($meeting))
            $this->entity_manager->persist($meeting);
        $this->entity_manager->flush($meeting);
    }

    public function delete(User $meeting): bool {
        $stm = "DELETE FROM " . User::class . " "
             . "WHERE id=:id";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("id", $meeting->getId());
        return (bool) $query->execute();
    }

    public function deleteMany(int... $ids): bool {
        $stm = "DELETE FROM " . User::class . " "
             . "WHERE id IN :ids";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("id", $ids);
        return (bool) $query->execute();

    }

    public function findOneByEmailAddressAndHash(string $email_address, string $hash): ? User {
        $stm = "FROM " . User::class . " "
            . "WHERE email_address LIKE :email_address "
            .   "AND hash LIKE :hash";

        $query = $this->entity_manager->createQuery($stm);
        $query->setParameters([
            "email_address" => $email_address,
            "hash" => $hash
        ]);

        return $query->getOneOrNullResult();
    }
}