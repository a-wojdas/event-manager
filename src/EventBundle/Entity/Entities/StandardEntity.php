<?php

namespace EventBundle\Entity\Entities;

use Doctrine\ORM\Mapping as ORM;

use EventBundle\Entity\Entities\AutoIdEntity;

/**
 * Description of StandardEntity
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
trait StandardEntity {
     use AutoIdEntity;
}
