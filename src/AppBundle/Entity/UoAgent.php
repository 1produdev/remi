<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UoAgent
 *
 * @ORM\Table(name="UoAgent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UoAgentRepository")
 */
class UoAgent
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
     * @var int
     *
     * @ORM\Column(name="niveau", type="integer", nullable=false)
     */
    private $niveau;

    /**
     * @var int
     *
     * @ORM\Column(name="priorite", type="integer", nullable=false)
     */
    private $priorite;

    /**
     * @var int
     *
     * @ORM\Column(name="nPlusUn", type="integer", nullable=true)
     */
    private $nPlusUn;

    /**
     * @var int
     *
     * @ORM\Column(name="nPlusDeux", type="integer", nullable=true)
     */
    private $nPlusDeux;

    /**
     * @var int
     *
     * @ORM\Column(name="nPlusTrois", type="integer", nullable=true)
     */
    private $nPlusTrois;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agent", inversedBy="uoAgents")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Uo", inversedBy="UoAgents")
     * @ORM\JoinColumn(referencedColumnName="code", nullable=true)
     */
    private $uo;

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
     * @return int
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param int $niveau
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }

    /**
     * @return int
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * @param int $priorite
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;
    }

    /**
     * @return int
     */
    public function getNPlusUn()
    {
        return $this->nPlusUn;
    }

    /**
     * @param int $nPlusUn
     */
    public function setNPlusUn($nPlusUn)
    {  
        $this->nPlusUn = $nPlusUn;
    }

    /**
     * @return int
     */
    public function getNPlusDeux()
    {
        return $this->nPlusDeux;
    }

    /**
     * @param int $nPlusDeux
     */
    public function setNPlusDeux($nPlusDeux)
    {
        $this->nPlusDeux = $nPlusDeux;
    }

    /**
     * @return int
     */
    public function getNPlusTrois()
    {
        return $this->nPlusTrois;
    }

    /**
     * @param int $nPlusTrois
     */
    public function setNPlusTrois($nPlusTrois)
    {
        $this->nPlusTrois = $nPlusTrois;
    }

    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getUo()
    {
        return $this->uo;
    }

    /**
     * @param mixed $uo
     */
    public function setUo($uo)
    {
        $this->uo = $uo;
    }

}
