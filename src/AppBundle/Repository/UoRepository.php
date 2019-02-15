<?php

namespace AppBundle\Repository;


/**
 * UoRepository
 */
class UoRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllUo(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT uo.nom FROM AppBundle:Uo uo')
            ->getResult();
    }
}