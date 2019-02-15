<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Dr
 *
 * @ORM\Table(name="Dr")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DrRepository")
 */
class Dr
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Agence", mappedBy="dr")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $agences;

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
    public function getAgences()
    {
        return $this->agences;
    }

    /**
     * @param mixed $agences
     */
    public function setAgences($agences)
    {
        $this->agences = $agences;
    }

}
