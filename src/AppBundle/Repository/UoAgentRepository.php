<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr\Join;

/**
 * UoAgentRepository
 */
class UoAgentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPST(){
        return $this->getEntityManager()
        ->createQuery("SELECT COUNT(DISTINCT(ua.agent)) as nb_agents, b.nom as bo, b.pole as pole, d.nom as domaine FROM AppBundle:UoAgent ua
                        INNER JOIN AppBundle:Agent a with ua.agent = a
                        INNER JOIN AppBundle:Equipe e with a.equipe = e
                        INNER JOIN AppBundle:Bo b with e.bo = b
                        INNER JOIN AppBundle:Agence ag with b.agence = ag
                        INNER JOIN AppBundle:Dr dr with ag.dr = dr
                        INNER JOIN AppBundle:UO uo with ua.uo = uo
                        INNER JOIN AppBundle:SousDomaine sd with uo.sousdomaine = sd
                        INNER JOIN AppBundle:Domaine d with sd.domaine = d
                        WHERE ua.niveau >= 4
                        GROUP BY b.nom, d.nom
                        ORDER BY b.pole, b.nom, d.nom ASC")
        ->getResult();
    }
    public function getPST_for_Agents(){
        return $this->getEntityManager()
        ->createQuery("SELECT COUNT(DISTINCT(uo)) as nb_uos, b.nom as bo, b.pole as pole, d.nom as domaine, a.nni, a.prenom, a.nom as nom FROM AppBundle:UoAgent ua
                        INNER JOIN AppBundle:Agent a with ua.agent = a
                        INNER JOIN AppBundle:Equipe e with a.equipe = e
                        INNER JOIN AppBundle:Bo b with e.bo = b
                        INNER JOIN AppBundle:Agence ag with b.agence = ag
                        INNER JOIN AppBundle:Dr dr with ag.dr = dr
                        INNER JOIN AppBundle:UO uo with ua.uo = uo
                        INNER JOIN AppBundle:SousDomaine sd with uo.sousdomaine = sd
                        INNER JOIN AppBundle:Domaine d with sd.domaine = d
                        WHERE ua.niveau >= 4
                        GROUP BY b.nom, d.nom, a.nni
                        ORDER BY nom ASC")
        ->getResult();
    }
    public function getPST_detail_Agents(){
        return $this->getEntityManager()
        ->createQuery("SELECT DISTINCT(uo.nom) as uo_nom, uo.code as uo_code, b.nom as bo, b.pole as pole, d.nom as domaine, sd.nom as sousdomaine, a.nni as nni, a.prenom as prenom, a.nom as nom FROM AppBundle:UoAgent ua
                        INNER JOIN AppBundle:Agent a with ua.agent = a
                        INNER JOIN AppBundle:Equipe e with a.equipe = e
                        INNER JOIN AppBundle:Bo b with e.bo = b
                        INNER JOIN AppBundle:Agence ag with b.agence = ag
                        INNER JOIN AppBundle:Dr dr with ag.dr = dr
                        INNER JOIN AppBundle:UO uo with ua.uo = uo
                        INNER JOIN AppBundle:SousDomaine sd with uo.sousdomaine = sd
                        INNER JOIN AppBundle:Domaine d with sd.domaine = d
                        WHERE ua.niveau >= 4
                        ORDER BY domaine, sousdomaine, uo_code ASC")
        ->getResult();
    }
    public function getResultAgent($id_agent){
        return $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, sd.nom as sousdomaine, AVG(UoAg.niveau) as niveau,
            ag.id as agent_id, sd.id as sd_id, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            WHERE ag.id = $id_agent
            GROUP BY sd.nom
            ORDER BY domaine, sousdomaine ASC")
            ->getResult();
    }
    public function getResultAgentDom($id_agent){
        return $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, sd.nom as sousdomaine, AVG(UoAg.niveau) as niveau,
            ag.id as agent_id, sd.id as sd_id, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            WHERE ag.id = $id_agent
            GROUP BY d.nom
            ORDER BY domaine, sousdomaine ASC")
            ->getResult();
    }
    public function getResultAgentInEcart(){
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(a.nni) as nni FROM AppBundle:UoAgent u INNER JOIN AppBundle:Agent a WITH u.agent = a 
            WHERE u.nPlusUn - u.niveau > 0 OR u.nPlusDeux - u.niveau > 0 OR u.nPlusTrois - u.niveau > 0 
            ORDER BY nni  ASC")
            ->getResult();
    }
    public function getResultAllBo(){
        return $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, sd.nom as sousdomaine, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, 
            sd.id as sd_id, AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            GROUP BY sd.nom
            ORDER BY domaine, sousdomaine ASC")
            ->getResult();
    }
    public function make_filtre($valeurs_filtre, $table, $colonne){
        if(is_array($valeurs_filtre)){
            if(count($valeurs_filtre) > 1){
                $chaine_valeurs = "";
                foreach($valeurs_filtre as $p){
                    if(!is_array($p)){
                        $chaine_valeurs .= "'".$p."', ";
                    }
                    else{
                        foreach($p as $sous_p){
                            $chaine_valeurs .= "'".$sous_p."', ";
                        }
                    }
                }
                $chaine_valeurs = substr($chaine_valeurs, 0, strlen($chaine_valeurs) - 2);
                $chaine_valeurs = " IN (".$chaine_valeurs.")";
                $nom_colonne = $table.".".$colonne;
            }
            else{
                if($valeurs_filtre[0] == ""){
                    $chaine_valeurs = " = 1";
                    $nom_colonne = "1";
                }
                else{
                    $chaine_valeurs = "'".$valeurs_filtre[0]."'";
                    $nom_colonne = $table.".".$colonne." = ";
                }
            }
        }
        else{
            if($valeurs_filtre != ""){
                $chaine_valeurs = "'".$valeurs_filtre."'";
                $nom_colonne = $table.".".$colonne." = ";
            }
            else{
                $nom_colonne = "1 = ";
                $chaine_valeurs = "1";
            }
        }
        
        $data = [];
        array_push($data, $chaine_valeurs); 
        array_push($data, $nom_colonne); 

        return $data;
    }
    public function getResultAllBoByFiltersNoAgent($pole, $bo, $profil, $astreinte, $domaine){
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

        if($domaine == ""){
            $domaine = 1;
            $col_domaine = 1;
        }
        else{
            $col_domaine = "d.nom";
        }
        
        $data = [];
        $data = $this->getEntityManager()
            ->createQuery("SELECT b.pole as pole, b.nom as bo, d.nom as domaine, d.id as domaine_id, sd.nom as sousdomaine, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND $col_domaine = '$domaine'
            GROUP BY domaine, sousdomaine
            ORDER BY domaine, sousdomaine, pole, bo ASC")
            ->getResult();
            
            return $data;
    }
    public function getResultatBonhommes($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];
        
        $data = [];
        $data = $this->getEntityManager()
            ->createQuery("SELECT b.nom as bo, d.nom as domaine, AVG(UoAg.niveau) as niveau, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_bo $bo
            GROUP BY bo, domaine
            ORDER BY bo, domaine ASC")
            ->getResult();
            
            return $data;
    }
    public function getResultatsTrait(){
        $data = [];
        $data = $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, AVG(UoAg.niveau) as niveau, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            GROUP BY domaine
            ORDER BY domaine ASC")
            ->getResult();
            
            return $data;
    }
    public function getResultatBonhommes_Agents($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];
        
        $data = [];
        $data = $this->getEntityManager()
            ->createQuery("SELECT b.nom as bo, d.nom as domaine, ag.nom as nom, ag.prenom as prenom, UoAg.niveau as niveau, 
            UoAg.nPlusUn as nPlusUn, UoAg.nPlusDeux as nPlusDeux, UoAg.nPlusTrois as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_bo $bo
            GROUP BY bo, domaine, ag.nni
            ORDER BY bo, domaine, ag.nom ASC")
            ->getResult();
            
            return $data;
    }
    public function getResultatsTrait_Agents($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        $data = [];
        $data = $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, AVG(UoAg.niveau) as niveau, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_bo $bo
            GROUP BY domaine
            ORDER BY domaine ASC")
            ->getResult();
            
            return $data;
    }
    public function getResultAllBoByFiltersAgent($agents_nni, $domaine){
        $ags_nni = "";
        if($domaine == ""){
            $domaine = 1;
            $col_domaine = 1;
        }
        else{
            $col_domaine = "d.nom";
        }

        if(count($agents_nni > 1)){
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni = substr($ags_nni, 0, strlen($ags_nni) - 2);

            return $this->getEntityManager()
                ->createQuery("SELECT b.pole as pole, b.nom as bo, d.nom as domaine, d.id as domaine_id, sd.nom as sousdomaine, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND $col_domaine = '$domaine'
                GROUP BY domaine, sousdomaine
                ORDER BY domaine, sousdomaine, pole, bo ASC")
                ->getResult();
        }
        else{
            $agent_nni = $agents_nni[0];
            return $this->getEntityManager()
                ->createQuery("SELECT b.pole as pole, b.nom as bo, d.nom as domaine, d.id as domaine_id, sd.nom as sousdomaine, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND d.nom = '$domaine'
                GROUP BY domaine, sousdomaine
                ORDER BY domaine, sousdomaine, pole, bo ASC")
                ->getResult();
        }
    }
    public function getResultAllBoByFiltersDomaine($pole, $bo, $profil, $astreinte){
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

        $data = $this->getEntityManager()
            ->createQuery("SELECT b.pole as pole, b.nom as bo, d.nom as domaine, d.id as domaine_id, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            GROUP BY domaine
            ORDER BY domaine, pole, bo ASC")
            ->getResult();

            return $data;
    }
    public function getResultAllBoByFiltersDomaineAgents($agents_nni){
        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);

            return $this->getEntityManager()
                ->createQuery("SELECT b.pole as pole, b.nom as bo, d.nom as domaine, d.id as domaine_id, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                GROUP BY domaine
                ORDER BY domaine, pole, bo ASC")
                ->getResult();
        }
        else{
            $agent_nni = $agents_nni[0];
            return $this->getEntityManager()
                ->createQuery("SELECT b.pole as pole, b.nom as bo, d.nom as domaine, d.id as domaine_id, COUNT(DISTINCT(ag.id)) as nb_agents, AVG(UoAg.niveau) as niveau, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                GROUP BY domaine
                ORDER BY domaine, pole, bo ASC")
                ->getResult();
        }
    }
    public function getResultAgentsByFiltersAgents($agents, $sd_id){
        $agents_size = count($agents);
        if($agents_size == 1){
            $agent_nni = $agents[0];
            $data = $this->getEntityManager()
                ->createQuery("SELECT ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois, ag.id as agent_id, sd.nom as sousdomaine, d.nom as domaine
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND sd.id = '$sd_id'
                GROUP BY ag.nni
                ORDER BY ag.nom ASC")
                ->getResult();
        }
        else{
            $ags_nni = "";
            foreach($agents as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            $data = $this->getEntityManager()
                ->createQuery("SELECT ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois, ag.id as agent_id, sd.nom as sousdomaine, d.nom as domaine
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND sd.id = '$sd_id'
                GROUP BY ag.nni
                ORDER BY ag.nom ASC")
                ->getResult();
        }

        return $data;
    }
    public function getResultAgentsByFiltersNoAgents($pole, $bo, $profil, $astreinte, $sd_id){
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

        $data = $this->getEntityManager()
            ->createQuery("SELECT ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois, ag.id as agent_id, sd.nom as sousdomaine, d.nom as domaine
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND sd.id = '$sd_id'
            GROUP BY ag.nni
            ORDER BY ag.nom ASC")
            ->getResult();

        return $data;
    }
    public function getResultAgentsByFiltersForAIE($pole, $bo, $profil, $astreinte){
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
            ->createQuery("SELECT ag.id as agent_id, ag.nni as nni, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, UoAg.niveau as niveau, 
            UoAg.nPlusUn as nPlusUn, UoAg.nPlusDeux as nPlusDeux, UoAg.nPlusTrois as nPlusTrois, u.code as uo_code
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            ORDER BY ag.nom ASC")
            ->getResult();
    }
    public function getResultAgentsByFiltersDomaineNoAgent($pole, $bo, $profil, $astreinte, $d_id){
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
            ->createQuery("SELECT ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois, ag.id as agent_id, d.nom as domaine
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND d.id = '$d_id'
            GROUP BY ag.nni
            ORDER BY ag.nom ASC")
            ->getResult();
    }
    public function getEvolMMS($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        return $this->getEntityManager()
            ->createQuery("SELECT count(distinct(ag.nni)) as nb_agents, b.nom as bo, d.nom as domaine FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where ua.niveau > 0
            group by b.nom, d.nom")
            ->getResult();
    }
    public function getEvolMMS_nPlus($nPlus){
        return $this->getEntityManager()
            ->createQuery("SELECT count(distinct(ag.nni)) as nb_agents, b.nom as bo, d.nom as domaine FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where $nPlus > 0
            group by b.nom, d.nom")
            ->getResult();
    }
    public function getEvolMMS_Agents($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        $dat = $this->getEntityManager()
            ->createQuery("SELECT count(distinct(u.code)) as nb_uos, b.nom as bo, d.nom as domaine, ag.nom as nom, ag.prenom as prenom, ag.nni as nni, 
            AVG(ua.niveau) as niveau, AVG(ua.nPlusUn) as nPlusUn, AVG(ua.nPlusDeux) as nPlusDeux, AVG(ua.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where ua.niveau > 0
            and $col_bo $bo
            group by b.nom, d.nom, ag.nni
            order by ag.nom")
            ->getResult();

        $dat2 =  $this->getEntityManager()
            ->createQuery("SELECT count(distinct(u.code)) as nb_uos, b.nom as bo, d.nom as domaine, ag.nom as nom, ag.prenom as prenom, ag.nni as nni FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where $col_bo $bo
            group by b.nom, d.nom, ag.nni
            order by ag.nom")
            ->getResult();

        $dat3 =  $this->getEntityManager()
            ->createQuery("SELECT b.nom as bo, d.nom as domaine, 
            AVG(ua.niveau) as niveau, AVG(ua.nPlusUn) as nPlusUn, AVG(ua.nPlusDeux) as nPlusDeux, AVG(ua.nPlusTrois) as nPlusTrois FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where $col_bo $bo
            group by b.nom, d.nom
            order by ag.nom")
            ->getResult();

            $dat4 =  $this->getEntityManager()
            ->createQuery("SELECT count(distinct(u.code)) as nb_uos, b.nom as bo, d.nom as domaine FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where $col_bo $bo
            group by b.nom, d.nom
            ")
            ->getResult();
        
        $data = [];
        $data['uo_checked'] = $dat;
        $data['uo_toutes'] = $dat2;
        $data['niveau_bo'] = $dat3;
        $data['nb_uos_bo'] = $dat4;
            
        return $data;
    }
    public function getEvolMMS_nPlus_Agents($nPlus, $bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        return $this->getEntityManager()
            ->createQuery("SELECT count(distinct(u.code)) as nb_uos, b.nom as bo, d.nom as domaine, ag.nom as nom, ag.prenom as prenom, ag.nni as nni FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where $nPlus > 0
            and $col_bo $bo
            group by b.nom, d.nom, ag.nni
            order by ag.nom")
            ->getResult();
    }
    public function getCompletudeMMS($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        return $data;

        /*return $this->getEntityManager()
            ->createQuery("SELECT  b.nom as bo, d.nom as domaine, count(distinct(u.code)) as nb_uos FROM AppBundle:UO u
            inner join AppBundle:UoAgent ua WITH u.UoAgents MEMBER OF ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            where ua.niveau > 0")
            ->getResult();*/
    }
    public function getUoMMS($bo){
        $bo_tab = $this->make_filtre($bo, 'b', 'nom');
        $bo = $bo_tab[0];
        $col_bo = $bo_tab[1];

        return $this->getEntityManager()
            ->createQuery("SELECT count(distinct(u.code)) as nb_uos, b.nom as bo, d.nom as domaine FROM AppBundle:UoAgent ua
            inner join AppBundle:Agent ag WITH ua.agent = ag
            inner join AppBundle:Equipe eq WITH ag.equipe = eq
            inner join AppBundle:Bo b WITH eq.bo = b
            inner join AppBundle:UO u WITH ua.uo = u
            inner join AppBundle:SousDomaine s WITH u.sousdomaine = s
            inner join AppBundle:Domaine d WITH s.domaine = d
            group by b.nom, d.nom")
            ->getResult();
    }
    public function getResultAgentsByFiltersDomaineAgent($agents, $d_id){
        $agents_size = count($agents);

        if($agents_size == 1){
            $agent_nni = $agents[0];
            return $this->getEntityManager()
                ->createQuery("SELECT ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois, ag.id as agent_id, d.nom as domaine
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND d.id = '$d_id'
                GROUP BY ag.nni
                ORDER BY ag.nom ASC")
                ->getResult();
        }
        else{
            $ags_nni = "";
            foreach($agents as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            return $this->getEntityManager()
                ->createQuery("SELECT ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo, AVG(UoAg.niveau) as niveau, sd.id as sd_id, 
                AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois, ag.id as agent_id, d.nom as domaine
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND d.id = '$d_id'
                GROUP BY ag.nni
                ORDER BY ag.nom ASC")
                ->getResult();
        }

    }
    public function getResultAgentUO($id_agent){
        return $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, sd.nom as sousdomaine, u.nom as uo_nom, u.code as uo_code, UoAg.niveau as niveau,
            ag.id as agent_id, sd.id as sd_id, 
            UoAg.nPlusUn as nPlusUn, UoAg.nPlusDeux as nPlusDeux, UoAg.nPlusTrois as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            WHERE ag.id = $id_agent
            ORDER BY domaine, sousdomaine, uo_code ASC")
            ->getResult();
    }
    public function getResultGeoUO($pole, $bo, $profil, $astreinte){ 
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

        $data = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents, u.nom as uo_nom, u.code as uo_code, AVG(UoAg.niveau) as niveau
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            GROUP BY u.code
            ORDER BY uo_code ASC")
            ->getResult();
        $data_astreinte = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND ag.abstreinte = 'oui'
            ")
            ->getResult();

        $uos_prv = "('01E', '02E', '03E', '04E', '08E', '09E', 1001, 1002, 1004, 1009, 1018, 1022, 1024, 1025, 1026, 1028, 1029, 1031, 1037, 1044, 1053, 1055, 1062, 1069, 1070, 1071, 1074, 1076, '10E', 1129, '11E', '14E', '15E', '16E', '17E', '18E', '19E', '20E', '21E', '22E', '23E', '24E', '25E', '26E', '27E', '28E', '41E', '42E', '50E', '51E', '52E', '53E', '54E', '55E', '80E', '82E', '88E', 'B5E', 'B9E', 'C4E', 'C8E', 'C9E', 'D0E', 'D1E', 'D2E', 'D6E', 'D8E', 'L2E', 'L4E', 'L5E', 'L6E', 'L9E', 'N4E', 'P0E', 'P1E', 'P2E', 'P3E', 'P4E', 'P5E', 'P6E', 'P7E', 'P8E', 'P9E', 'R0E', 'R1E', 'R2E', 'R3E', 'R7E', 'R8E', 'S7E', 'U0E', 'U1E', 'U2E', 'U3E', 'U5E', 'UO126', 'UO127', 'W0E', 'W1E', 'W5E', 'W6E', 'W8E', 'X2E', 'X3E', 'X4E', 'Y0E', 'Y1E', 'Y2E', 'Y3E', 'Y4E', 'Y5E', 'Y7E', 'Y8E', 'Y9E', 'Z1E', 'Z2E', 'Z4E', 'Z5E', 'Z8E', 'Z9E')";
        
        $data_prv = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND u.code IN $uos_prv
            AND UoAg.niveau >= 3
            ")
            ->getResult();
        $data_pst = $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND UoAg.niveau = 4
            ")
            ->getResult();

            $data['nb_agent_astreinte'] = $data_astreinte;
            $data['prv'] = $data_prv;
            $data['pst'] = $data_pst;

            return $data;
    }
    public function getResultGeoUOAgents($agents_nni){ 
        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);    

            $data = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents, u.nom as uo_nom, u.code as uo_code, AVG(UoAg.niveau) as niveau
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni) 
                GROUP BY u.code
                ORDER BY uo_code ASC")
                ->getResult();
            $data_astreinte = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND ag.abstreinte = 'oui'
                ")
                ->getResult();

            $uos_prv = "('01E', '02E', '03E', '04E', '08E', '09E', 1001, 1002, 1004, 1009, 1018, 1022, 1024, 1025, 1026, 1028, 1029, 1031, 1037, 1044, 1053, 1055, 1062, 1069, 1070, 1071, 1074, 1076, '10E', 1129, '11E', '14E', '15E', '16E', '17E', '18E', '19E', '20E', '21E', '22E', '23E', '24E', '25E', '26E', '27E', '28E', '41E', '42E', '50E', '51E', '52E', '53E', '54E', '55E', '80E', '82E', '88E', 'B5E', 'B9E', 'C4E', 'C8E', 'C9E', 'D0E', 'D1E', 'D2E', 'D6E', 'D8E', 'L2E', 'L4E', 'L5E', 'L6E', 'L9E', 'N4E', 'P0E', 'P1E', 'P2E', 'P3E', 'P4E', 'P5E', 'P6E', 'P7E', 'P8E', 'P9E', 'R0E', 'R1E', 'R2E', 'R3E', 'R7E', 'R8E', 'S7E', 'U0E', 'U1E', 'U2E', 'U3E', 'U5E', 'UO126', 'UO127', 'W0E', 'W1E', 'W5E', 'W6E', 'W8E', 'X2E', 'X3E', 'X4E', 'Y0E', 'Y1E', 'Y2E', 'Y3E', 'Y4E', 'Y5E', 'Y7E', 'Y8E', 'Y9E', 'Z1E', 'Z2E', 'Z4E', 'Z5E', 'Z8E', 'Z9E')";
            $data_prv = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND u.code IN $uos_prv
                AND UoAg.niveau >= 3
                ")
                ->getResult();
            $data_pst = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND UoAg.niveau = 4
                ")
                ->getResult();

                $data['nb_agent_astreinte'] = $data_astreinte;
                $data['prv'] = $data_prv;
                $data['pst'] = $data_pst;
        }
        else{
            $agent_nni = $agents_nni[0];
            $data = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents, u.nom as uo_nom, u.code as uo_code, AVG(UoAg.niveau) as niveau
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
                INNER JOIN AppBundle:Domaine d with sd.domaine = d
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                GROUP BY u.code
                ORDER BY uo_code ASC")
                ->getResult();
            $data_astreinte = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND ag.abstreinte = 'oui'
                ")
                ->getResult();

            $uos_prv = "('01E', '02E', '03E', '04E', '08E', '09E', 1001, 1002, 1004, 1009, 1018, 1022, 1024, 1025, 1026, 1028, 1029, 1031, 1037, 1044, 1053, 1055, 1062, 1069, 1070, 1071, 1074, 1076, '10E', 1129, '11E', '14E', '15E', '16E', '17E', '18E', '19E', '20E', '21E', '22E', '23E', '24E', '25E', '26E', '27E', '28E', '41E', '42E', '50E', '51E', '52E', '53E', '54E', '55E', '80E', '82E', '88E', 'B5E', 'B9E', 'C4E', 'C8E', 'C9E', 'D0E', 'D1E', 'D2E', 'D6E', 'D8E', 'L2E', 'L4E', 'L5E', 'L6E', 'L9E', 'N4E', 'P0E', 'P1E', 'P2E', 'P3E', 'P4E', 'P5E', 'P6E', 'P7E', 'P8E', 'P9E', 'R0E', 'R1E', 'R2E', 'R3E', 'R7E', 'R8E', 'S7E', 'U0E', 'U1E', 'U2E', 'U3E', 'U5E', 'UO126', 'UO127', 'W0E', 'W1E', 'W5E', 'W6E', 'W8E', 'X2E', 'X3E', 'X4E', 'Y0E', 'Y1E', 'Y2E', 'Y3E', 'Y4E', 'Y5E', 'Y7E', 'Y8E', 'Y9E', 'Z1E', 'Z2E', 'Z4E', 'Z5E', 'Z8E', 'Z9E')";
            $data_prv = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND u.code IN $uos_prv
                AND UoAg.niveau >= 3
                ")
                ->getResult();
            $data_pst = $this->getEntityManager()
                ->createQuery("SELECT COUNT(DISTINCT(ag.nni)) as nb_agents
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND UoAg.niveau = 4
                ")
                ->getResult();

                $data['nb_agent_astreinte'] = $data_astreinte;
                $data['prv'] = $data_prv;
                $data['pst'] = $data_pst;
        }

            return $data;
    }
    public function getAgentsPSTAgent($agents_nni, $pst_or_not){
        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            if($pst_or_not == 'oui'){
                return $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni IN ($ags_nni)
                    AND UoAg.niveau >= 4
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
            }
            else{
                $data = [];
                $data['nni'] = [];
                $data['nom'] = [];
                $data['prenom'] = [];
                $data['pole'] = [];
                $data['bo'] = [];
                $data_pst = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni IN ($ags_nni)
                    AND UoAg.niveau >= 4
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
                $data_tot = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni IN ($ags_nni)
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
                foreach($data_tot as $row_tot){
                    $isPst = false;
                    foreach($data_pst as $row_pst){
                        if($row_tot['nni'] == $row_pst['nni']){
                            $isPst = true;
                    
                            break;
                        }
                    }
                    if(!$isPst){
                        array_push($data['nni'], $row_tot['nni']); 
                        array_push($data['nom'], $row_tot['nom']); 
                        array_push($data['prenom'], $row_tot['prenom']); 
                        array_push($data['pole'], $row_tot['pole']); 
                        array_push($data['bo'], $row_tot['bo']); 
                    }
                }
            }
        }
        else{
            if($pst_or_not == 'oui'){
                return $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni = '$agent_nni'
                    AND UoAg.niveau >= 4
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
            }
            else{
                $agent_nni = $agents_nni[0];
                $data = [];
                $data['nni'] = [];
                $data['nom'] = [];
                $data['prenom'] = [];
                $data['pole'] = [];
                $data['bo'] = [];
                $data_pst = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni = '$agent_nni'
                    AND UoAg.niveau >= 4
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
                $data_tot = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni = '$agent_nni'
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
                foreach($data_tot as $row_tot){
                    $isPst = false;
                    foreach($data_pst as $row_pst){
                        if($row_tot['nni'] == $row_pst['nni']){
                            $isPst = true;
                    
                            break;
                        }
                    }
                    if(!$isPst){
                        array_push($data['nni'], $row_tot['nni']); 
                        array_push($data['nom'], $row_tot['nom']); 
                        array_push($data['prenom'], $row_tot['prenom']); 
                        array_push($data['pole'], $row_tot['pole']); 
                        array_push($data['bo'], $row_tot['bo']); 
                    }
                }
            }
        }
        return $data;
    }
    
    public function getAgentsPSTNoAgent($pole, $bo, $profil, $astreinte, $pst_or_not){
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

        if($pst_or_not == 'oui'){
            return $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                AND UoAg.niveau >= 4
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
        }
        else{
            $data = [];
            $data['nni'] = [];
            $data['nom'] = [];
            $data['prenom'] = [];
            $data['pole'] = [];
            $data['bo'] = [];
            $data_pst = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                AND UoAg.niveau >= 4
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
            $data_tot = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
            foreach($data_tot as $row_tot){
                $isPst = false;
                foreach($data_pst as $row_pst){
                    if($row_tot['nni'] == $row_pst['nni']){
                        $isPst = true;
                
                        break;
                    }
                }
                if(!$isPst){
                    array_push($data['nni'], $row_tot['nni']); 
                    array_push($data['nom'], $row_tot['nom']); 
                    array_push($data['prenom'], $row_tot['prenom']); 
                    array_push($data['pole'], $row_tot['pole']); 
                    array_push($data['bo'], $row_tot['bo']); 
                }
            }
            return $data;
        }
    }
    public function getAgentsPRVNoAgent($pole, $bo, $profil, $astreinte, $prv_or_not){
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

        $uos_prv = "('01E', '02E', '03E', '04E', '08E', '09E', 1001, 1002, 1004, 1009, 1018, 1022, 1024, 1025, 1026, 1028, 1029, 1031, 1037, 1044, 1053, 1055, 1062, 1069, 1070, 1071, 1074, 1076, '10E', 1129, '11E', '14E', '15E', '16E', '17E', '18E', '19E', '20E', '21E', '22E', '23E', '24E', '25E', '26E', '27E', '28E', '41E', '42E', '50E', '51E', '52E', '53E', '54E', '55E', '80E', '82E', '88E', 'B5E', 'B9E', 'C4E', 'C8E', 'C9E', 'D0E', 'D1E', 'D2E', 'D6E', 'D8E', 'L2E', 'L4E', 'L5E', 'L6E', 'L9E', 'N4E', 'P0E', 'P1E', 'P2E', 'P3E', 'P4E', 'P5E', 'P6E', 'P7E', 'P8E', 'P9E', 'R0E', 'R1E', 'R2E', 'R3E', 'R7E', 'R8E', 'S7E', 'U0E', 'U1E', 'U2E', 'U3E', 'U5E', 'UO126', 'UO127', 'W0E', 'W1E', 'W5E', 'W6E', 'W8E', 'X2E', 'X3E', 'X4E', 'Y0E', 'Y1E', 'Y2E', 'Y3E', 'Y4E', 'Y5E', 'Y7E', 'Y8E', 'Y9E', 'Z1E', 'Z2E', 'Z4E', 'Z5E', 'Z8E', 'Z9E')";
        if($prv_or_not == 'oui'){
            return $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                AND u.code IN $uos_prv
                AND UoAg.niveau >= 3
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
        }
        else{
            $data = [];
            $data['nni'] = [];
            $data['nom'] = [];
            $data['prenom'] = [];
            $data['pole'] = [];
            $data['bo'] = [];
            $data_prv = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                AND u.code IN $uos_prv
                AND UoAg.niveau >= 3
                ORDER BY b.pole, b.nom, ag.nom ASC
            ")
            ->getResult();
            $data_tot = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
            foreach($data_tot as $row_tot){
                $isPrv = false;
                foreach($data_prv as $row_prv){
                    if($row_tot['nni'] == $row_prv['nni']){
                        $isPrv = true;
                
                        break;
                    }
                }
                if(!$isPrv){
                    array_push($data['nni'], $row_tot['nni']); 
                    array_push($data['nom'], $row_tot['nom']); 
                    array_push($data['prenom'], $row_tot['prenom']); 
                    array_push($data['pole'], $row_tot['pole']); 
                    array_push($data['bo'], $row_tot['bo']); 
                }
            }
            return $data;
        }
    }
    public function getAgentsPRVAgent($agents_nni, $prv_or_not){
        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            $uos_prv = "('01E', '02E', '03E', '04E', '08E', '09E', 1001, 1002, 1004, 1009, 1018, 1022, 1024, 1025, 1026, 1028, 1029, 1031, 1037, 1044, 1053, 1055, 1062, 1069, 1070, 1071, 1074, 1076, '10E', 1129, '11E', '14E', '15E', '16E', '17E', '18E', '19E', '20E', '21E', '22E', '23E', '24E', '25E', '26E', '27E', '28E', '41E', '42E', '50E', '51E', '52E', '53E', '54E', '55E', '80E', '82E', '88E', 'B5E', 'B9E', 'C4E', 'C8E', 'C9E', 'D0E', 'D1E', 'D2E', 'D6E', 'D8E', 'L2E', 'L4E', 'L5E', 'L6E', 'L9E', 'N4E', 'P0E', 'P1E', 'P2E', 'P3E', 'P4E', 'P5E', 'P6E', 'P7E', 'P8E', 'P9E', 'R0E', 'R1E', 'R2E', 'R3E', 'R7E', 'R8E', 'S7E', 'U0E', 'U1E', 'U2E', 'U3E', 'U5E', 'UO126', 'UO127', 'W0E', 'W1E', 'W5E', 'W6E', 'W8E', 'X2E', 'X3E', 'X4E', 'Y0E', 'Y1E', 'Y2E', 'Y3E', 'Y4E', 'Y5E', 'Y7E', 'Y8E', 'Y9E', 'Z1E', 'Z2E', 'Z4E', 'Z5E', 'Z8E', 'Z9E')";
            if($prv_or_not == 'oui'){
                return $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:UO u with UoAg.uo = u
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni IN ($ags_nni)
                    AND u.code IN $uos_prv
                    AND UoAg.niveau >= 3
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
            }
            else{
                $data = [];
                $data['nni'] = [];
                $data['nom'] = [];
                $data['prenom'] = [];
                $data['pole'] = [];
                $data['bo'] = [];
                $data_prv = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:UO u with UoAg.uo = u
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni IN ($ags_nni)
                    AND u.code IN $uos_prv
                    AND UoAg.niveau >= 3
                    ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
                $data_tot = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni IN ($ags_nni)
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
                foreach($data_tot as $row_tot){
                    $isPrv = false;
                    foreach($data_prv as $row_prv){
                        if($row_tot['nni'] == $row_prv['nni']){
                            $isPrv = true;
                    
                            break;
                        }
                    }
                    if(!$isPrv){
                        array_push($data['nni'], $row_tot['nni']); 
                        array_push($data['nom'], $row_tot['nom']); 
                        array_push($data['prenom'], $row_tot['prenom']); 
                        array_push($data['pole'], $row_tot['pole']); 
                        array_push($data['bo'], $row_tot['bo']); 
                    }
                }
            }
            return $data;
        }
        else{
            $agent_nni = $agents_nni[0];
            $uos_prv = "('01E', '02E', '03E', '04E', '08E', '09E', 1001, 1002, 1004, 1009, 1018, 1022, 1024, 1025, 1026, 1028, 1029, 1031, 1037, 1044, 1053, 1055, 1062, 1069, 1070, 1071, 1074, 1076, '10E', 1129, '11E', '14E', '15E', '16E', '17E', '18E', '19E', '20E', '21E', '22E', '23E', '24E', '25E', '26E', '27E', '28E', '41E', '42E', '50E', '51E', '52E', '53E', '54E', '55E', '80E', '82E', '88E', 'B5E', 'B9E', 'C4E', 'C8E', 'C9E', 'D0E', 'D1E', 'D2E', 'D6E', 'D8E', 'L2E', 'L4E', 'L5E', 'L6E', 'L9E', 'N4E', 'P0E', 'P1E', 'P2E', 'P3E', 'P4E', 'P5E', 'P6E', 'P7E', 'P8E', 'P9E', 'R0E', 'R1E', 'R2E', 'R3E', 'R7E', 'R8E', 'S7E', 'U0E', 'U1E', 'U2E', 'U3E', 'U5E', 'UO126', 'UO127', 'W0E', 'W1E', 'W5E', 'W6E', 'W8E', 'X2E', 'X3E', 'X4E', 'Y0E', 'Y1E', 'Y2E', 'Y3E', 'Y4E', 'Y5E', 'Y7E', 'Y8E', 'Y9E', 'Z1E', 'Z2E', 'Z4E', 'Z5E', 'Z8E', 'Z9E')";
            if($prv_or_not == 'oui'){
                return $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:UO u with UoAg.uo = u
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni = '$agent_nni'
                    AND u.code IN $uos_prv
                    AND UoAg.niveau >= 3
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
            }
            else{
                $data = [];
                $data['nni'] = [];
                $data['nom'] = [];
                $data['prenom'] = [];
                $data['pole'] = [];
                $data['bo'] = [];
                $data_prv = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:UO u with UoAg.uo = u
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni = '$agent_nni'
                    AND u.code IN $uos_prv
                    AND UoAg.niveau >= 3
                    ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
                $data_tot = $this->getEntityManager()
                    ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                    FROM AppBundle:UoAgent UoAg
                    INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                    INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                    INNER JOIN AppBundle:Bo b with eq.bo = b
                    WHERE ag.nni = '$agent_nni'
                    ORDER BY b.pole, b.nom, ag.nom ASC
                    ")
                    ->getResult();
                foreach($data_tot as $row_tot){
                    $isPrv = false;
                    foreach($data_prv as $row_prv){
                        if($row_tot['nni'] == $row_prv['nni']){
                            $isPrv = true;
                    
                            break;
                        }
                    }
                    if(!$isPrv){
                        array_push($data['nni'], $row_tot['nni']); 
                        array_push($data['nom'], $row_tot['nom']); 
                        array_push($data['prenom'], $row_tot['prenom']); 
                        array_push($data['pole'], $row_tot['pole']); 
                        array_push($data['bo'], $row_tot['bo']); 
                    }
                }
            }
            return $data;
        }
    }
    
    public function getAgentsPremTronNonAgent($agents_nni){
        $data = [];
        $data['nni'] = [];
        $data['nom'] = [];
        $data['prenom'] = [];
        $data['pole'] = [];
        $data['bo'] = [];

        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            $data_prem = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND u.code = 'UO175'
                AND UoAg.niveau >= 3
                ORDER BY b.pole, b.nom, ag.nom ASC
            ")
            ->getResult();
            $data_tot = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni IN ($ags_nni)
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
            foreach($data_tot as $row_tot){
                $isPrem = false;
                foreach($data_prem as $row_prem){
                    if($row_tot['nni'] == $row_prem['nni']){
                        $isPrem = true;
                
                        break;
                    }
                }
                if(!$isPrem){
                    array_push($data['nni'], $row_tot['nni']); 
                    array_push($data['nom'], $row_tot['nom']); 
                    array_push($data['prenom'], $row_tot['prenom']); 
                    array_push($data['pole'], $row_tot['pole']); 
                    array_push($data['bo'], $row_tot['bo']); 
                }
            }
        }
        else{
            $agent_nni = $agents_nni[0];
            $data_prem = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                AND u.code = 'UO175'
                AND UoAg.niveau >= 3
                ORDER BY b.pole, b.nom, ag.nom ASC
            ")
            ->getResult();
            $data_tot = $this->getEntityManager()
                ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
                INNER JOIN AppBundle:Bo b with eq.bo = b
                WHERE ag.nni = '$agent_nni'
                ORDER BY b.pole, b.nom, ag.nom ASC
                ")
                ->getResult();
            foreach($data_tot as $row_tot){
                $isPrem = false;
                foreach($data_prem as $row_prem){
                    if($row_tot['nni'] == $row_prem['nni']){
                        $isPrem = true;
                
                        break;
                    }
                }
                if(!$isPrem){
                    array_push($data['nni'], $row_tot['nni']); 
                    array_push($data['nom'], $row_tot['nom']); 
                    array_push($data['prenom'], $row_tot['prenom']); 
                    array_push($data['pole'], $row_tot['pole']); 
                    array_push($data['bo'], $row_tot['bo']); 
                }
            }
        }
        return $data;
    }
    public function getAgentsPremTronNonNoAgent($pole, $bo, $profil, $astreinte){
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
        
        $data = [];
        $data['nni'] = [];
        $data['nom'] = [];
        $data['prenom'] = [];
        $data['pole'] = [];
        $data['bo'] = [];
        $data_prem = $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND u.code = 'UO175'
            AND UoAg.niveau >= 3
            ORDER BY b.pole, b.nom, ag.nom ASC
        ")
        ->getResult();
        $data_tot = $this->getEntityManager()
            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.nom as nom, ag.prenom as prenom, b.nom as bo, b.pole as pole
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe eq with ag.equipe = eq
            INNER JOIN AppBundle:Bo b with eq.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            ORDER BY b.pole, b.nom, ag.nom ASC
            ")
            ->getResult();
        foreach($data_tot as $row_tot){
            $isPrem = false;
            foreach($data_prem as $row_prem){
                if($row_tot['nni'] == $row_prem['nni']){
                    $isPrem = true;
            
                    break;
                }
            }
            if(!$isPrem){
                array_push($data['nni'], $row_tot['nni']); 
                array_push($data['nom'], $row_tot['nom']); 
                array_push($data['prenom'], $row_tot['prenom']); 
                array_push($data['pole'], $row_tot['pole']); 
                array_push($data['bo'], $row_tot['bo']); 
            }
        }
        return $data;
    }
    public function getResultBoDom($bo_id){
        return $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, AVG(UoAg.niveau) as niveau,
            ag.id as agent_id, sd.id as sd_id,
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE b.id = $bo_id
            GROUP BY d.nom
            ORDER BY domaine ASC")
            ->getResult();
    }
    public function getResultBoSdom($bo_id){
        return $this->getEntityManager()
            ->createQuery("SELECT d.nom as domaine, sd.nom as sousdomaine, AVG(UoAg.niveau) as niveau,
            ag.id as agent_id, sd.id as sd_id,
            AVG(UoAg.nPlusUn) as nPlusUn, AVG(UoAg.nPlusDeux) as nPlusDeux, AVG(UoAg.nPlusTrois) as nPlusTrois
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:SousDomaine sd with u.sousdomaine = sd
            INNER JOIN AppBundle:Domaine d with sd.domaine = d
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE b.id = $bo_id
            GROUP BY sd.nom
            ORDER BY domaine, sousdomaine ASC")
            ->getResult();
    }
    public function getResultNbAgentsByHab_niv($pole, $bo, $profil, $astreinte, $uos){
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

        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        return $this->getEntityManager()
            ->createQuery("SELECT COUNT(ag.id) as nb_doublons, ag.id, MIN(UoAg.niveau) as niveau_min
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND ($uos_txt)
            GROUP BY ag.id
            HAVING COUNT(ag.id) >= 1
            ORDER BY niveau_min ASC")
            ->getResult();
    }
    public function getAgentsByHabAgent($agents_nni, $uos, $niveau){
        
        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            $uos_txt = "";
            $uo_length = count($uos);
            for($i = 0; $i < $uo_length; $i++){
                $uos_txt .= "u.code = '".$uos[$i]."' or ";       
            }
            $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

            $data_niveau_1 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni IN ($ags_nni)
                            AND UoAg.niveau = 1
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            $data_niveau_2 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni IN ($ags_nni)
                            AND UoAg.niveau = 2
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            $data_niveau_3 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni IN ($ags_nni)
                            AND UoAg.niveau = 3
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            $data_niveau_4 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni IN ($ags_nni)
                            AND UoAg.niveau = 4
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            
            $dn1 = [];
            $dn1['nni'] = [];
            $dn1['nom'] = [];
            $dn1['prenom'] = [];
            $dn1['pole'] = [];
            $dn1['bo'] = [];

            $dn2 = [];
            $dn2['nni'] = [];
            $dn2['nom'] = [];
            $dn2['prenom'] = [];
            $dn2['pole'] = [];
            $dn2['bo'] = [];

            $dn3 = [];
            $dn3['nni'] = [];
            $dn3['nom'] = [];
            $dn3['prenom'] = [];
            $dn3['pole'] = [];
            $dn3['bo'] = [];

            $dn4 = [];
            $dn4['nni'] = [];
            $dn4['nom'] = [];
            $dn4['prenom'] = [];
            $dn4['pole'] = [];
            $dn4['bo'] = [];

            foreach($data_niveau_1 as $row){
                array_push($dn1['nni'], $row['nni']);
                array_push($dn1['nom'], $row['nom']);
                array_push($dn1['prenom'], $row['prenom']);
                array_push($dn1['pole'], $row['pole']);
                array_push($dn1['bo'], $row['bo']);
            }
            foreach($data_niveau_2 as $row){
                array_push($dn2['nni'], $row['nni']);
                array_push($dn2['nom'], $row['nom']);
                array_push($dn2['prenom'], $row['prenom']);
                array_push($dn2['pole'], $row['pole']);
                array_push($dn2['bo'], $row['bo']);
            }
            foreach($data_niveau_3 as $row){
                array_push($dn3['nni'], $row['nni']);
                array_push($dn3['nom'], $row['nom']);
                array_push($dn3['prenom'], $row['prenom']);
                array_push($dn3['pole'], $row['pole']);
                array_push($dn3['bo'], $row['bo']);
            }
            foreach($data_niveau_4 as $row){
                array_push($dn4['nni'], $row['nni']);
                array_push($dn4['nom'], $row['nom']);
                array_push($dn4['prenom'], $row['prenom']);
                array_push($dn4['pole'], $row['pole']);
                array_push($dn4['bo'], $row['bo']);
            }
            $dn3_2 = [];
            $dn3_2['nni'] = [];
            $dn3_2['nom'] = [];
            $dn3_2['prenom'] = [];
            $dn3_2['pole'] = [];
            $dn3_2['bo'] = [];
            $dn3_3 = [];
            $dn3_3['nni'] = [];
            $dn3_3['nom'] = [];
            $dn3_3['prenom'] = [];
            $dn3_3['pole'] = [];
            $dn3_3['bo'] = [];
            $dn2_2 = [];
            $dn2_2['nni'] = [];
            $dn2_2['nom'] = [];
            $dn2_2['prenom'] = [];
            $dn2_2['pole'] = [];
            $dn2_2['bo'] = [];
            for($i = 0; $i < count($dn3['nni']); $i++){
                if(!in_array($dn3['nni'][$i], $dn2['nni'])){
                    array_push($dn3_2['nni'], $dn3['nni'][$i]);
                    array_push($dn3_2['nom'], $dn3['nom'][$i]);
                    array_push($dn3_2['prenom'], $dn3['prenom'][$i]);
                    array_push($dn3_2['pole'], $dn3['pole'][$i]);
                    array_push($dn3_2['bo'], $dn3['bo'][$i]);
                }    
            }
            for($i = 0; $i < count($dn3_2['nni']); $i++){
                if(!in_array($dn3_2['nni'][$i], $dn1['nni'])){
                    array_push($dn3_3['nni'], $dn3_2['nni'][$i]);
                    array_push($dn3_3['nom'], $dn3_2['nom'][$i]);
                    array_push($dn3_3['prenom'], $dn3_2['prenom'][$i]);
                    array_push($dn3_3['pole'], $dn3_2['pole'][$i]);
                    array_push($dn3_3['bo'], $dn3_2['bo'][$i]);
                }    
            }
            for($i = 0; $i < count($dn2['nni']); $i++){
                if(!in_array($dn2['nni'][$i], $dn1['nni'])){
                    array_push($dn2_2['nni'], $dn2['nni'][$i]);
                    array_push($dn2_2['nom'], $dn2['nom'][$i]);
                    array_push($dn2_2['prenom'], $dn2['prenom'][$i]);
                    array_push($dn2_2['pole'], $dn2['pole'][$i]);
                    array_push($dn2_2['bo'], $dn2['bo'][$i]);
                }    
            }
        }
        else{
            $agent_nni = $agents_nni[0];
            $uos_txt = "";
            $uo_length = count($uos);
            for($i = 0; $i < $uo_length; $i++){
                $uos_txt .= "u.code = '".$uos[$i]."' or ";       
            }
            $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

            $data_niveau_1 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni = '$agent_nni'
                            AND UoAg.niveau = 1
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            $data_niveau_2 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni = '$agent_nni'
                            AND UoAg.niveau = 2
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            $data_niveau_3 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni = '$agent_nni'
                            AND UoAg.niveau = 3
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            $data_niveau_4 = $this->getEntityManager()
                            ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                            FROM AppBundle:UoAgent UoAg
                            INNER JOIN AppBundle:UO u with UoAg.uo = u
                            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                            INNER JOIN AppBundle:Bo b WITH e.bo = b
                            WHERE ($uos_txt)
                            AND ag.nni = '$agent_nni'
                            AND UoAg.niveau = 4
                            ORDER BY b.pole, b.nom, ag.nom ASC")
                            ->getResult();
            
            $dn1 = [];
            $dn1['nni'] = [];
            $dn1['nom'] = [];
            $dn1['prenom'] = [];
            $dn1['pole'] = [];
            $dn1['bo'] = [];

            $dn2 = [];
            $dn2['nni'] = [];
            $dn2['nom'] = [];
            $dn2['prenom'] = [];
            $dn2['pole'] = [];
            $dn2['bo'] = [];

            $dn3 = [];
            $dn3['nni'] = [];
            $dn3['nom'] = [];
            $dn3['prenom'] = [];
            $dn3['pole'] = [];
            $dn3['bo'] = [];

            $dn4 = [];
            $dn4['nni'] = [];
            $dn4['nom'] = [];
            $dn4['prenom'] = [];
            $dn4['pole'] = [];
            $dn4['bo'] = [];

            foreach($data_niveau_1 as $row){
                array_push($dn1['nni'], $row['nni']);
                array_push($dn1['nom'], $row['nom']);
                array_push($dn1['prenom'], $row['prenom']);
                array_push($dn1['pole'], $row['pole']);
                array_push($dn1['bo'], $row['bo']);
            }
            foreach($data_niveau_2 as $row){
                array_push($dn2['nni'], $row['nni']);
                array_push($dn2['nom'], $row['nom']);
                array_push($dn2['prenom'], $row['prenom']);
                array_push($dn2['pole'], $row['pole']);
                array_push($dn2['bo'], $row['bo']);
            }
            foreach($data_niveau_3 as $row){
                array_push($dn3['nni'], $row['nni']);
                array_push($dn3['nom'], $row['nom']);
                array_push($dn3['prenom'], $row['prenom']);
                array_push($dn3['pole'], $row['pole']);
                array_push($dn3['bo'], $row['bo']);
            }
            foreach($data_niveau_4 as $row){
                array_push($dn4['nni'], $row['nni']);
                array_push($dn4['nom'], $row['nom']);
                array_push($dn4['prenom'], $row['prenom']);
                array_push($dn4['pole'], $row['pole']);
                array_push($dn4['bo'], $row['bo']);
            }
            $dn3_2 = [];
            $dn3_2['nni'] = [];
            $dn3_2['nom'] = [];
            $dn3_2['prenom'] = [];
            $dn3_2['pole'] = [];
            $dn3_2['bo'] = [];
            $dn3_3 = [];
            $dn3_3['nni'] = [];
            $dn3_3['nom'] = [];
            $dn3_3['prenom'] = [];
            $dn3_3['pole'] = [];
            $dn3_3['bo'] = [];
            $dn2_2 = [];
            $dn2_2['nni'] = [];
            $dn2_2['nom'] = [];
            $dn2_2['prenom'] = [];
            $dn2_2['pole'] = [];
            $dn2_2['bo'] = [];
            for($i = 0; $i < count($dn3['nni']); $i++){
                if(!in_array($dn3['nni'][$i], $dn2['nni'])){
                    array_push($dn3_2['nni'], $dn3['nni'][$i]);
                    array_push($dn3_2['nom'], $dn3['nom'][$i]);
                    array_push($dn3_2['prenom'], $dn3['prenom'][$i]);
                    array_push($dn3_2['pole'], $dn3['pole'][$i]);
                    array_push($dn3_2['bo'], $dn3['bo'][$i]);
                }    
            }
            for($i = 0; $i < count($dn3_2['nni']); $i++){
                if(!in_array($dn3_2['nni'][$i], $dn1['nni'])){
                    array_push($dn3_3['nni'], $dn3_2['nni'][$i]);
                    array_push($dn3_3['nom'], $dn3_2['nom'][$i]);
                    array_push($dn3_3['prenom'], $dn3_2['prenom'][$i]);
                    array_push($dn3_3['pole'], $dn3_2['pole'][$i]);
                    array_push($dn3_3['bo'], $dn3_2['bo'][$i]);
                }    
            }
            for($i = 0; $i < count($dn2['nni']); $i++){
                if(!in_array($dn2['nni'][$i], $dn1['nni'])){
                    array_push($dn2_2['nni'], $dn2['nni'][$i]);
                    array_push($dn2_2['nom'], $dn2['nom'][$i]);
                    array_push($dn2_2['prenom'], $dn2['prenom'][$i]);
                    array_push($dn2_2['pole'], $dn2['pole'][$i]);
                    array_push($dn2_2['bo'], $dn2['bo'][$i]);
                }    
            }
        }
        if($niveau == 1){
            return $dn1;
        }
        elseif($niveau == 2){
            return $dn2_2;
        }
        elseif($niveau == 3){
            return $dn3_3;
        }
        elseif($niveau == 4){
            return $dn4;
        }
    }
    public function getAgentsByHabNoAgent($pole, $bo, $profil, $astreinte, $uos, $niveau){
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

        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        $data_niveau_1 = $this->getEntityManager()
                        ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                        FROM AppBundle:UoAgent UoAg
                        INNER JOIN AppBundle:UO u with UoAg.uo = u
                        INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                        INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                        INNER JOIN AppBundle:Bo b WITH e.bo = b
                        WHERE ($uos_txt)
                        AND $col_pole $pole
                        AND $col_bo $bo
                        AND $col_profil $profil
                        AND $col_astreinte $astreinte
                        AND UoAg.niveau = 1
                        ORDER BY b.pole, b.nom, ag.nom ASC")
                        ->getResult();
        $data_niveau_2 = $this->getEntityManager()
                        ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                        FROM AppBundle:UoAgent UoAg
                        INNER JOIN AppBundle:UO u with UoAg.uo = u
                        INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                        INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                        INNER JOIN AppBundle:Bo b WITH e.bo = b
                        WHERE ($uos_txt)
                        AND $col_pole $pole
                        AND $col_bo $bo
                        AND $col_profil $profil
                        AND $col_astreinte $astreinte
                        AND UoAg.niveau = 2
                        ORDER BY b.pole, b.nom, ag.nom ASC")
                        ->getResult();
        $data_niveau_3 = $this->getEntityManager()
                        ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                        FROM AppBundle:UoAgent UoAg
                        INNER JOIN AppBundle:UO u with UoAg.uo = u
                        INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                        INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                        INNER JOIN AppBundle:Bo b WITH e.bo = b
                        WHERE ($uos_txt)
                        AND $col_pole $pole
                        AND $col_bo $bo
                        AND $col_profil $profil
                        AND $col_astreinte $astreinte
                        AND UoAg.niveau = 3
                        ORDER BY b.pole, b.nom, ag.nom ASC")
                        ->getResult();
        $data_niveau_4 = $this->getEntityManager()
                        ->createQuery("SELECT DISTINCT(ag.nni) as nni, ag.id, ag.nom as nom, ag.prenom as prenom, b.pole as pole, b.nom as bo
                        FROM AppBundle:UoAgent UoAg
                        INNER JOIN AppBundle:UO u with UoAg.uo = u
                        INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                        INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                        INNER JOIN AppBundle:Bo b WITH e.bo = b
                        WHERE ($uos_txt)
                        AND $col_pole $pole
                        AND $col_bo $bo
                        AND $col_profil $profil
                        AND $col_astreinte $astreinte
                        AND UoAg.niveau = 4
                        ORDER BY b.pole, b.nom, ag.nom ASC")
                        ->getResult();
        
        $dn1 = [];
        $dn1['nni'] = [];
        $dn1['nom'] = [];
        $dn1['prenom'] = [];
        $dn1['pole'] = [];
        $dn1['bo'] = [];

        $dn2 = [];
        $dn2['nni'] = [];
        $dn2['nom'] = [];
        $dn2['prenom'] = [];
        $dn2['pole'] = [];
        $dn2['bo'] = [];

        $dn3 = [];
        $dn3['nni'] = [];
        $dn3['nom'] = [];
        $dn3['prenom'] = [];
        $dn3['pole'] = [];
        $dn3['bo'] = [];

        $dn4 = [];
        $dn4['nni'] = [];
        $dn4['nom'] = [];
        $dn4['prenom'] = [];
        $dn4['pole'] = [];
        $dn4['bo'] = [];

        foreach($data_niveau_1 as $row){
            array_push($dn1['nni'], $row['nni']);
            array_push($dn1['nom'], $row['nom']);
            array_push($dn1['prenom'], $row['prenom']);
            array_push($dn1['pole'], $row['pole']);
            array_push($dn1['bo'], $row['bo']);
        }
        foreach($data_niveau_2 as $row){
            array_push($dn2['nni'], $row['nni']);
            array_push($dn2['nom'], $row['nom']);
            array_push($dn2['prenom'], $row['prenom']);
            array_push($dn2['pole'], $row['pole']);
            array_push($dn2['bo'], $row['bo']);
        }
        foreach($data_niveau_3 as $row){
            array_push($dn3['nni'], $row['nni']);
            array_push($dn3['nom'], $row['nom']);
            array_push($dn3['prenom'], $row['prenom']);
            array_push($dn3['pole'], $row['pole']);
            array_push($dn3['bo'], $row['bo']);
        }
        foreach($data_niveau_4 as $row){
            array_push($dn4['nni'], $row['nni']);
            array_push($dn4['nom'], $row['nom']);
            array_push($dn4['prenom'], $row['prenom']);
            array_push($dn4['pole'], $row['pole']);
            array_push($dn4['bo'], $row['bo']);
        }
        $dn3_2 = [];
        $dn3_2['nni'] = [];
        $dn3_2['nom'] = [];
        $dn3_2['prenom'] = [];
        $dn3_2['pole'] = [];
        $dn3_2['bo'] = [];
        $dn3_3 = [];
        $dn3_3['nni'] = [];
        $dn3_3['nom'] = [];
        $dn3_3['prenom'] = [];
        $dn3_3['pole'] = [];
        $dn3_3['bo'] = [];
        $dn2_2 = [];
        $dn2_2['nni'] = [];
        $dn2_2['nom'] = [];
        $dn2_2['prenom'] = [];
        $dn2_2['pole'] = [];
        $dn2_2['bo'] = [];
        for($i = 0; $i < count($dn3['nni']); $i++){
            if(!in_array($dn3['nni'][$i], $dn2['nni'])){
                array_push($dn3_2['nni'], $dn3['nni'][$i]);
                array_push($dn3_2['nom'], $dn3['nom'][$i]);
                array_push($dn3_2['prenom'], $dn3['prenom'][$i]);
                array_push($dn3_2['pole'], $dn3['pole'][$i]);
                array_push($dn3_2['bo'], $dn3['bo'][$i]);
            }    
        }
        for($i = 0; $i < count($dn3_2['nni']); $i++){
            if(!in_array($dn3_2['nni'][$i], $dn1['nni'])){
                array_push($dn3_3['nni'], $dn3_2['nni'][$i]);
                array_push($dn3_3['nom'], $dn3_2['nom'][$i]);
                array_push($dn3_3['prenom'], $dn3_2['prenom'][$i]);
                array_push($dn3_3['pole'], $dn3_2['pole'][$i]);
                array_push($dn3_3['bo'], $dn3_2['bo'][$i]);
            }    
        }
        for($i = 0; $i < count($dn2['nni']); $i++){
            if(!in_array($dn2['nni'][$i], $dn1['nni'])){
                array_push($dn2_2['nni'], $dn2['nni'][$i]);
                array_push($dn2_2['nom'], $dn2['nom'][$i]);
                array_push($dn2_2['prenom'], $dn2['prenom'][$i]);
                array_push($dn2_2['pole'], $dn2['pole'][$i]);
                array_push($dn2_2['bo'], $dn2['bo'][$i]);
            }    
        }

        if($niveau == 1){
            return $dn1;
        }
        elseif($niveau == 2){
            return $dn2_2;
        }
        elseif($niveau == 3){
            return $dn3_3;
        }
        elseif($niveau == 4){
            return $dn4;
        }
    }

    public function getResultNbAgentsByHab_nivAgents($agents_nni, $uos){
        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            return $this->getEntityManager()
                ->createQuery("SELECT COUNT(ag.id) as nb_doublons, ag.id, MIN(UoAg.niveau) as niveau_min
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                INNER JOIN AppBundle:Bo b WITH e.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND ($uos_txt)
                GROUP BY ag.id
                HAVING COUNT(ag.id) >= 1
                ORDER BY niveau_min ASC")
                ->getResult();
        }
        else{
            $agent_nni = $agents_nni[0];
            return $this->getEntityManager()
                ->createQuery("SELECT COUNT(ag.id) as nb_doublons, ag.id, MIN(UoAg.niveau) as niveau_min
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                INNER JOIN AppBundle:Bo b WITH e.bo = b
                WHERE ag.nni = '$agent_nni'
                AND ($uos_txt)
                GROUP BY ag.id
                HAVING COUNT(ag.id) >= 1
                ORDER BY niveau_min ASC")
                ->getResult();
        }
    }
    public function getResultNbAgentsByHabs($pole, $bo, $profil, $astreinte, $uos){
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
        
        $data = [];
        for($z = 0; $z < count($uos); $z++){
            $uos_txt = "";
            $uo_length = count($uos[$z]);
            for($i = 0; $i < $uo_length; $i++){
                $uos_txt .= "u.code = '".$uos[$z][$i]."' or ";       
            }
            $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

            $data[$i] = [];
            $data[$i] =  $this->getEntityManager()
                ->createQuery("SELECT COUNT(ag.id) as nb_doublons, ag.id, MIN(UoAg.niveau) as niveau_min
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                INNER JOIN AppBundle:Bo b WITH e.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                AND ($uos_txt)
                GROUP BY ag.id
                HAVING COUNT(ag.id) >= 1
                ORDER BY niveau_min ASC")
                ->getResult();
        }

        return $data;
    }
    public function getResultNbAgentsByHabEtoile_niv($pole, $bo, $profil, $astreinte, $uos){
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

        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        return $this->getEntityManager()
            ->createQuery("SELECT ag.id as agent_id, ag.nni as nni, ag.nom as nom, ag.prenom as prenom, UoAg.niveau as niveau, u.code as uo_id, b.nom as bo, b.pole as pole
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND ($uos_txt)
            ORDER BY b.pole, b.nom, ag.nom ASC")
            ->getResult();
    }
    public function getResultNbAgentsByHabEtoile_niv_Agents($agents_nni, $uos){
        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);

            return $this->getEntityManager()
                ->createQuery("SELECT ag.id as agent_id, ag.nni as nni, ag.nom as nom, ag.prenom as prenom, UoAg.niveau as niveau, u.code as uo_id, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                INNER JOIN AppBundle:Bo b WITH e.bo = b
                WHERE ag.nni IN ($ags_nni)
                AND ($uos_txt)
                ORDER BY b.pole, b.nom, ag.nom ASC")
                ->getResult();
        }
        else{
            $agent_nni = $agents_nni[0];
            return $this->getEntityManager()
                ->createQuery("SELECT ag.id as agent_id, ag.nni as nni, ag.nom as nom, ag.prenom as prenom, UoAg.niveau as niveau, u.code as uo_id, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                INNER JOIN AppBundle:Bo b WITH e.bo = b
                WHERE ag.nni = '$agent_nni'
                AND ($uos_txt)
                ORDER BY b.pole, b.nom, ag.nom ASC")
                ->getResult();
        }
    }
    public function getResultNbAgentsByAllHabsEtoile_niv($pole, $bo, $profil, $astreinte, $uos){
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

        foreach($uos as $cle => $uo){
            $uos_txt = "";
            $uo_length = count($uo);
            for($i = 0; $i < $uo_length; $i++){
                $uos_txt .= "u.code = '".$uo[$i]."' or ";       
            }
            $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

            $data[$cle] = $this->getEntityManager()
                ->createQuery("SELECT ag.id as agent_id, ag.nni as nni, ag.nom as nom, ag.prenom as prenom, UoAg.niveau as niveau, u.code as uo_id, b.nom as bo, b.pole as pole
                FROM AppBundle:UoAgent UoAg
                INNER JOIN AppBundle:UO u with UoAg.uo = u
                INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
                INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
                INNER JOIN AppBundle:Bo b WITH e.bo = b
                WHERE $col_pole $pole
                AND $col_bo $bo
                AND $col_profil $profil
                AND $col_astreinte $astreinte
                AND ($uos_txt)
                ORDER BY b.pole, b.nom, ag.nom ASC")
                ->getResult();
            $data[$uo] = $data[$cle];
        }

        return $data;
    }
    public function getResultNbAgentsByHabPST_niv($pole, $bo, $profil, $astreinte, $uos){
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

        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        return $this->getEntityManager()
            ->createQuery("SELECT COUNT(DISTINCT(agent_id)) FROM
            (SELECT ag.id as agent_id, u.code as code, UoAg.niveau as niveau
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:UO u with UoAg.uo = u
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE $col_pole $pole
            AND $col_bo $bo
            AND $col_profil $profil
            AND $col_astreinte $astreinte
            AND ($uos_txt)
            AND niveau = 4
            ORDER BY agent_id, code ASC) as req")
            ->getResult();
    }
    public function getCountPST($pole, $bo, $profil, $astreinte, $uos){
        if($pole == ""){
            $pole = 1;
            $col_pole = 1;
        }
        else{
            $col_pole = "b.pole";
        }
        if($bo == ""){
            $bo = 1;
            $col_bo = 1;
        }
        else{
            $col_bo = "b.nom";
        }
        if($profil == ""){
            $profil = 1;
            $col_profil = 1;
        }
        else{
            $col_profil = "ag.profil";
        }
        if($astreinte == ""){
            $astreinte = 1;
            $col_astreinte = 1;
        }
        else{
            $col_astreinte = "ag.abstreinte";
        }
        
        $ags_id = [];

        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "u.code = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);

        return $this->getEntityManager()
        ->createQuery("SELECT COUNT(DISTINCT(agent_id))
            FROM AppBundle:UoAgent UoAg
            INNER JOIN AppBundle:Agent ag with UoAg.agent = ag
            INNER JOIN AppBundle:Equipe e WITH ag.equipe = e
            INNER JOIN AppBundle:Bo b WITH e.bo = b
            WHERE $col_pole = '$pole'
            AND $col_bo = '$bo'
            AND $col_profil = '$profil'
            AND $col_astreinte = '$astreinte'
            AND niveau = 4
            ORDER BY agent_id, code ASC) as req")
            ->getResult();
    }

    

}
