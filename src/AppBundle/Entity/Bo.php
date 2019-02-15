<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Bo
 *
 * @ORM\Table(name="Bo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BoRepository")
 */
class Bo
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
     * @var string
     *
     * @ORM\Column(name="pole", type="string", length=50, nullable=true)
     */
    private $pole;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence", inversedBy="bos")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $agence;


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
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * @param mixed $agence
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;
    }

    /**
     * @return string
     */
    public function getPole()
    {
        return $this->pole;
    }

    /**
     * @param string $pole
     */
    public function setPole($pole)
    {
        $this->pole = $pole;
    }


}
