<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agence;
use AppBundle\Entity\Agent;
use AppBundle\Entity\Bo;
use AppBundle\Entity\Domaine;
use AppBundle\Entity\Dr;
use AppBundle\Entity\Equipe;
use AppBundle\Entity\SousDomaine;
use AppBundle\Entity\Uo;
use AppBundle\Entity\UoAgent;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;

class DefaultController extends Controller
{
    /**
     * @Route("", name="homepage")
     */
    public function indexAction(Request $request)
    {	
        return $this->render('others/main.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/mail_old", name="mail_old")
     */
	 function mail_oldAction(){
		$mailer = $this->get('mailer');
		$message = \Swift_Message::newInstance();
		$message->setFrom('remitafforeau@yahoo.fr')
			->setTo('remitafforeau@yahoo.fr')
			->setSubject('Le objet')
			->setBody(
				$this->renderView(
					// app/Resources/views/Emails/registration.html.twig
					'Emails/registration.html.twig',
					['name' => 'remi']
				),
				'text/html'
			)
			/*
			 * If you also want to include a plaintext version of the message
			->addPart(
				$this->renderView(
					'Emails/registration.txt.twig',
					['name' => $name]
				),
				'text/plain'
			)
			*/
		;

		$mailer->send($message);
		
		return new JsonResponse(0, 200);
	}
	/**
     * @Route("/mail", name="mail")
     */
	 function mailAction(){
		$mess = $this->get('mailer');
		$m = $this->sendEmail('remi', $mess);
		
		//$m = $this->xxmail("remitafforeau@yahoo.fr", "sujet test", "body test", "Content-type: text/plain \r\n charset=utf-8");
		
		return new JsonResponse($m, 200);
	}
	function xxmail($to, $subject, $body, $headers){
		$smtp = stream_socket_client('tcp://smtp.mail.yahoo.fr:25', $eno, $estr, 30);

		$B = 8192;
		$c = "\r\n";
		$s = 'remitafforeau@yahoo.fr';

		fwrite($smtp, 'helo ' . 'Dolly' . $c);
		  $junk = fgets($smtp, $B);

		// Envelope
		fwrite($smtp, 'mail from: ' . $s . $c);
		  $junk = fgets($smtp, $B);
		fwrite($smtp, 'rcpt to: ' . $to . $c);
		  $junk = fgets($smtp, $B);
		fwrite($smtp, 'data' . $c);
		  $junk = fgets($smtp, $B);

		// Header 
		fwrite($smtp, 'To: ' . $to . $c); 
		if(strlen($subject)) fwrite($smtp, 'Subject: ' . $subject . $c); 
		if(strlen($headers)) fwrite($smtp, $headers); // Must be \r\n (delimited)
		fwrite($smtp, $headers . $c); 

		// Body
		if(strlen($body)) fwrite($smtp, $body . $c);
		fwrite($smtp, $c . '.' . $c);
		  $junk = fgets($smtp, $B);

		// Close
		fwrite($smtp, 'quit' . $c);
		  $junk = fgets($smtp, $B);
		fclose($smtp);
		
		return 0;
	}
	
	
	
	public function sendEmail($name, \Swift_Mailer $mailer){
		$message = (new \Swift_Message())
			->setFrom('remitafforeau@yahoo.fr')
			->setTo('remitafforeau@yahoo.fr')
			->setBody('Ta trace de fils de putes pauvre enculé de merde !')
		;

		$mailer->send($message);

		return $mailer;
	}
	function envoiMail(){
		$mailer = $this->get('mailer');
		$message = \Swift_Message::newInstance();
		$message->setFrom('remitafforeau@yahoo.fr')
			->setTo('remitafforeau@yahoo.fr')
			->setSubject('Le objet')
			->setBody(
				$this->renderView(
					// app/Resources/views/Emails/registration.html.twig
					'Emails/registration.html.twig',
					['name' => 'remi']
				),
				'text/html'
			)
			/*
			 * If you also want to include a plaintext version of the message
			->addPart(
				$this->renderView(
					'Emails/registration.txt.twig',
					['name' => $name]
				),
				'text/plain'
			)
			*/
		;

		$mailer->send($message);
		
		return 0;
	}
	/**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        return $this->render('others/contact.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/competences", name="competences")
     */
    public function competencesAction(Request $request)
    {
        return $this->render('others/competences.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/cv", name="cv")
     */
    public function cvAction(Request $request)
    {
        return $this->render('others/cv.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/parcours", name="parcours")
     */
    public function parcoursAction(Request $request)
    {
        return $this->render('others/parcours.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/macro", name="macro")
     */
    public function macroAction(Request $request)
    {
        return $this->render('others/macro.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/robot", name="robot")
     */
    public function robotAction(Request $request)
    {
        return $this->render('others/robot.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
	/**
     * @Route("/sites", name="sites")
     */
    public function sitesAction(Request $request)
    {
		$RAW_QUERY = "SELECT * FROM identite";
        $em = $this->get('doctrine.orm.entity_manager');
		$em = $this->getDoctrine()->getManager();
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $data = $statement->fetchAll();
		
		$em = $this->get("doctrine.orm.entity_manager");

        $domaines = $em->getRepository('AppBundle:Domaine')->getAllDomaine();
        $domaines_soudomaines = $em->getRepository('AppBundle:SousDomaine')->getAllDomaineSousDomaine();

        $rslt = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent("", "", "", "", "");
        $rslt_dom = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine("", "", "", "");

        $bos = $em->getRepository('AppBundle:Bo')->getAllBo();
        $poles = $em->getRepository('AppBundle:Bo')->getAllPole();
        $poles_bos = $em->getRepository('AppBundle:Bo')->findPolesBos();

        $agents_id = $em->getRepository('AppBundle:Agent')->getAgentsId();  

        $titre = " Cartographie des compétences en AIE";

        return $this->render('others/sites.html.twig', array('data' => $data,
        'titre' => $titre, 'domaines' => $domaines,  'domaines_soudomaines' => $domaines_soudomaines, 'rslt' => $rslt, 'rslt_dom' => $rslt_dom,
        'poles_bos' => $poles_bos, 'bos' => $bos, 'poles' => $poles, 'agents_id' => $agents_id));
    }
	/**
     * @Route("/cartographieCompetenceAgent", name="cartographieCompetenceAgent")
     */
    public function cartographieCompetenceAgent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $poles = $em->getRepository('AppBundle:Bo')->getAllPole();

        $bos = $em->getRepository('AppBundle:Bo')->findAll();
        $poles_bos = $em->getRepository('AppBundle:Bo')->findPolesBos();

        $agents = $em->getRepository('AppBundle:Agent')->findBy(array(), array('nom' => 'ASC'));
        //$agents = $em->getRepository('AppBundle:Agent')->getAllAboutAgents();

        $agents_in_ecart = $em->getRepository('AppBundle:UoAgent')->getResultAgentInEcart();

        $titre = " Cartographie des compétences : Base de données des agents";

        return $this->render('AppBundle:Default:cartographieCompetenceAgent.html.twig', array(
            'poles_bos'=>$poles_bos,
            'poles'=>$poles,
            'bos'=>$bos,
            'agents'=>$agents,
            'agents_in_ecart'=> $agents_in_ecart,
            'titre' => $titre
        ));
    }
	/**
     * @Route("/lst_bo", name="lst_bo")
     */
    public function lst_bo(Request $request)
    {
         $em = $this->get('doctrine.orm.entity_manager');
         $poles = $em->getRepository('AppBundle:Bo')->getAllPole();

        $bos = $em->getRepository('AppBundle:Bo')->findAll();

        $agents = $em->getRepository('AppBundle:Agent')->findAll();

        $titre = " GPEC-AIE : Liste des B.O. par pôle";

        return $this->render('AppBundle:Default:lst_bo.html.twig', array(
            'poles'=>$poles,
            'bos'=>$bos,
            'agents'=>$agents,
            'titre' => $titre
        ));
    }
	/**
     * @Route("/carto_json", name="carto_json")
     */
    public function carto_json(Request $request)
    {
        $id = $request->get('id'); 
        $val = $request->get('val'); 
        $agent_id = $request->get("agent_id");
        $col_type = $request->get('col_type');
        
        $em = $this->get('doctrine.orm.entity_manager');
        $agent = $em->getRepository('AppBundle:Agent')->find($agent_id);

        if($agent){    
            if($col_type == "profil"){
                $agent->setProfil($val);
            }
            else if($col_type == "abstreinte"){
                $agent->setAbstreinte($val);
            }
            else if($col_type == "retraite"){
                $agent->setRetraite($val);
            }
            else if($col_type == "mobilite"){
                $agent->setMobilite($val);
            }
            else if($col_type == "parti"){
                $agent->setParti($val);
            }
            $em->persist($agent);

            $em->flush();
        }

        return new Response($agent_id, 200);
    }
	/**
     * @Route("/update_tab_json", name="update_tab_json")
     */
    public function update_tab_json(Request $request)
    {
        $sd_id = $request->get('sd_id');
        $agent_id = $request->get('agent_id'); 
        $new_val = $request->get('new_val');
        $new_val = floatval($new_val); 
        $col = $request->get('col');
        
        $em = $this->get('doctrine.orm.entity_manager');
        $agent = $em->getRepository('AppBundle:Agent')->findById($agent_id);

        $sd = $em->getRepository('AppBundle:SousDomaine')->findById($sd_id);

        $uos = $em->getRepository('AppBundle:Uo')->findBy(array('sousdomaine' => $sd));
        
        for($i = 0; $i < count($uos); $i++){
            $uoAgent = $em->getRepository('AppBundle:UoAgent')->findOneBy(array('agent' => $agent, 'uo' => $uos[$i]));
            
            if($col == 'nPlusUn'){
                //print_r($new_val);
                //$a = 0;
                $uoAgent->setNPlusUn($new_val);
            }
            elseif($col == 'nPlusDeux'){
                $uoAgent->setNPlusDeux($new_val);
            }
            elseif($col == 'nPlusTrois'){
                $uoAgent->setNPlusTrois($new_val);
            }

            $em->persist($uoAgent);

            $em->flush();
        }

        return new Response($new_val, 200);
    }
	/**
     * @Route("/filtre_tab_json", name="filtre_tab_json")
     */
    public function filtre_tab_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');
        $agents = [];
        $agents =  $request->get('agents');

        $data = [];

        if(count($agents) == 0){
            $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, $bo, $profil, $astreinte, '');
        }
        else{
            if($agents[0] == ""){
                $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, $bo, $profil, $astreinte, '');
            }
            else{
                $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersAgent($agents, '');
            }
        }

        $data_maille_supp = [];
        if(count($agents) == 0){
            if(!empty($pole[0]) && !empty($bo[0])){
                $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent('', $bo, $profil, $astreinte, '');
            }
            else if(!empty($bo[0])){
                $pole_tab = $em->getRepository('AppBundle:Bo')->getPoleByBo($bo);
                $pole = $pole_tab[0]['pole'];
                $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, '', $profil, $astreinte, '');
            }
        }
        else{
            if(empty($agents[0])){
                if(!empty($pole[0]) && !empty($bo[0])){
                    $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent('', $bo, $profil, $astreinte, '');
                }
                if(!empty($bo[0])){
                    $pole_tab = $em->getRepository('AppBundle:Bo')->getPoleByBo($bo);
                    $pole = $pole_tab[0]['pole'];
                    $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, '', $profil, $astreinte, '');
                }
            }
            else{

            }
        }
        $d = [];
        
        array_push($d, $data);
        array_push($d, $data_maille_supp);

        return new JsonResponse($d);
    }
    /**
     * @Route("/filtre_tab_qualif_json", name="filtre_tab_qualif_json")
     */
    public function filtre_tab_qualif_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');

        $agents = [];
        $agents =  $request->get('agents');
        
        if(count($agents) > 0){
            if($agents[0] == ""){
                $data = $em->getRepository('AppBundle:UoAgent')->getResultGeoUO($pole, $bo, $profil, $astreinte);
            }
            else{
                $data = $em->getRepository('AppBundle:UoAgent')->getResultGeoUOAgents($agents);
            }
        }
        else{
            $data = $em->getRepository('AppBundle:UoAgent')->getResultGeoUO($pole, $bo, $profil, $astreinte);
        }

        return new JsonResponse($data);
    }
    /**
     * @Route("/filtre_graph_json", name="filtre_graph_json")
     */
    public function filtre_graph_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');

        $agents = [];
        $agents =  $request->get('agents');

        if(count($agents) == 0){
            $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine($pole, $bo, $profil, $astreinte);
        }
        else{
            if($agents[0] == ""){
                $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine($pole, $bo, $profil, $astreinte);
            }
            else{
                $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaineAgents($agents);
            }
        }

        $data_maille_supp = [];
        if(count($agents) == 0){
            if(!empty($pole[0]) && !empty($bo[0])){
                $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine('', $bo, $profil, $astreinte, '');
            }
            else if(!empty($bo[0])){
                $pole_tab = $em->getRepository('AppBundle:Bo')->getPoleByBo($bo);
                $pole = $pole_tab[0]['pole'];
                $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine($pole, '', $profil, $astreinte, '');
            }
        }
        else{
            if(empty($agents[0])){
                if(!empty($pole[0]) && !empty($bo[0])){
                    $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine('', $bo, $profil, $astreinte, '');
                }
                else if(!empty($bo[0])){
                    $pole_tab = $em->getRepository('AppBundle:Bo')->getPoleByBo($bo);
                    $pole = $pole_tab[0]['pole'];
                    $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine($pole, '', $profil, $astreinte, '');
                }
            }
            else{

            }
        }

        $d = [];
        
        array_push($d, $data);
        array_push($d, $data_maille_supp);

        return new JsonResponse($d);
    }
	/**
     * @Route("/filtre_grah_dom_no_agent_json", name="filtre_grah_dom_no_agent_json")
     */
    public function filtre_grah_dom_no_agent_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');
        $domaine =  $request->get('domaine');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, $bo, $profil, $astreinte, $domaine);

        $data_maille_supp = [];
        if($pole != '' && $bo == ''){
            $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent('', $bo, $profil, $astreinte, $domaine);
        }
        else if($bo != ''){
            $pol = $em->getRepository('AppBundle:Bo')->getPoleByBo($bo);
            $pole = $pol[0]['pole'];
            $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, '', $profil, $astreinte, $domaine);
        }

        $d = [];

        if(count($data_maille_supp) == 0){
            for($i = 0; $i < count($data); $i++){
                $data_maille_supp[$i]['sousdomaine'] = $data[$i]['sousdomaine'];
            }
        }
        
        array_push($d, $data);
        array_push($d, $data_maille_supp);

        return new JsonResponse($d);
    }
	/**
     * @Route("/filtre_grah_dom_agent_json", name="filtre_grah_dom_agent_json")
     */
    public function filtre_grah_dom_agent_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $pole = $request->get('pole');
        $bo = $request->get('bo');

        $agents = $request->get('agents');
        $domaine =  $request->get('domaine');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersAgent($agents, $domaine);

        $data_maille_supp = [];
        if($pole != '' && $bo == ''){
            $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent('', $bo, $profil, $astreinte, $domaine);
        }
        else if($bo != ''){
            $pol = $em->getRepository('AppBundle:Bo')->getPoleByBo($bo);
            $pole = $pol[0]['pole'];
            $data_maille_supp = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent($pole, '', $profil, $astreinte, $domaine);
        }

        $d = [];

        if(count($data_maille_supp) == 0){
            for($i = 0; $i < count($data); $i++){
                $data_maille_supp[$i]['sousdomaine'] = $data[$i]['sousdomaine'];
            }
        }
        
        array_push($d, $data);
        array_push($d, $data_maille_supp);

        return new JsonResponse($d);
    }
	/**
     * @Route("/popup_tab_json_no_agent", name="popup_tab_json_no_agent")
     */
    public function popup_tab_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');

        $sd_id =  $request->get('sd_id');

        $data = $em->getRepository('AppBundle:UoAgent')->getResultAgentsByFiltersNoAgents($pole, $bo, $profil, $astreinte, $sd_id);


        return new JsonResponse($data);
    }
	/**
     * @Route("/popup_tab_json_agent", name="popup_tab_json_agent")
     */
    public function popup_tab_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $sd_id =  $request->get('sd_id');

        $agents = [];
        $agents =  $request->get('agents');

        $data = $em->getRepository('AppBundle:UoAgent')->getResultAgentsByFiltersAgents($agents, $sd_id);


        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_astreinte_no_agent_json", name="agents_by_astreinte_no_agent_json")
     */
    public function agents_by_astreinte_no_agent_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');
        
        $data = $em->getRepository('AppBundle:Agent')->getAgentsAstreinteNoAgent($pole, $bo, $profil, $astreinte);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_astreinte_agent_json", name="agents_by_astreinte_agent_json")
     */
    public function agents_by_astreinte_agent_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents = $request->get('agents');
        $astreinte = $request->get('astreinte');
        
        $data = $em->getRepository('AppBundle:Agent')->getAgentsAstreinteAgent($agents, $astreinte);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_pst_json_no_agent", name="agents_by_pst_json_no_agent")
     */
    public function agents_by_pst_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');
        $pst_or_not =  $request->get('pst');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsPSTNoAgent($pole, $bo, $profil, $astreinte, $pst_or_not);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_pst_json_agent", name="agents_by_pst_json_agent")
     */
    public function agents_by_pst_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $agents = [];
        $agents = $request->get('agents');
        $pst_or_not = $request->get('pst');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsPSTAgent($agents, $pst_or_not);

        return new JsonResponse($data);
    }
	/**
     * @Route("/calcul_de_bonhommes", name="calcul_de_bonhommes")
     */
    public function calcul_de_bonhommes(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $bo = $request->get('bo'); 
        
        $data = $em->getRepository('AppBundle:UoAgent')->getResultatBonhommes($bo);

        return new JsonResponse($data);
    }
	/**
     * @Route("/getResultatTrait", name="getResultatTrait")
     */
    public function getResultatTrait(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $data = $em->getRepository('AppBundle:UoAgent')->getResultatsTrait();

        return new JsonResponse($data);
    }
	/**
     * @Route("/calcul_de_bonhommes_agents", name="calcul_de_bonhommes_agents")
     */
    public function calcul_de_bonhommes_agents(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $bo = $request->get('bo'); 
        
        $data = $em->getRepository('AppBundle:UoAgent')->getResultatBonhommes_Agents($bo);

        return new JsonResponse($data);
    }
	/**
     * @Route("/getResultatTrait_agents", name="getResultatTrait_agents")
     */
    public function getResultatTrait_agents(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $bo = $request->get('bo'); 

        $data = $em->getRepository('AppBundle:UoAgent')->getResultatsTrait_Agents($bo);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_prv_json_agent", name="agents_by_prv_json_agent")
     */
    public function agents_by_prv_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents = [];
        $agents = $request->get('agents'); 
        $prv_or_not =  $request->get('prv');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsPRVAgent($agents, $prv_or_not);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_prv_json_no_agent", name="agents_by_prv_json_no_agent")
     */
    public function agents_by_prv_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');
        $prv_or_not =  $request->get('prv');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsPRVNoAgent($pole, $bo, $profil, $astreinte, $prv_or_not);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_premtron_non_json_no_agent", name="agents_by_premtron_non_json_no_agent")
     */
    public function agents_by_premtron_non_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsPremTronNonNoAgent($pole, $bo, $profil, $astreinte);

        return new JsonResponse($data);
    }
	/**
     * @Route("/agents_by_premtron_non_json_agent", name="agents_by_premtron_non_json_agent")
     */
    public function agents_by_premtron_non_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents = [];
        $agents = $request->get('agents'); 
        
        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsPremTronNonAgent($agents);

        return new JsonResponse($data);
    }
	/**
     * @Route("/nb_agents_tot_json_no_agent", name="nb_agents_tot_json_no_agent")
     */
    public function nb_agents_tot_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');

        $data = $em->getRepository('AppBundle:Agent')->getNbAgents($pole, $bo, $profil, $astreinte);


        return new JsonResponse($data);
    }
	/**
     * @Route("/nb_agents_tot_json_agent", name="nb_agents_tot_json_agent")
     */
    public function nb_agents_tot_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents =  $request->get('agents');
        $data = $em->getRepository('AppBundle:Agent')->getNbAgentsFiltreAgent($agents);
        

        return new JsonResponse($data);
    }
	/**
     * @Route("/popup_tab_domaine_json_no_agent", name="popup_tab_domaine_json_no_agent")
     */
    public function popup_tab_domaine_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $astreinte = $request->get('astreinte');
        $profil =  $request->get('profil');

        $d_id =  $request->get('d_id');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getResultAgentsByFiltersDomaineNoAgent($pole, $bo, $profil, $astreinte, $d_id);

        return new JsonResponse($data);
    }
	/**
     * @Route("/popup_tab_domaine_json_agent", name="popup_tab_domaine_json_agent")
     */
    public function popup_tab_domaine_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $agents = [];
        $agents =  $request->get('agents');
        $d_id =  $request->get('d_id');
        
        $data = $em->getRepository('AppBundle:UoAgent')->getResultAgentsByFiltersDomaineAgent($agents, $d_id);

        return new JsonResponse($data);
    }
	/**
     * @Route("/sort_by_all_filters", name="sort_by_all_filters")
     */
    public function sort_by_all_filters(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');
        $agents_nni = $request->get('s_agents');

        $uos =  $request->get('uos');

        if(count($agents_nni) > 0){
            if($agents_nni[0] == ""){
                $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHab_niv($pole, $bo, $profil, $astreinte, $uos);
            }
            else{
                $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHab_nivAgents($agents_nni, $uos);
            }
        }
        else{
            $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHab_niv($pole, $bo, $profil, $astreinte, $uos);
        }


        return new JsonResponse($data);
    }
	/**
     * @Route("/nb_agents_by_hab_json_agent", name="nb_agents_by_hab_json_agent")
     */
    public function nb_agents_by_hab_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents_nni = $request->get('agents');
        $uos =  $request->get('uos');

        $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHab_nivAgents($agents_nni, $uos);

        return new JsonResponse($data);
    }
	/**
     * @Route("/nb_agents_by_hab_json_no_agent", name="nb_agents_by_hab_json_no_agent")
     */
    public function nb_agents_by_hab_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');
        $uos =  $request->get('uos');

        $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHab_niv($pole, $bo, $profil, $astreinte, $uos);

        return new JsonResponse($data);
    }
	/**
     * @Route("/det_agents_by_hab_json_no_agent", name="det_agents_by_hab_json_no_agent")
     */
    public function det_agents_by_hab_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');
        $uos =  $request->get('uos');
        $niveau =  $request->get('niveau');

        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsByHabNoAgent($pole, $bo, $profil, $astreinte, $uos, $niveau);
        
        return new JsonResponse($data);
    }
	/**
     * @Route("/det_agents_by_hab_json_agent", name="det_agents_by_hab_json_agent")
     */
    public function det_agents_by_hab_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents = [];
        $agents = $request->get('agents');
        $uos =  $request->get('uos');
        $niveau =  $request->get('niveau');

        $data = $em->getRepository('AppBundle:UoAgent')->getAgentsByHabAgent($agents, $uos, $niveau);
        
        return new JsonResponse($data);
    }
	/**
     * @Route("/nb_agents_by_hab_etoile_json", name="nb_agents_by_hab_etoile_json")
     */
    public function nb_agents_by_hab_etoile_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');
        $agents_nni = $request->get('agents');

        $uos =  $request->get('uos');
        
        if(count($agents_nni) > 0){
            if($agents_nni[0] == ""){
                $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHabEtoile_niv($pole, $bo, $profil, $astreinte, $uos);
            }
            else{
                $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHabEtoile_niv_Agents($agents_nni, $uos);
            }
        }
        else{
            $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHabEtoile_niv($pole, $bo, $profil, $astreinte, $uos);
        }

        return new JsonResponse($data);
    }
	/**
     * @Route("/nb_agents_by_all_habs_etoile_json", name="nb_agents_by_all_habs_etoile_json")
     */
    public function nb_agents_by_all_habs_etoile_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');
        $agents_nni = $request->get('agents');

        $uos = $request->get('uos');
        
        $d = [];
        foreach($uos as $uo){
            if(count($agents_nni) > 0){
                if($agents_nni[0] == ""){
                    $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHabEtoile_niv($pole, $bo, $profil, $astreinte, $uo);
                }
                else{
                    $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHabEtoile_niv_Agents($agents_nni, $uo);
                }
            }
            else{
                $data = $em->getRepository('AppBundle:UoAgent')->getResultNbAgentsByHabEtoile_niv($pole, $bo, $profil, $astreinte, $uo);
            }
            array_push($d, $data);
        }

        return new JsonResponse($d);
    }
	/**
     * @Route("/nb_agents_by_hab_pst_json_no_agent", name="nb_agents_by_hab_pst_json_no_agent")
     */
    public function nb_agents_by_hab_pst_json_no_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');

        $uos =  $request->get('uos');
        
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
            $uos_txt .= "uo_id = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = "SELECT COUNT(DISTINCT(ag_id)) as nb_agents FROM 
        (SELECT Agent.id AS ag_id, UoAgent.uo_id as uo_id, UoAgent.niveau as niveau
        FROM `UoAgent`
        inner join Agent on UoAgent.agent_id = Agent.id
        inner join Equipe on Agent.equipe_id = Equipe.id
        inner join Bo b on Equipe.bo_id = b.id
        WHERE $col_pole $pole
        AND $col_bo $bo
        AND $col_profil $profil
        AND $col_astreinte $astreinte
        AND ($uos_txt)
        and niveau = 4 
        order by ag_id, uo_id) 
        AS req";
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $data = $statement->fetchAll();

        return new JsonResponse($data);
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
	/**
     * @Route("/nb_agents_by_hab_pst_json_agent", name="nb_agents_by_hab_pst_json_agent")
     */
    public function nb_agents_by_hab_pst_json_agent(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $agents_nni = $request->get('agents');
        $uos =  $request->get('uos');
        
        $uos_txt = "";
        $uo_length = count($uos);
        for($i = 0; $i < $uo_length; $i++){
            $uos_txt .= "uo_id = '".$uos[$i]."' or ";       
        }
        $uos_txt = substr($uos_txt, 0, strlen($uos_txt) - 3);
        $em = $this->getDoctrine()->getManager();

        if(count($agents_nni > 1)){
            $ags_nni = "";
            foreach($agents_nni as $a_nni){
                $ags_nni = $ags_nni."'".$a_nni."', ";
            }
            $ags_nni =  substr($ags_nni, 0, strlen($ags_nni) - 2);
            $RAW_QUERY = "SELECT COUNT(DISTINCT(ag_id)) as nb_agents FROM 
            (SELECT Agent.id AS ag_id, UoAgent.uo_id as uo_id, UoAgent.niveau as niveau
            FROM `UoAgent`
            inner join Agent on UoAgent.agent_id = Agent.id
            inner join Equipe on Agent.equipe_id = Equipe.id
            inner join Bo on Equipe.bo_id = Bo.id
            WHERE Agent.nni IN ($ags_nni)
            AND ($uos_txt)
            and niveau = 4 
            order by ag_id, uo_id) 
            AS req";
        }
        else{
            $agent_nni = $agents_nni[0];
            $RAW_QUERY = "SELECT COUNT(DISTINCT(ag_id)) as nb_agents FROM 
            (SELECT Agent.id AS ag_id, UoAgent.uo_id as uo_id, UoAgent.niveau as niveau
            FROM `UoAgent`
            inner join Agent on UoAgent.agent_id = Agent.id
            inner join Equipe on Agent.equipe_id = Equipe.id
            inner join Bo on Equipe.bo_id = Bo.id
            WHERE Agent.nni = '$agent_nni'
            AND ($uos_txt)
            and niveau = 4 
            order by ag_id, uo_id) 
            AS req";
        }
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $data = $statement->fetchAll();

        return new JsonResponse($data);
    }
	/**
     * @Route("/insert_dom_json", name="insert_dom_json")
     */
    public function insert_dom(Request $request)
    {
        $agent_id = $request->get('agent_id'); 
        $domaine_nom = $request->get('domaine_nom');

        $sousdomaines_nom = $request->get('sousdomaines_nom');
        
        $em = $this->get('doctrine.orm.entity_manager');
        $agent = $em->getRepository('AppBundle:Agent')->findOneById($agent_id);

        $dom_id = $em->getRepository('AppBundle:Domaine')->findOneBy(array('nom' => $domaine_nom));
        //$d_id = $dom_id->getId();

        $domaine = $em->getRepository('AppBundle:Domaine')->findOneByNom($domaine_nom);

       // $sousDomaines = $domaine->getSousdomaines();
       
       $sousDomaines = [];
       for($i = 0; $i < count($sousdomaines_nom); $i++){
           $sousDomaine = $em->getRepository('AppBundle:SousDomaine')->findOneBy(array('nom' => $sousdomaines_nom[$i]));
           array_push($sousDomaines, $sousDomaine);
       }

        $uos = [];
        foreach($sousDomaines as $s){
            $uos_tab = [];
            array_push($uos_tab, $s->getUos());
            foreach($uos_tab as $u){
                array_push($uos, $u);
            }
        }

        foreach($uos as $uo){
            foreach($uo as $u_ag){
                $UoAgent = new UoAgent();
                $UoAgent->setAgent($agent);
                $UoAgent->setUo($u_ag);
                $UoAgent->setNiveau(0);
                $UoAgent->setPriorite(2);
                $UoAgent->setNPlusUn(0);
                $UoAgent->setNPlusDeux(0);
                $UoAgent->setNPlusTrois(0);

                $em->persist($UoAgent);

                $em->flush();
            }
        }

        return new Response('ok', 200);
        
    }

	/**
     * @Route("/pst", name="pst")
     */
    public function pst(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $poles = $em->getRepository('AppBundle:Bo')->getAllPole();

        $bos = $em->getRepository('AppBundle:Bo')->getAllBo();
        $nnis = $em->getRepository('AppBundle:Agent')->getAllNni();
        $polesbos = $em->getRepository('AppBundle:Bo')->findAll();
        $agents = $em->getRepository('AppBundle:Agent')->getAllAgent();
        $domaines = $em->getRepository('AppBundle:Domaine')->getAllDomaine();
        $sousdomaines = $em->getRepository('AppBundle:SousDomaine')->getAllSousDomaine();
        $uos = $em->getRepository('AppBundle:Uo')->getAllUo();


        $titre = " Cartographie des compagnons PST";

        $pst = $em->getRepository('AppBundle:UoAgent')->getPST();
        $pst_agent = $em->getRepository('AppBundle:UoAgent')->getPST_for_Agents();
        $pst_agent_detail = $em->getRepository('AppBundle:UoAgent')->getPST_detail_Agents();


        return $this->render('AppBundle:Default:pst.html.twig', array(
            'poles'=>$poles,
            'bos'=>$bos,
            'nnis' => $nnis,
            'agents'=>$agents,
            'titre' => $titre,
            'pst' => $pst,
            'pst_agent' => $pst_agent,
            'pst_agent_detail' => $pst_agent_detail,
            'domaines' => $domaines,
            'sousdomaines' => $sousdomaines,
            'uos' => $uos,
            'polesbos' => $polesbos,
        ));
    }


    /**
     * @Route("/graphCartographieCompetenceAgent/{id}", name="graphCartographieCompetenceAgent")
     */
    public function graphCartographieCompetenceAgent(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");
        $agent_id = $request->get("id");

        $infos_agent = $em->getRepository('AppBundle:Agent')->getInfosAgent($agent_id);

        $domaines = $em->getRepository('AppBundle:Domaine')->getAllDomaine();
        $agent_domaines = $em->getRepository('AppBundle:Domaine')->getDomaineAgent($agent_id);
        $domaines_soudomaines = $em->getRepository('AppBundle:SousDomaine')->getAllDomaineSousDomaine();
        $agent_domaines_sousdomaines = $em->getRepository('AppBundle:Domaine')->getDomaineSousDomaineAgent($agent_id);
        $bos = $em->getRepository('AppBundle:Agent')->getBoAgent($agent_id);
        
        $bo = $bos[0];
        $bo_id = $bo['bo_id'];
        $bo_nom = $bo['bo_nom'];

        $rslt = $em->getRepository('AppBundle:UoAgent')->getResultAgent($agent_id);
        $rslt_dom = $em->getRepository('AppBundle:UoAgent')->getResultAgentDom($agent_id);
        $rslt_bo_dom = $em->getRepository('AppBundle:UoAgent')->getResultBoDom($bo_id);
        $rslt_bo_sdom = $em->getRepository('AppBundle:UoAgent')->getResultBoSdom($bo_id);
        $rslt_uo = $em->getRepository('AppBundle:UoAgent')->getResultAgentUO($agent_id);

        $agent = $em->getRepository("AppBundle:Agent")->find($agent_id);
        $titre = " Cartographie des compétences de l'agent : ".$agent->getPrenom()." ".$agent->getNom();

        return $this->render('AppBundle:Default:graphCartographieCompetenceAgent.html.twig', array('agent' => $agent, 
        'titre' => $titre, 'rslt' => $rslt, 'rslt_dom' => $rslt_dom, 'domaines' => $domaines,  'domaines_soudomaines' => $domaines_soudomaines,
        'bo_nom' => $bo_nom, 'rslt_bo_dom' => $rslt_bo_dom, 'rslt_bo_sdom' => $rslt_bo_sdom, 'rslt_uo' => $rslt_uo, 'infos_agent' => $infos_agent, 
        'agent_domaines' => $agent_domaines, 'agent_domaines_sousdomaines' => $agent_domaines_sousdomaines));
    }
    /**
     * @Route("/aie", name="aie")
     */
    public function aie(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");

        $domaines = $em->getRepository('AppBundle:Domaine')->getAllDomaine();
        $domaines_soudomaines = $em->getRepository('AppBundle:SousDomaine')->getAllDomaineSousDomaine();

        $rslt = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent("", "", "", "", "");
        $rslt_dom = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine("", "", "", "");

        $bos = $em->getRepository('AppBundle:Bo')->getAllBo();
        $poles = $em->getRepository('AppBundle:Bo')->getAllPole();
        $poles_bos = $em->getRepository('AppBundle:Bo')->findPolesBos();

        $agents_id = $em->getRepository('AppBundle:Agent')->getAgentsId();  

        $titre = " Cartographie des compétences en AIE";

        return $this->render('others/aie.html.twig', array( 
        'titre' => $titre, 'domaines' => $domaines,  'domaines_soudomaines' => $domaines_soudomaines, 'rslt' => $rslt, 'rslt_dom' => $rslt_dom,
        'poles_bos' => $poles_bos, 'bos' => $bos, 'poles' => $poles, 'agents_id' => $agents_id));
    }
    /**
     * @Route("/evolutionMMSAIE", name="evolutionMMSAIE")
     */
    public function evolutionMMSAIE(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");

        $domaines = $em->getRepository('AppBundle:Domaine')->getAllDomaine();
        $domaines_soudomaines = $em->getRepository('AppBundle:SousDomaine')->getAllDomaineSousDomaine();

        $rslt = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersNoAgent("", "", "", "", "");
        $rslt_dom = $em->getRepository('AppBundle:UoAgent')->getResultAllBoByFiltersDomaine("", "", "", "");

        $bos = $em->getRepository('AppBundle:Bo')->getAllBo();
        $poles = $em->getRepository('AppBundle:Bo')->getAllPole();
        $poles_bos = $em->getRepository('AppBundle:Bo')->findPolesBos();

        $agents_id = $em->getRepository('AppBundle:Agent')->getAgentsId();  

        $titre = " Évolution MMS et attendu cible en AIE";

        return $this->render('AppBundle:Default:evolutionMMSAIE.html.twig', array( 
        'titre' => $titre, 'domaines' => $domaines,  'domaines_soudomaines' => $domaines_soudomaines, 'rslt' => $rslt, 'rslt_dom' => $rslt_dom,
        'poles_bos' => $poles_bos, 'bos' => $bos, 'poles' => $poles, 'agents_id' => $agents_id));
    }
    /**
     * @Route("/tab_evol_mms_json", name="tab_evol_mms_json")
     */
    public function tab_evol_mms_json(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");

        $bo = $request->get('bo');

        $data = $em->getRepository('AppBundle:UoAgent')->getEvolMMS($bo);

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT Bo.nom as bo, Domaine.nom as domaine, count(distinct(UO.code)) as nb_uos FROM `UO`
                        inner join UoAgent on UO.code = UoAgent.uo_id
                        inner join SousDomaine on UO.sousdomaine_id = SousDomaine.id
                        inner join Domaine on SousDomaine.domaine_id = Domaine.id
                        inner join Agent on UoAgent.agent_id = Agent.id
                        inner join Equipe on Agent.equipe_id = Equipe.id
                        inner join Bo on Equipe.bo_id = Bo.id
                        where UoAgent.niveau > 0
                        group by Bo.nom, Domaine.nom";
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $data_completude = $statement->fetchAll();

        //$data_completude = $em->getRepository('AppBundle:UoAgent')->getCompletudeMMS($bo);

        $data_uos = $em->getRepository('AppBundle:UoAgent')->getUoMMS($bo);
        
        $d = [];
        $d['evol'] = $data;
        $d['completude'] = $data_completude;
        $d['uos'] = $data_uos;

        return new JsonResponse($d);
    }
    /**
     * @Route("/tab_evol_mms_nPlus_json", name="tab_evol_mms_nPlus_json")
     */
    public function tab_evol_mms_nPlus_json(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");

        $anneeSelect = $request->get('anneeSelect');

        $data = $em->getRepository('AppBundle:UoAgent')->getEvolMMS_nPlus($anneeSelect);

        return new JsonResponse($data);
    }
    /**
     * @Route("/tab_evol_mms_agent_json", name="tab_evol_mms_agent_json")
     */
    public function tab_evol_mms_agent_json(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");

        $bo = $request->get('bo');

        $data = $em->getRepository('AppBundle:UoAgent')->getEvolMMS_Agents($bo);

        /*$em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT Bo.nom as bo, Domaine.nom as domaine, Agent.nom, Agent.prenom, count(distinct(UO.code)) as nb_uos FROM `UO`
                        inner join UoAgent on UO.code = UoAgent.uo_id
                        inner join SousDomaine on UO.sousdomaine_id = SousDomaine.id
                        inner join Domaine on SousDomaine.domaine_id = Domaine.id
                        inner join Agent on UoAgent.agent_id = Agent.id
                        inner join Equipe on Agent.equipe_id = Equipe.id
                        inner join Bo on Equipe.bo_id = Bo.id
                        where $col_bo $bo
                        group by Bo.nom, Domaine.nom, Agent.nni";
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        $data_completude = $statement->fetchAll();

        $data_uos = $em->getRepository('AppBundle:UoAgent')->getUoMMS_Agents($bo);
        
        $d = [];
        $d['evol'] = $data;
        $d['completude'] = $data_completude;
        $d['uos'] = $data_uos;*/

        return new JsonResponse($data);
    }
    /**
     * @Route("/tab_evol_mms_nPlus_agent_json", name="tab_evol_mms_nPlus_agent_json")
     */
    public function tab_evol_mms_nPlus_agent_json(Request $request)
    {
        $em = $this->get("doctrine.orm.entity_manager");

        $bo = $request->get('bo');
        $anneeSelect = $request->get('anneeSelect');

        $data = $em->getRepository('AppBundle:UoAgent')->getEvolMMS_nPlus_Agents($anneeSelect, $bo);

        return new JsonResponse($data);
    }
    /**
     * @Route("/liste_agents_json", name="liste_agents_json")
     */
    public function liste_agents_json(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        
        $pole = $request->get('pole');
        $bo = $request->get('bo'); 
        $profil =  $request->get('profil');
        $astreinte = $request->get('astreinte');
        
        $data = $em->getRepository('AppBundle:Agent')->getListeAgents($pole, $bo, $profil, $astreinte);

        return new JsonResponse($data);
    }


    function parseCSV($path) {
        $convert = function( $str ) {
            return iconv( "CP1252", "UTF-8", $str );
        };

        if(($handle = fopen($path, 'r')) !== false)
        {
            $header = fgetcsv($handle);
            $zz = 0;
            while(($data = fgetcsv($handle, 10000,";")) !== false)
            {
                $zz++;
                //echo("<div>$zz</div>");


                $data = array_map($convert, $data);

                $em = $this->get("doctrine.orm.entity_manager");

                $codeUo = $data[9];
                $nni = $data[0];

                $uo = $em->getRepository("AppBundle:Uo")->find($codeUo);

                $agent = $em->getRepository("AppBundle:Agent")->findBy(array("nni"=>$nni));

                $uoAgent = $em->getRepository("AppBundle:UoAgent")->findBy(array("agent"=>$agent, "uo"=>$uo));

                if(!$uoAgent) {
                    if (!$uo) {
                        $nomDomaine = $data[7];
                        $nomSousDomaine = $data[8];
                        $nomUo = $data[10];

                        $domaine = $em->getRepository("AppBundle:Domaine")->findBy(array("nom" => $nomDomaine));
                        $sousDomaine = $em->getRepository("AppBundle:SousDomaine")->findBy(array("nom" => $nomSousDomaine));

                        if (!$sousDomaine) {
                            if (!$domaine) {
                                $domaine = new Domaine();
                                $domaine->setNom($nomDomaine);

                                $em->persist($domaine);
                            } else {
                                $domaine = $domaine[0];
                            }
                            $sousDomaine = new SousDomaine();
                            $sousDomaine->setNom($nomSousDomaine);
                            $sousDomaine->setDomaine($domaine);

                            $em->persist($sousDomaine);
                        } else {
                            $sousDomaine = $sousDomaine[0];
                        }

                        $uo = new Uo();
                        $uo->setNom($nomUo);
                        $uo->setCode($codeUo);
                        $uo->setSousdomaine($sousDomaine);

                        $em->persist($uo);
                    }
                    $nomAgent = $data[1];
                    $prenomAgent = $data[2];
                    $nomDr = $data[3];
                    $nomAgence = $data[4];
                    $nomBo = $data[5];
                    $nomEquipe = $data[6];

                    $agence = $em->getRepository("AppBundle:Agence")->findBy(array("nom" => $nomAgence));

                    if (!$agent) {
                        $equipe = $em->getRepository("AppBundle:Equipe")->findBy(array("nom" => $nomEquipe));

                        if (!$equipe) {
                            $bo = $em->getRepository("AppBundle:Bo")->findBy(array("nom" => $nomBo));

                            if (!$bo) {
                                if (!$agence) {
                                    $dr = $em->getRepository("AppBundle:Dr")->findBy(array("nom" => $nomDr));

                                    if (!$dr) {
                                        $dr = new Dr();
                                        $dr->setNom($nomDr);

                                        $em->persist($dr);
                                    } else {
                                        $dr = $dr[0];
                                    }

                                    $agence = new Agence();
                                    $agence->setNom($nomAgence);
                                    $agence->setDr($dr);

                                    $em->persist($agence);
                                } else {
                                    $agence = $agence[0];
                                }
								
								$nomBoMaj = strtoupper($nomBo);
								
								if(substr($nomBoMaj, 0, 2) == "BO"){
									$nomBo = substr($nomBo, 3);
								}

                                $bo = new Bo();
                                $bo->setNom($nomBo);
                                $bo->setAgence($agence);

                                $em->persist($bo);
                            } else {
                                $bo = $bo[0];
                            }
                            
                            if(substr($nomEquipe, 0, 2) == "Eq"){
                                $nomEquipe = substr($nomEquipe, 7);
                            }

                            $equipe = new Equipe();
                            $equipe->setNom($nomEquipe);
                            $equipe->setBo($bo);

                            $em->persist($equipe);
                        } else {
                            $equipe = $equipe[0];
                        }

                        $agent = new Agent();
                        $agent->setNom($nomAgent);
                        $agent->setNni($nni);
                        $agent->setPrenom($prenomAgent);
                        $agent->setEquipe($equipe);

                        $em->persist($agent);
                    } else {
                        $agent = $agent[0];
                    }


                    $uoAgent = new UoAgent();
                    $uoAgent->setNiveau($data[11]);
                    $uoAgent->setPriorite($data[12]);
                    $uoAgent->setAgent($agent);
                    $uoAgent->setUo($uo);

                }else{
                    $uoAgent = $uoAgent[0];
                    $uoAgent->setNiveau($data[11]);
                    $uoAgent->setPriorite($data[12]);
                }
				
                $em->persist($uoAgent);

                $em->flush();

                unset($data);
            }
            fclose($handle);
        }
    }
}
