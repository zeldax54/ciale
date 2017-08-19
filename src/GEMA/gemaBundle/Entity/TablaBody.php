<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TablaDatos
 *
 * @ORM\Table(name="tablabody")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\TablaBodyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TablaBody
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
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Tabla",inversedBy="tabladatos", cascade={"persist"})
     */

    private $tabla;


    /**
     * @var string
     * @ORM\Column(name="rowname", type="string", length=10)
     */
    private $rowname;//Nombre del row



    /**
     * @var string
     * @ORM\Column(name="numberOfcells", type="integer")
     */
    private $numberOfcells;//Numero de celdas: debe coincidir con las columnas de la tabla


    /**
     * @var integer
     * @ORM\Column(name="lejania", type="integer")
     */
    private $lejania;//numero de lejania del dato madre
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
     * Set rowname
     *
     * @param string $rowname
     * @return TablaBody
     */
    public function setRowname($rowname)
    {
        $this->rowname = $rowname;

        return $this;
    }

    /**
     * Get rowname
     *
     * @return string 
     */
    public function getRowname()
    {
        return $this->rowname;
    }



    /**
     * Set tabla
     *
     * @param \GEMA\gemaBundle\Entity\Tabla $tabla
     * @return TablaBody
     */
    public function setTabla(\GEMA\gemaBundle\Entity\Tabla $tabla = null)
    {
        $this->tabla = $tabla;

        return $this;
    }

    /**
     * Get tabla
     *
     * @return \GEMA\gemaBundle\Entity\Tabla 
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Set numberOfcells
     *
     * @param integer $numberOfcells
     * @return TablaBody
     */
    public function setNumberOfcells($numberOfcells)
    {
        $this->numberOfcells = $numberOfcells;

        return $this;
    }

    /**
     * Get numberOfcells
     *
     * @return integer 
     */
    public function getNumberOfcells()
    {
        return $this->numberOfcells;
    }

    /**
     * Set lejania
     *
     * @param integer $lejania
     * @return TablaBody
     */
    public function setLejania($lejania)
    {
        $this->lejania = $lejania;

        return $this;
    }

    /**
     * Get lejania
     *
     * @return integer 
     */
    public function getLejania()
    {
        return $this->lejania;
    }
}
