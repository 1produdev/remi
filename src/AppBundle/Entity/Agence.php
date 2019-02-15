<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Agence
 *
 * @ORM\Table(name="Agence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgenceRepository")
 */
class Agence
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dr", inversedBy="agences")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $dr;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bo", mappedBy="agence")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $bos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Equipe", mappedBy="agence")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $equipes;

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
    public function getDr()
    {
        return $this->dr;
    }

    /**
     * @param mixed $dr
     */
    public function setDr($dr)
    {
        $this->dr = $dr;
    }

    /**
     * @return mixed
     */
    public function getBos()
    {
        return $this->bos;
    }

    /**
     * @param mixed $bos
     */
    public function setBos($bos)
    {
        $this->bos = $bos;
    }

    /**
     * @return mixed
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * @param mixed $equipes
     */
    public function setEquipes($equipes)
    {
        $this->equipes = $equipes;
    }

}
