<?php

namespace AppBundle\Repository;


/**
 * AgentRepository
 */
class AgentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllAgent(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT a.nom FROM AppBundle:Agent a')
            ->getResult();
    }
    public function getNbAgents($pole, $bo, $profil, $astreinte){
        $pole_tab = $this->make_filtre($pole, 'b', 'pole');
        $pole = $pole_tab[0];
        $col_pole = $pole_tab[1];

        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        $profil_tab = $this->make_filtre($profil, 'ag', 'profil');
        $profil = $profil_tab[0];
        $col_profil = $profil_tab[1];

        $astreinte_tab = $this->make_filtre($astreinte, 'ag', 'abstreinte');
        $astreinte = $astreinte_tab[0];
        $col_astreinte = $astreinte_tab[1];
        
        $nb_ag = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                           FROM AppBundle:Agent ag
                           INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                           INNER JOIN AppBundle:Bo b WITH e.bo = b
                           INNER JOIN AppBundle:Agence a WITH b.agence = a
                           INNER JOIN AppBundle:Dr d WITH a.dr = d 
                           WHERE $col_pole $pole
                           AND $col_bo $bo
                           AND $col_profil $profil
                           AND $col_astreinte $astreinte
                           ")
            ->getResult();
        $nb_ag_astreinte_oui = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                           FROM AppBundle:Agent ag
                           INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                           INNER JOIN AppBundle:Bo b WITH e.bo = b
                           INNER JOIN AppBundle:Agence a WITH b.agence = a
                           INNER JOIN AppBundle:Dr d WITH a.dr = d 
                           WHERE $col_pole $pole
                           AND $col_bo $bo
                           AND $col_profil $profil
                           AND ag.abstreinte = 'oui'
                           ")
            ->getResult();
        $nb_ag_astreinte_non = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                           FROM AppBundle:Agent ag
                           INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                           INNER JOIN AppBundle:Bo b WITH e.bo = b
                           INNER JOIN AppBundle:Agence a WITH b.agence = a
                           INNER JOIN AppBundle:Dr d WITH a.dr = d 
                           WHERE $col_pole $pole
                           AND $col_bo $bo
                           AND $col_profil $profil
                           AND ag.abstreinte = 'non'
                           ")
            ->getResult();

        $data = [];
        
        array_push($data, $nb_ag);
        array_push($data, $nb_ag_astreinte_oui);
        array_push($data, $nb_ag_astreinte_non);

        return $data;
    }
    public function getNbAgentsFiltreAgent($agents_nni){
        $ags_nni = "";
        if(count($agents_nni > 1)){
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
        
            $nb_ag = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                            FROM AppBundle:Agent ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            INNER JOIN AppBundle:Agence a WITH b.agence = a
                            INNER JOIN AppBundle:Dr d WITH a.dr = d 
                            WHERE ag.nni IN ($ags_nni)
                            ")
                ->getResult();
            $nb_ag_astreinte_oui = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                            FROM AppBundle:Agent ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            INNER JOIN AppBundle:Agence a WITH b.agence = a
                            INNER JOIN AppBundle:Dr d WITH a.dr = d 
                            WHERE ag.nni IN ($ags_nni)
                            AND ag.abstreinte = 'oui'
                            ")
                ->getResult();
            $nb_ag_astreinte_non = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                            FROM AppBundle:Agent ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            INNER JOIN AppBundle:Agence a WITH b.agence = a
                            INNER JOIN AppBundle:Dr d WITH a.dr = d 
                            WHERE ag.nni IN ($ags_nni)
                            AND ag.abstreinte = 'non'
                            ")
                ->getResult();

            $data = [];
            
            array_push($data, $nb_ag);
            array_push($data, $nb_ag_astreinte_oui);
            array_push($data, $nb_ag_astreinte_non);
        }
        else{
            $agent_nni = $agents_nni[0];
        
            $nb_ag = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                            FROM AppBundle:Agent ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            INNER JOIN AppBundle:Agence a WITH b.agence = a
                            INNER JOIN AppBundle:Dr d WITH a.dr = d 
                            WHERE ag.nni = '$agent_nni'
                            ")
                ->getResult();
            $nb_ag_astreinte_oui = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                            FROM AppBundle:Agent ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            INNER JOIN AppBundle:Agence a WITH b.agence = a
                            INNER JOIN AppBundle:Dr d WITH a.dr = d 
                            WHERE ag.nni = '$agent_nni'
                            AND ag.abstreinte = 'oui'
                            ")
                ->getResult();
            $nb_ag_astreinte_non = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni))
                            FROM AppBundle:Agent ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            INNER JOIN AppBundle:Agence a WITH b.agence = a
                            INNER JOIN AppBundle:Dr d WITH a.dr = d 
                            WHERE ag.nni = '$agent_nni'
                            AND ag.abstreinte = 'non'
                            ")
                ->getResult();

            $data = [];
            
            array_push($data, $nb_ag);
            array_push($data, $nb_ag_astreinte_oui);
            array_push($data, $nb_ag_astreinte_non);
        }

        return $data;
    }
    public function getAgentsAstreinteNoAgent($pole, $bo, $profil, $astreinte){
        $pole_tab = $this->make_filtre($pole, 'b', 'pole');
        $pole = $pole_tab[0];
        $col_pole = $pole_tab[1];

        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        $profil_tab = $this->make_filtre($profil, 'ag', 'profil');
        $profil = $profil_tab[0];
        $col_profil = $profil_tab[1];

        $astreinte_tab = $this->make_filtre($astreinte, 'ag', 'abstreinte');
        $astreinte = $astreinte_tab[0];
        $col_astreinte = $astreinte_tab[1];
        
         return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                           FROM AppBundle:Agent ag
                           INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                           INNER JOIN AppBundle:Bo b WITH e.bo = b
                           INNER JOIN AppBundle:Agence a WITH b.agence = a
                           INNER JOIN AppBundle:Dr d WITH a.dr = d 
                           WHERE $col_pole $pole
                           AND $col_bo $bo
                           AND $col_profil $profil
                           AND $col_astreinte $astreinte
                           ")
            ->getResult();
    }
    public function getAgentsAstreinteAgent($agents_nni, $astreinte){
        $ags_nni = "";
        if($astreinte == ""){
            $astreinte = 1;
            $col_astreinte = 1;
        }
        else{
            $col_astreinte = "ag.abstreinte";
        }
        if(count($agents_nni > 1)){
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
        
         return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                           FROM AppBundle:Agent ag
                           INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                           INNER JOIN AppBundle:Bo b WITH e.bo = b
                           INNER JOIN AppBundle:Agence a WITH b.agence = a
                           INNER JOIN AppBundle:Dr d WITH a.dr = d 
                           WHERE ag.nni IN ($ags_nni)
                           AND $col_astreinte = '$astreinte'

                           ")
            ->getResult();
        }
        else{
            $agent_nni = $agents_nni[0];
            return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                           FROM AppBundle:Agent ag
                           INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                           INNER JOIN AppBundle:Bo b WITH e.bo = b
                           INNER JOIN AppBundle:Agence a WITH b.agence = a
                           INNER JOIN AppBundle:Dr d WITH a.dr = d 
                           WHERE ag.nni = '$agent_nni'
                           AND $col_astreinte = '$astreinte'
                           ")
            ->getResult();
        }
    }
    public function getAllAboutAgents(){
        return $this->getEntityManager()
            ->createQuery('SELECT a.nni as nni, a.nom as nom, a.prenom as prenom, a.profil as profil, a.abstreinte as abstreinte,
            a.retraite as retraite, a.parti as parti
            FROM AppBundle:Agent a
            ORDER BY a.nom')
            ->getResult();
    }
    public function getInfosAgent($agent_id){
        return $this->getEntityManager()
            ->createQuery("SELECT a.nni as nni, a.nom as nom, a.prenom as prenom, a.profil as profil, a.abstreinte as abstreinte,
            a.retraite as retraite, a.parti as parti, d.nom as dr, ag.nom as agence, b.nom as bo, b.pole as pole
            FROM AppBundle:Agent a
            INNER JOIN AppBundle:Equipe e WITH a.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            INNER JOIN AppBundle:Agence ag WITH b.agence = ag
            INNER JOIN AppBundle:Dr d WITH ag.dr = d
            WHERE a.id = $agent_id")
            ->getResult();
    }
    public function getAllNni(){
        return $this->getEntityManager()
            ->createQuery('SELECT DISTINCT a.nni as nni, a.nom as nom, a.prenom as prenom, b.nom as bo FROM AppBundle:Agent a
            INNER JOIN AppBundle:Equipe e WITH a.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b')
            ->getResult();
    }
    public function getBoAgent($agent_id){
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT b.id as bo_id, b.nom as bo_nom FROM AppBundle:Agent a
            INNER JOIN AppBundle:Equipe e WITH a.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE a.id = $agent_id")
            ->getResult();
    }
    public function getAgentsId(){
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT a.id as agent_id FROM AppBundle:Agent a")
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
    public function getListeAgents($pol, $bo, $profil, $astreinte){
        $pole_tab = $this->make_filtre($pol, 'b', 'pole');
        $pole = $pole_tab[0];
        $col_pole = $pole_tab[1];

        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        $profil_tab = $this->make_filtre($profil, 'ag', 'profil');
        $profil = $profil_tab[0];
        $col_profil = $profil_tab[1];

        $astreinte_tab = $this->make_filtre($astreinte, 'ag', 'abstreinte');
        $astreinte = $astreinte_tab[0];
        $col_astreinte = $astreinte_tab[1];

        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.prenom as prenom, ag.nom as nom
            FROM AppBundle:Agent ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            ORDER BY nom ASC")
            ->getResult();
    }
}
