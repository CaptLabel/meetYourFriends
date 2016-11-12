<?php

namespace CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stay
 *
 * @ORM\Table(name="stay")
 * @ORM\Entity(repositoryClass="CommonBundle\Repository\StayRepository")
 */
class Stay
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_arrival", type="date")
     */
    private $dateArrival;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_departure", type="date")
     */
    private $dateDeparture;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="CommonBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */

    private $user;

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
     * Set dateArrival
     *
     * @param \DateTime $dateArrival
     * @return Stay
     */
    public function setDateArrival($dateArrival)
    {
        $this->dateArrival = $dateArrival;

        return $this;
    }

    /**
     * Get dateArrival
     *
     * @return \DateTime 
     */
    public function getDateArrival()
    {
        return $this->dateArrival;
    }

    /**
     * Set dateDeparture
     *
     * @param \DateTime $dateDeparture
     * @return Stay
     */
    public function setDateDeparture($dateDeparture)
    {
        $this->dateDeparture = $dateDeparture;

        return $this;
    }

    /**
     * Get dateDeparture
     *
     * @return \DateTime 
     */
    public function getDateDeparture()
    {
        return $this->dateDeparture;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Stay
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
