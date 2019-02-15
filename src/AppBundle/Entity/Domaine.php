<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="Domaine")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DomaineRepository")
 */
class Domaine{
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SousDomaine", mappedBy="domaine")
     * @ORM\JoinColumn(referencedColumnName="code", nullable=true)
     */
    private $sousdomaines;

    /**
     * Domaine constructor.
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
    public function getSousdomaines()
    {
        return $this->sousdomaines;
    }

    /**
     * @param mixed $sousdomaines
     */
    public function setSousdomaines($sousdomaines)
    {
        $this->sousdomaines = $sousdomaines;
    }
}