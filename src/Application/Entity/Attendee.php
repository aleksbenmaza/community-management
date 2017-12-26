<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 10:36
 */

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="attendees")
 * @ORM\DiscriminatorMap("1")
 */
class Attendee extends User {

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Meeting", mappedBy="attendee", cascade={"ALL"})
     */
    private $meetings;

    /**
     * @return ArrayCollection
     */
    public function getMeetings(): ArrayCollection
    {
        return $this->meetings;
    }

    /**
     * @param Meeting $meeting
     * @return bool
     */
    public function addMeeting(Meeting $meeting): bool {
        if(!$meeting->getParticipants()->contains($this))
            $meeting->addParticipant($this);
        return $this->meetings->add($meeting);
    }

}