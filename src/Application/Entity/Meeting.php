<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 10:37
 */

namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="meetings")
 */
class Meeting {

    /**
     * @var integer
     * @ORM\Id @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Organizer
     * @ORM\ManyToOne(targetEntity="Organizer", inversedBy="created_meetings", fetch="EAGER", cascade={"ALL"})
     * @ORM\JoinColumn(name="organizer_id", referencedColumnName="id")
     */
    private $organizer;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Organizer", cascade="ALL")
     * @ORM\JoinTable(
     *     name="organizers__meetings",
     *     joinColumns={
     *          @ORM\JoinColumn(
     *                 name="meeting_id",
     *                 referencedColumnName="id"
     *          )
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(
     *              name="organizer_id",
     *              referencedColumnName="id"
     *          )
     *      }
     * )
     */
    private $organizers;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Attendee", cascade="ALL")
     * @ORM\JoinTable(
     *     name="attendees__meetings",
     *     joinColumns={
     *          @ORM\JoinColumn(
     *                 name="meeting_id",
     *                 referencedColumnName="id"
     *          )
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(
     *              name="attendee_id",
     *              referencedColumnName="id"
     *          )
     *      }
     * )
     */
    private $attendees;

    /**
     * @var \DateTime
     * @ORM\Column
     */
    private $start_date;

    /**
     * @var \DateTime
     * @ORM\Column
     */
    private $end_date;

    /**
     * @var string
     * @ORM\Column
     */
    private $title;

    /**
     * @var string
     * @ORM\Column
     */
    private $description;

    /**
     * Meeting constructor.
     */
    public function __construct(Organizer $organizer) {
        $this->organizer  = $organizer;
        $this->organizers = new ArrayCollection;
        $this->participants = new ArrayCollection;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Organizer
     */
    public function getOrganizer(): Organizer
    {
        return $this->organizer;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrganizers(): ArrayCollection
    {
        return $this->organizers;
    }

    /**
     * @param Organizer $organizer
     * @return bool
     */
    public function addOrganizer(Organizer $organizer): bool {
        if(!$organizer->getOrganizedMeetings()->contains($this))
            $organizer->addOrganizedMeeting($this);
        return $this->organizers->add($organizer);
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipants(): ArrayCollection
    {
        return $this->participants;
    }

    /**
     * @param Attendee $attendee
     * @return bool
     */
    public function addParticipant(Attendee $attendee): bool {
        if(!$attendee->getMeetings()->contains($this))
            $attendee->addMeeting($this);
        return $this->participants->add($attendee);
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): ? \DateTime
    {
        return $this->start_date;
    }

    /**
     * @param \DateTime $start_date
     */
    public function setStartDate(? \DateTime $start_date): void
    {
        $this->start_date = $start_date;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): ? \DateTime
    {
        return $this->end_date;
    }

    /**
     * @param \DateTime $end_date
     */
    public function setEndDate(? \DateTime $end_date): void
    {
        $this->end_date = $end_date;
    }

    /**
     * @return string
     */
    public function getTitle(): ? string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(? string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(? string $description): void
    {
        $this->description = $description;
    }
}