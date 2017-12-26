<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 12:00
 */

namespace Application\Repository;

use Application\Entity\Meeting;
use \DI\Annotation\Injectable;

/**
 * @Injectable
 */
class MeetingDAO extends BaseDAO {

    public function findAll(): array {
        $stm = "FROM " . Meeting::class;
        $query = $this->entity_manager->createQuery($stm);
        return $query->getArrayResult();
    }

    public function findMany(int... $ids): array {
        $stm = "FROM " . Meeting::class . " "
             . "WHERE id IN :ids";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("ids", $ids);
        return $query->getArrayResult();
    }

    public function findOne(int $id): ? Meeting {
        $stm = "FROM " . Meeting::class . " "
             . "WHERE id=:id";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("id", $id);
        return $query->getOneOrNullResult();
    }

    public function save(Meeting $meeting): void {
        if(!$this->entity_manager->contains($meeting))
            $this->entity_manager->persist($meeting);
        $this->entity_manager->flush($meeting);
    }

    public function delete(Meeting $meeting): bool {
        $stm = "DELETE FROM " . Meeting::class . " "
             . "WHERE id=:id";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("id", $meeting->getId());
        return (bool) $query->execute();
    }

    public function deleteMany(int... $ids): bool {
        $stm = "DELETE FROM " . Meeting::class . " "
             . "WHERE id IN :ids";
        $query = $this->entity_manager->createQuery($stm);
        $query->setParameter("id", $ids);
        return (bool) $query->execute();

    }
}