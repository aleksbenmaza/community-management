<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 10:32
 */

namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organizers")
 * @ORM\DiscriminatorMap("0")
 */
class Organizer extends User {

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Meeting", cascade="ALL", mappedBy="organizer")
     */
    private $created_meetings;
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Meeting", cascade="ALL", mappedBy="organizers")
     */
    private $organized_meetings;

    /**
     * Organizer constructor.
     */
    public function __construct()
    {
        $this->created_meetings = new ArrayCollection;
        $this->organized_meetings = new ArrayCollection;
    }

    /**
     * @return ArrayCollection
     */
    public function getCreatedMeetings(): ArrayCollection
    {
        return $this->created_meetings;
    }

    public function addCreatedMeeting(Meeting $meeting) : bool {
        return $this->created_meetings->add($meeting);
    }

    /**
     * @return ArrayCollection
     */
    public function getOrganizedMeetings(): ArrayCollection {
        return $this->organized_meetings;
    }

    public function addOrganizedMeeting(Meeting $meeting) : bool {
        if(!$meeting->getOrganizers()->contains($this))
            $meeting->addOrganizer($this);
        return $this->created_meetings->add($meeting);
    }

}