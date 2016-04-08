<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provisions
 *
 * @ORM\Table(name="provisions")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ProvisionsRepository")
 */
class Provisions
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
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\DrinkFood")
     * @ORM\JoinColumn
     */
    private $drinkFood;
    

    /**
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn

     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event")
     * @ORM\JoinColumn
    
     */
    private $event;

    

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
     * Set name
     *
     * @param string $name
     * @return DrinkFood
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return DrinkFood
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return DrinkFood
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set event
     *
     * @param \EventBundle\Entity\Event $event
     * @return DrinkFood
     */
    public function setEvent(\EventBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \EventBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set drinkFood
     *
     * @param \EventBundle\Entity\DrinkFood $drinkFood
     * @return Provisions
     */
    public function setDrinkFood(\EventBundle\Entity\DrinkFood $drinkFood = null)
    {
        $this->drinkFood = $drinkFood;

        return $this;
    }

    /**
     * Get drinkFood
     *
     * @return \EventBundle\Entity\DrinkFood 
     */
    public function getDrinkFood()
    {
        return $this->drinkFood;
    }
}
