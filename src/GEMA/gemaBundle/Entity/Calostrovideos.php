<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * Calostrovideos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\CalostrovideosRepository")
 * @ORM\Table(name="calostrovideos")
 */
class Calostrovideos {

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
     * @ORM\Column(name="titulo", type="text",nullable=false )
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="videoby", type="text",nullable=false )
     */
    private $videoby;

    /**
     * @var string
     *
     * @ORM\Column(name="urlvideo", type="string",nullable=false )
     */
    private $urlvideo;

     /**
     * @var string
     *
     * @ORM\Column(name="urlbackimg", type="string",nullable=false )
     */
    private $urlbackimg;

     /**
     * @var string
     *
     * @ORM\Column(name="urlminiatura", type="string",nullable=false )
     */
    private $urlminiatura;


    


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
     * Set titulo
     *
     * @param string $titulo
     * @return Calostrovideos
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set videoby
     *
     * @param string $videoby
     * @return Calostrovideos
     */
    public function setVideoby($videoby)
    {
        $this->videoby = $videoby;

        return $this;
    }

    /**
     * Get videoby
     *
     * @return string 
     */
    public function getVideoby()
    {
        return $this->videoby;
    }

    /**
     * Set urlvideo
     *
     * @param string $urlvideo
     * @return Calostrovideos
     */
    public function setUrlvideo($urlvideo)
    {
        $this->urlvideo = $urlvideo;

        return $this;
    }

    /**
     * Get urlvideo
     *
     * @return string 
     */
    public function getUrlvideo()
    {
        return $this->urlvideo;
    }

    /**
     * Set urlbackimg
     *
     * @param string $urlbackimg
     * @return Calostrovideos
     */
    public function setUrlbackimg($urlbackimg)
    {
        $this->urlbackimg = $urlbackimg;

        return $this;
    }

    /**
     * Get urlbackimg
     *
     * @return string 
     */
    public function getUrlbackimg()
    {
        return $this->urlbackimg;
    }

    /**
     * Set urlminiatura
     *
     * @param string $urlminiatura
     * @return Calostrovideos
     */
    public function setUrlminiatura($urlminiatura)
    {
        $this->urlminiatura = $urlminiatura;

        return $this;
    }

    /**
     * Get urlminiatura
     *
     * @return string 
     */
    public function getUrlminiatura()
    {
        return $this->urlminiatura;
    }
}