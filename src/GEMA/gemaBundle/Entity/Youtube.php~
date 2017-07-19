<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Youtube
 *
 * @ORM\Table(name="youtube")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\YoutubeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Youtube
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Toro",inversedBy="youtubes", cascade={"persist"})
     */

    private $toro;



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
     * Set url
     *
     * @param string $url
     * @return Youtube
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set toros
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toros
     * @return Youtube
     */
    public function setToros(\GEMA\gemaBundle\Entity\Toro $toros = null)
    {
        $this->toros = $toros;

        return $this;
    }

    /**
     * Get toros
     *
     * @return \GEMA\gemaBundle\Entity\Toro 
     */
    public function getToros()
    {
        return $this->toros;
    }

    /**
     * Set toro
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toro
     * @return Youtube
     */
    public function setToro(\GEMA\gemaBundle\Entity\Toro $toro = null)
    {
        $this->toro = $toro;

        return $this;
    }

    /**
     * Get toro
     *
     * @return \GEMA\gemaBundle\Entity\Toro 
     */
    public function getToro()
    {
        return $this->toro;
    }
}
