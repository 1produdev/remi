<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 *
 * @ORM\Table(name="Equipe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EquipeRepository")
 */
class Equipe
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
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Bo", inversedBy="equipes")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $bo;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Agent", mappedBy="equipe")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $agents;

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
    public function getBo()
    {
        return $this->bo;
    }

    /**
     * @param mixed $bo
     */
    public function setBo($bo)
    {
        $this->bo = $bo;
    }

    /**
     * @return mixed
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * @param mixed $agents
     */
    public function setAgents($agents)
    {
        $this->agents = $agents;
    }
}
