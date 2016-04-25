<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requirement
 *
 * @ORM\Table(name="requirement")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\RequirementRepository")
 */
class Requirement
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
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Item")
     * @ORM\JoinColumn
     */
    private $item;
    

    /**
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event", inversedBy="requirements")
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
     * Set quantity
     *
     * @param integer $quantity
     * @return Requirement
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
     * Set item
     *
     * @param \EventBundle\Entity\Item $item
     * @return Requirement
     */
    public function setItem(\EventBundle\Entity\Item $item){
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \EventBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }


    /**
     * Set event
     *
     * @param \EventBundle\Entity\Event $event
     * @return Requirement
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
}
