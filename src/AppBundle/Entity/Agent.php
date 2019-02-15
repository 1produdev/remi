<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Agent
 *
 * @ORM\Table(name="Agent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgentRepository")
 */
class Agent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nni", type="string", length=20, nullable=false)
     */
    private $nni;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=100, nullable=false)
     */
    private $prenom;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UoAgent", mappedBy="agent")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $uoAgents;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipe", inversedBy="agents")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $equipe;

     /**
     * @var string
     *
     * @ORM\Column(name="profil", type="string", length=50, nullable=true)
     */
    private $profil;
    /**
     * @var string
     *
     * @ORM\Column(name="abstreinte", type="string", length=3, nullable=true)
     */
    private $abstreinte;
    /**
     * @var string
     *
     * @ORM\Column(name="retraite", type="string", length=30, nullable=true)
     */
    private $retraite;
    /**
     * @var string
     *
     * @ORM\Column(name="mobilite", type="string", length=50, nullable=true)
     */
    private $mobilite;
    /**
     * @var string
     *
     * @ORM\Column(name="parti", type="string", length=3, nullable=true)
     */
    private $parti;


    public function __construct() {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNni()
    {
        return $this->nni;
    }

    /**
     * @param string $nni
     */
    public function setNni($nni)
    {
        $this->nni = $nni;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getUoAgents()
    {
        return $this->UoAgents;
    }

    /**
     * @param mixed $UoAgents
     */
    public function setUoAgents($UoAgents)
    {
        $this->UoAgents = $UoAgents;
    }

    /**
     * @return mixed
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * @param mixed $equipe
     */
    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;
    }

    /**
     * @return string
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * @param string $profil
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;
    }
    /**
     * @return string
     */
    public function getAbstreinte()
    {
        return $this->abstreinte;
    }

    /**
     * @param string $abstreinte
     */
    public function setAbstreinte($abstreinte)
    {
        $this->abstreinte = $abstreinte;
    }
    /**
     * @return string
     */
    public function getRetraite()
    {
        return $this->retraite;
    }

    /**
     * @param string $retraite
     */
    public function setRetraite($retraite)
    {
        $this->retraite = $retraite;
    }
    /**
     * @return string
     */
    public function getMobilite()
    {
        return $this->mobilite;
    }

    /**
     * @param string $mobilite
     */
    public function setMobilite($mobilite)
    {
        $this->mobilite = $mobilite;
    }

    /**
     * @return string
     */
    public function getParti()
    {
        return $this->parti;
    }

    /**
     * @param string $abstreinte
     */
    public function setParti($parti)
    {
        $this->parti = $parti;
    }

}
