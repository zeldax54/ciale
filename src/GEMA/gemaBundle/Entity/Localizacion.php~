<?php

namespace GEMA\gemaBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Localizacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\LocalizacionRepository")
 */
class Localizacion
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;
    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Localizacion" , mappedBy="localizacion", cascade={"persist"})
     */
    protected $departamentos;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Localizacion
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departamentos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add departamentos
     *
     * @param \GEMA\gemaBundle\Entity\Localizacion $departamentos
     * @return Localizacion
     */
    public function addDepartamento(\GEMA\gemaBundle\Entity\Localizacion $departamentos)
    {
        $this->departamentos[] = $departamentos;

        return $this;
    }

    /**
     * Remove departamentos
     *
     * @param \GEMA\gemaBundle\Entity\Localizacion $departamentos
     */
    public function removeDepartamento(\GEMA\gemaBundle\Entity\Localizacion $departamentos)
    {
        $this->departamentos->removeElement($departamentos);
    }

    /**
     * Get departamentos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartamentos()
    {
        return $this->departamentos;
    }

    public function __toString() {
        return $this->nombre;
    }

}
