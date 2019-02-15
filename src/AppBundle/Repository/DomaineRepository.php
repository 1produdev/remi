<?php

namespace AppBundle\Repository;


/**
 * DomaineRepository
 */
class DomaineRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllDomaine(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT d.nom as domaine FROM AppBundle:Domaine d ORDER BY d.nom ASC')
            ->getResult();
    }
    public function pick_up_id($domaine_nom){
        return $this->getEntityManager()
            ->createQuery("SELECT d.id as domaine_id FROM AppBundle:Domaine d WHERE d.nom = '$domaine_nom'")
            ->getResult();
    }
    public function getUniqueDomaine(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT d FROM AppBundle:Domaine d')
            ->getResult();
    }
    public function getDomaineAgent($agent_id){
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(d.nom) as domaine FROM AppBundle:UoAgent u
            INNER JOIN AppBundle:UO uo WITH u.uo = uo
            INNER JOIN AppBundle:SousDomaine s WITH uo.sousdomaine = s
            INNER JOIN AppBundle:Domaine d WITH s.domaine = d
            INNER JOIN AppBundle:Agent a WITH u.agent= a
            WHERE a.id = $agent_id")
            ->getResult();
    }
    public function getDomaineSousDomaineAgent($agent_id){
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(d.nom) as domaine, s.nom as sousdomaine FROM AppBundle:UoAgent u
            INNER JOIN AppBundle:UO uo WITH u.uo = uo
            INNER JOIN AppBundle:SousDomaine s WITH uo.sousdomaine = s
            INNER JOIN AppBundle:Domaine d WITH s.domaine = d
            INNER JOIN AppBundle:Agent a WITH u.agent= a
            WHERE a.id = $agent_id")
            ->getResult();
    }
}