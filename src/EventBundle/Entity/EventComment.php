<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EventBundle\Entity\Entities\StandardEntity,
    EventBundle\Entity\Entities\EmailEntity;

/**
 * Encja komentarza imprezy.
 *
 * @ORM\Table(name="event_comment")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EventCommentRepository")
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class EventComment {
    
    /*
     * Wstrzyknij E-mail oraz standardowe właściwości encji.
     */
    use StandardEntity,
        EmailEntity;

    /**
     * Treść komentarza.
     * 
     * @ORM\Column(type="string", length=300, nullable=false)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 300
     * )
     */
    protected $description;

    /**
     * Impreza.
     * 
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event", inversedBy="comments")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * Ustaw treść komentarza do imprezy.
     *
     * @param string $description
     *
     * @return EventComment
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Zwróć treść komentarza do imprezy.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Ustaw encję imprezy.
     *
     * @param \EventBundle\Entity\Event $event
     *
     * @return EventComment
     */
    public function setEvent(\EventBundle\Entity\Event $event = null) {
        $this->event = $event;

        return $this;
    }

    /**
     * Zwróć encję imprezy.
     *
     * @return \EventBundle\Entity\Event
     */
    public function getEvent() {
        return $this->event;
    }

}
