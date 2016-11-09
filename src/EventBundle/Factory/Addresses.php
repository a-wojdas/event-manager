<?php

namespace EventBundle\Factory;

use Psr\Log\LoggerInterface,
    Psr\Log\LoggerAwareInterface,
    Psr\Log\NullLogger,
    Psr\Log\LoggerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use EventBundle\Entity\Address;

/**
 * Fabryka lokalizacji.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class Addresses implements LoggerAwareInterface, ContainerAwareInterface {

    use LoggerAwareTrait;

    /**
     * @var Container
     */
    private $container;

    /**
     *
     * @var EntityManager 
     */
    protected $em;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger = null) {
        $this->em = $entityManager;
        $this->logger = $logger ? new NullLogger() : $logger;
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    /**
     * Utwórz lokalizację imprezy.
     *
     * @param string $latitude
     * @param string $longitude
     *
     * @return Address
     */
    public function create($latitude, $longitude) {
        $entity = new Address();
        $entity->setLatitude($latitude)
                ->setLongitude($longitude);

        $this->em->persist($entity);
        $this->em->flush();

        /*
          $config = $this->container->getParameter('address_notify');

          $to = $config['to'];
          $from = $config['from'];
          $subject = $config['subject'];
          $body = 'Dodano nowy adres [' . $latitude . ', ' . $longitude . '].'    ;

          $mailer = $this->container->get('party.mailer');
          $mailer->send($from, $to, $subject, $body); */

        return $entity;
    }

    /**
     * Sprawdź czy istnieje już lokalizacja imprezy.
     *
     * @param string $latitude
     * @param string $longitude
     *
     * @return Address
     */
    public function exists($latitude, $longitude) {
        $addressRepository = $this->em->getRepository('EventBundle:Address');

        $address = $addressRepository->findOneBy(array(
            'latitude' => $latitude,
            'longitude' => $longitude
        ));

        return $address;
    }

}
