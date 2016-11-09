<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EventBundle\Entity\Entities\StandardEntity;

/**
 * Encja lokalizacji imprezy.
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\AddressesRepository")
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class Address {
    
    /*
     * Wstrzyknij standardowe właściwości encji.
     */
    use StandardEntity;

    /**
     * Współrzędna geo "latitude".
     * 
     * @ORM\Column(type="decimal", precision=14, scale=8, nullable=true)
     */
    protected $latitude;

    /**
     * Współrzędna geo "latitude".
     * @ORM\Column(type="decimal", precision=14, scale=8, nullable=true)
     */
    protected $longitude;

    /**
     * Ustaw współrzędną "latitude".
     *
     * @param string $latitude
     *
     * @return Address
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Zwróć współrzędną "latitude".
     *
     * @return string
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Ustaw współrzędną "longitude".
     *
     * @param string $longitude
     *
     * @return Address
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Zwróć współrzędną "longitude".
     *
     * @return string
     */
    public function getLongitude() {
        return $this->longitude;
    }

}
