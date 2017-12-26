<?php

/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 15/12/2017
 * Time: 10:14
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 */
abstract class User {

    /**
     * @var integer
     * @ORM\Id @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column()
     */
    private $first_name;

    /**
     * @var string
     * @ORM\Column()
     */
    private $last_name;

    /**
     * @var string
     * @ORM\Column()
     */
    private $email_address;

    /**
     * @var string
     * @ORM\Column()
     */
    private $hash;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ? string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(? string $first_name) : void {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): ? string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(? string $last_name) : void {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): ? string {
        return $this->email_address;
    }

    /**
     * @param string $email_address
     */
    public function setEmailAddress(? string $email_address) {
        $this->email_address = $email_address;
    }

    /**
     * @return string
     */
    public function getHash(): ? string {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(? string $hash) : void {
        $this->hash = $hash;
    }
}