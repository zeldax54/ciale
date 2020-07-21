<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use Doctrine\ORM\Mapping\ManyToMany;



/**
 * Catalogo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\CatalogohojasRepository")
 * @ORM\Table(name="catalogohojas")
 */
class Catalogohojas {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")     
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="text",nullable=false )
     */
    private $tipo;

    /**
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=false)
     */
    private $guid;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Catalogo",inversedBy="hojas", cascade={"persist"})
     * @Exclude
     */

    private $catalogo;  

        /**
     * @ORM\ManyToMany(targetEntity="GEMA\gemaBundle\Entity\Toro", inversedBy="hojas")
     * @ORM\JoinTable(name="torohojas",
     *     joinColumns={@ORM\JoinColumn(name="hojaid", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="toroid",referencedColumnName="id")})
     * @Assert\Valid
     */   
    
    private $toros;






    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->toros = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set numero
     *
     * @param integer $numero
     * @return Catalogohojas
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Catalogohojas
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Catalogohojas
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set catalogo
     *
     * @param \GEMA\gemaBundle\Entity\Catalogo $catalogo
     * @return Catalogohojas
     */
    public function setCatalogo(\GEMA\gemaBundle\Entity\Catalogo $catalogo = null)
    {
        $this->catalogo = $catalogo;

        return $this;
    }

    /**
     * Get catalogo
     *
     * @return \GEMA\gemaBundle\Entity\Catalogo 
     */
    public function getCatalogo()
    {
        return $this->catalogo;
    }

    /**
     * Add toros
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toros
     * @return Catalogohojas
     */
    public function addToro(\GEMA\gemaBundle\Entity\Toro $toros)
    {
        $this->toros[] = $toros;

        return $this;
    }

    /**
     * Remove toros
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toros
     */
    public function removeToro(\GEMA\gemaBundle\Entity\Toro $toros)
    {
        $this->toros->removeElement($toros);
    }

    /**
     * Get toros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getToros()
    {
        return $this->toros;
    }
}
