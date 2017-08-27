<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;




/**
 * Rol
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\MediaDescriptionRepository")
 * @ORM\Table(name="mediadescription")
 */
class MediaDescription {

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
     *
     * @ORM\Column(name="folder", type="string", length=255)
     */
    private $folder;


    /**
     * @var string
     *
     * @ORM\Column(name="subforlder", type="string", length=255,nullable=true)
     */
    private $subforlder;


    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=500)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=1255)
     */
    private $descripcion;







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
     * Set folder
     *
     * @param string $folder
     * @return MediaDescription
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return string 
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set subforlder
     *
     * @param string $subforlder
     * @return MediaDescription
     */
    public function setSubforlder($subforlder)
    {
        $this->subforlder = $subforlder;

        return $this;
    }

    /**
     * Get subforlder
     *
     * @return string 
     */
    public function getSubforlder()
    {
        return $this->subforlder;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return MediaDescription
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return MediaDescription
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
}
