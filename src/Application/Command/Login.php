<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 21/12/2017
 * Time: 23:45
 */

namespace Application\Command;

class Login {

    /**
     * @var string
     * @Field(name="email_address")
     */
    private $email_address;

    /**
     * @var string
     * @Field
     */
    private $password;

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->email_address;
    }

    /**
     * @param string $email_address
     */
    public function setEmailAddress(string $email_address)
    {
        $this->email_address = $email_address;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}