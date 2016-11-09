<?php

namespace EventBundle\Repository;

/**
 * Repozytorium imprez.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
class EventRepository extends \Doctrine\ORM\EntityRepository {

    public function findAllAddresses() {
        $result = array();

        $list = $this->findAll();

        foreach ($list as $item) {
            $result[md5($item->getAddress())] = [
                'id' => $item->getId(),
                'text' => $item->getAddress(),
            ];
        }

        return array_values($result);
    }

    public function findByDistance($distance, $latDestination, $lngDestination) {
        $qb = $this->createQueryBuilder('evnt')
                ->select('evnt, loc, GEO_DISTANCE(loc.latitude, loc.longitude, :latDestination, :lngDestination) as distance')
                ->leftJoin('evnt.location', 'loc')
                ->andWhere('GEO_DISTANCE(loc.latitude, loc.longitude, :latDestination, :lngDestination) <= :distance')
                ->setParameter('distance', $distance)
                ->setParameter('latDestination', $latDestination)
                ->setParameter('lngDestination', $lngDestination)
                ->orderBy('GEO_DISTANCE(loc.latitude, loc.longitude, :latDestination, :lngDestination)', 'DESC')
        ;

        $result = $qb->getQuery()->getResult();

        $return = array();
        foreach ($result as $item) {
            $out = $item[0];
            $out->distance = $item['distance'];
            $return[] = $out;
        }

        return $return;
    }

}
