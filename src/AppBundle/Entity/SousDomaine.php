<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousDomaine
 *
 * @ORM\Table(name="SousDomaine")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SousDomaineRepository")
 */
class SousDomaine{
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Uo", mappedBy="sousdomaine")
     * @ORM\JoinColumn(referencedColumnName="code", nullable=true)
     */
    private $uos;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Domaine", inversedBy="sousdomaines")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $domaine;

    /**
     * SousDomaine constructor.
     */
    public function __construct()
    {
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
    public function getUos()
    {
        return $this->uos;
    }

    /**
     * @param mixed $uos
     */
    public function setUos($uos)
    {
        $this->uos = $uos;
    }

    /**
     * @return mixed
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * @param mixed $domaine
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
    }


}