<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EventBundle\Entity\Entities\StandardEntity,
    EventBundle\Entity\Entities\EmailEntity;

/**
 * Encja "imprezy"
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EventRepository")
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class Event {
    
    /*
     * Wstrzyknij E-mail oraz standardowe właściwości encji.
     */
    use StandardEntity,
        EmailEntity;

    /**
     * Nazwa imprezy.
     * 
     * @ORM\Column(type="string", length=50, nullable=false)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 50
     * )
     */
    protected $name;

    /**
     * Opis imprezy.
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
     * Adres imprezy.
     * 
     * @ORM\Column(type="string", length=50, nullable=false)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 50
     * )
     */
    protected $address;

    /**
     * Lokalizacja imprezy.
     * 
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Address")
     */
    protected $location;

    /**
     * Zmienna umożliwia prezentację odlegości między lokalizacjami.
     *      
     */
    public $distance;

    /**
     * Data rozpoczęcia imprezy.
     * 
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\Expression(
     *     "this.getStart() < this.getFinish()"
     * )
     * @Assert\Expression(
     *     "this.getStart() < this.getFinish()"
     * )
     * @Assert\GreaterThan("+7 days")
     */
    protected $start;

    /**
     * Data zakończenia imprezy.
     * 
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\GreaterThan("+7 days")
     */
    protected $finish;

    /**
     * Komentarze do imprezy
     * 
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\EventComment", mappedBy="event")
     * @ORM\JoinTable(name="event_comments")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $comments;

    /**
     * Constructor
     */
    public function __construct() {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Ustaw nazwę imprezy.
     *
     * @param string $name
     *
     * @return Event
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Zwróć nazwę imprezy.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Ustaw opis imprezy.
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Zwróć opis imprezy.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Ustaw datę rozpoczęcia imprezy.
     *
     * @param \DateTime $start
     *
     * @return Event
     */
    public function setStart($start) {
        $this->start = $start;

        return $this;
    }

    /**
     * Zwróć datę rozpoczęcia imprezy.
     *
     * @return \DateTime
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Ustaw datę zakończenia imprezy.
     *
     * @param \DateTime $finish
     *
     * @return Event
     */
    public function setFinish($finish) {
        $this->finish = $finish;

        return $this;
    }

    /**
     * Ustaw datę zakończenia imprezy.
     *
     * @return \DateTime
     */
    public function getFinish() {
        return $this->finish;
    }

    /**
     * Ustaw adres imprezy.
     *
     * @param string $address
     *
     * @return Event
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Zwróć adres imprezy.
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Ustaw encję lokalizacji imprezy.
     *
     * @param \EventBundle\Entity\Address $location
     *
     * @return Event
     */
    public function setLocation(\EventBundle\Entity\Address $location = null) {
        $this->location = $location;

        return $this;
    }

    /**
     * Zwróć encję lokalizacji imprezy.
     *
     * @return \EventBundle\Entity\Address
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Dodaj encję komentarza do imprezy.
     *
     * @param \EventBundle\Entity\EventComment $comment
     *
     * @return Event
     */
    public function addComment(\EventBundle\Entity\EventComment $comment) {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Usuń encję komentarza z imprezy.
     *
     * @param \EventBundle\Entity\EventComment $comment
     */
    public function removeComment(\EventBundle\Entity\EventComment $comment) {
        $this->comments->removeElement($comment);
    }

    /**
     * Zwróć encje komentarzy do imprezy.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this->comments;
    }

}
