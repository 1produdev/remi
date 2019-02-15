<?php

namespace AppBundle\Repository;


/**
 * SousDomaineRepository
 */
class SousDomaineRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllSousDomaine(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT sd.nom FROM AppBundle:SousDomaine sd')
            ->getResult();
    }
    public function getAllDomaineSousDomaine(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT d.nom as domaine, sd.nom as sousdomaine, sd.id as sd_id, d.id as d_id FROM AppBundle:SousDomaine sd
                           INNER JOIN AppBundle:Domaine d with sd.domaine = d')
            ->getResult();
    }
}