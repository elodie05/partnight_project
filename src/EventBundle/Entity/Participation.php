<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ParticipationRepository")
 */
class Participation
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
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event")
     * @ORM\JoinColumn
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn
     */
    private $user;

    /**
     * @ORM\Column(name="response", type="boolean", nullable=true)
     */
    private $response;
    
    /**
     * @ORM\Column(name="sleep", type="boolean", nullable=true)
     */
    private $sleep;

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
     * Set response
     *
     * @param boolean $response
     * @return Participation
     */
    public function setResponse($response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return strig
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Set sleep
     *
     * @param boolean $sleep
     * @return Participation
     */
    public function setSleep($sleep = null)
    {
    	$this->sleep = $sleep;
    
    	return $this;
    }
    
    /**
     * Get sleep
     *
     * @return boolean
     */
    public function getSleep()
    {
    	return $this->sleep;
    }

    /**
     * Set event
     *
     * @param \EventBundle\Entity\Event $event
     * @return Participation
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Participation
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
}
