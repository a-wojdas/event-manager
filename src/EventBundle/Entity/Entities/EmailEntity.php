<?php

namespace EventBundle\Entity\Entities;

use Doctrine\ORM\Mapping as ORM;


/**
 * Description of EmailEntity
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
trait EmailEntity {
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255
     * )
     * @Assert\Email(
     *     checkMX = false
     * )
     */
    protected $email;

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Place
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
}
