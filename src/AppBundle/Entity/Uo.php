<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Uo
 *
 * @ORM\Table(name="UO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UoRepository")
 */
class Uo{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="code", type="string", length=50, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=250, nullable=false)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SousDomaine", inversedBy="uos")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $sousdomaine;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UoAgent", mappedBy="uo")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $UoAgents;

    /**
     * Uo constructor.
     */
    public function __construct()
    {
    }

    public function __toString() {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return mixed
     */
    public function getSousdomaine()
    {
        return $this->sousdomaine;
    }

    /**
     * @param mixed $sousdomaine
     */
    public function setSousdomaine($sousdomaine)
    {
        $this->sousdomaine = $sousdomaine;
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

}