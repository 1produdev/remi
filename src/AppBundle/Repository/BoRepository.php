<?php

namespace AppBundle\Repository;


/**
 * BoRepository
 */
class BoRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllPole(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT b.pole FROM AppBundle:Bo b
            ORDER BY b.pole')
            ->getResult();
    }
    public function getAllBo(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT b.nom, b.pole FROM AppBundle:Bo b
                           ORDER BY b.nom ASC')
            ->getResult();
    }
    public function findPolesBos(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT b.nom as bo, b.pole as pole FROM AppBundle:Bo b
            ORDER BY b.nom')
            ->getResult();
    }
    public function getPoleByBo($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT b.pole as pole FROM AppBundle:Bo b
            WHERE $col_bo $bo")
            ->getResult();
    }
    public function make_filtre($valeus_filtre, $table, $colonne){
        if(is_array($valeus_filtre)){
            if(count($valeus_filtre) > 1){
                $chaine_valeurs = "";
                foreach($valeus_filtre as $p){
                    $chaine_valeurs .= "'".$p."', ";
                }
                $chaine_valeurs = substr($chaine_valeurs, 0, strlen($chaine_valeurs) - 2);
                $chaine_valeurs = " IN (".$chaine_valeurs.")";
                $nom_colonne = $table.".".$colonne;
            }
            else{
                if($valeus_filtre[0] == ""){
                    $chaine_valeurs = " = 1";
                    $nom_colonne = "1";
                }
                else{
                    $chaine_valeurs = "'".$valeus_filtre[0]."'";
                    $nom_colonne = $table.".".$colonne." = ";
                }
            }
        }
        else{
            if($valeus_filtre != ""){
                $chaine_valeurs = "'".$valeus_filtre."'";
                $nom_colonne = $table.".".$colonne." = ";
            }
            else{
                $nom_colonne = "1 = ";
                $chaine_valeurs = "1";
            }
        }
        return [$chaine_valeurs, $nom_colonne];
    }
}
