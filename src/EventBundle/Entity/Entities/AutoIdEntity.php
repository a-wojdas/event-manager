<?php

namespace EventBundle\Entity\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of AutoIdEntity
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
trait AutoIdEntity {   
    
    /**
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;    
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
