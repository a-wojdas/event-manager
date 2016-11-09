<?php

namespace EventBundle\Factory;

use Psr\Log\LoggerInterface,
    Psr\Log\LoggerAwareInterface,
    Psr\Log\NullLogger,
    Psr\Log\LoggerAwareTrait;
use Doctrine\ORM\EntityManager;
use EventBundle\Entity\Event;

/**
 * Fabryka imprez.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class Events implements LoggerAwareInterface {

    use LoggerAwareTrait;

    /**
     *
     * @var EntityManager 
     */
    protected $em;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger = null) {
        $this->em = $entityManager;
        $this->logger = $logger ? new NullLogger() : $logger;
    }

    /**
     * Utwórz imprezę.
     *
     * @param Event $entity
     *
     * @return Event
     */
    public function createFromEntity(Event $entity) {
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
    }

}
